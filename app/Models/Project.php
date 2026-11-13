<?php

namespace App\Models;

use App\Portfolio\ProjectImageOptimizer;
use App\Portfolio\ProjectKind;
use App\Portfolio\ProjectStatus;
use App\Portfolio\ScreenshotDevice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A portfolio project managed from /admin.
 *
 * Images: paths starting with "images/" live in public/ (shipped with the
 * repo); any other path is a file uploaded to the "public" storage disk.
 * Uploaded covers, logos and gallery screenshots are converted to WebP
 * when saved.
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property int $sort_order
 * @property bool $is_featured
 * @property bool $is_published
 * @property ProjectStatus $status
 * @property ProjectKind $kind
 * @property int|null $year
 * @property string|null $url
 * @property string|null $repository
 * @property string $accent
 * @property string|null $logo_path
 * @property string|null $cover_path
 * @property string|null $cover_small_path
 * @property list<array{image: string, image_small?: string|null, device: string, caption?: array<string, string|null>}>|null $gallery
 * @property list<string> $stack
 * @property array<string, array<string, mixed>> $content
 * @property list<array{label: string, url: string}>|null $links
 */
class Project extends Model
{
    public const IMAGE_COLUMNS = ['logo_path', 'cover_path', 'cover_small_path'];

    protected $fillable = [
        'slug', 'name', 'sort_order', 'is_featured', 'is_published', 'status', 'kind', 'year', 'url',
        'repository', 'accent', 'logo_path', 'cover_path', 'cover_small_path', 'gallery', 'stack', 'content', 'links',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'status' => ProjectStatus::class,
            'kind' => ProjectKind::class,
            'year' => 'integer',
            'sort_order' => 'integer',
            'stack' => 'array',
            'content' => 'array',
            'links' => 'array',
            'gallery' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project): void {
            if ($project->sort_order === null || $project->sort_order === 0) {
                $project->sort_order = (int) static::query()->max('sort_order') + 10;
            }
        });

        static::saving(function (Project $project): void {
            $optimizer = app(ProjectImageOptimizer::class);

            if ($project->isDirty('cover_path')) {
                if ($project->cover_path === null) {
                    $project->cover_small_path = null;
                } elseif (self::isUploaded($project->cover_path) && ! $optimizer->isOptimized($project->cover_path)) {
                    if ($result = $optimizer->cover($project->cover_path)) {
                        [$project->cover_path, $project->cover_small_path] = $result;
                    }
                }
            }

            if ($project->isDirty('logo_path') && self::isUploaded($project->logo_path) && ! $optimizer->isOptimized($project->logo_path)) {
                $project->logo_path = $optimizer->logo($project->logo_path) ?? $project->logo_path;
            }

            if ($project->isDirty('gallery')) {
                $project->gallery = self::optimizedGallery($project->gallery ?? [], $optimizer);
            }
        });

        // Remove uploaded files that were replaced or removed.
        static::updated(function (Project $project): void {
            $current = self::mediaPaths($project->only(self::IMAGE_COLUMNS), $project->gallery);
            $previous = self::mediaPaths(
                array_map(fn (string $column): ?string => $project->getOriginal($column), array_combine(self::IMAGE_COLUMNS, self::IMAGE_COLUMNS)),
                $project->getOriginal('gallery'),
            );

            foreach (array_diff($previous, $current) as $path) {
                if (self::isUploaded($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });

        static::deleted(function (Project $project): void {
            foreach (self::mediaPaths($project->only(self::IMAGE_COLUMNS), $project->gallery) as $path) {
                if (self::isUploaded($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }

    /**
     * Uploaded screenshots become WebP (desktop 1600 + 800 px, mobile 600 px).
     *
     * @param  array<int|string, array<string, mixed>>  $gallery
     * @return list<array<string, mixed>>
     */
    private static function optimizedGallery(array $gallery, ProjectImageOptimizer $optimizer): array
    {
        return array_values(array_map(function (array $item) use ($optimizer): array {
            $image = $item['image'] ?? null;

            if (is_string($image) && self::isUploaded($image) && ! $optimizer->isOptimized($image)) {
                $device = ScreenshotDevice::tryFrom((string) ($item['device'] ?? '')) ?? ScreenshotDevice::Desktop;
                $result = $optimizer->screenshot($image, $device);

                [$item['image'], $item['image_small']] = $result ?? [$image, null];
            }

            return $item;
        }, $gallery));
    }

    /**
     * Every image path referenced by the image columns and the gallery.
     *
     * @param  array<string, string|null>  $columns
     * @param  array<int|string, array<string, mixed>>|null  $gallery
     * @return list<string>
     */
    private static function mediaPaths(array $columns, ?array $gallery): array
    {
        $paths = array_values($columns);

        foreach ($gallery ?? [] as $item) {
            $paths[] = $item['image'] ?? null;
            $paths[] = $item['image_small'] ?? null;
        }

        return array_values(array_filter($paths, fn ($path): bool => is_string($path) && $path !== ''));
    }

    /**
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Whether a stored path is an uploaded file (storage disk) rather than a
     * file shipped in public/.
     */
    public static function isUploaded(?string $path): bool
    {
        return $path !== null && $path !== '' && ! str_starts_with($path, 'images/');
    }

    public static function mediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        // Built from the current host (not APP_URL) via the public/storage symlink.
        return asset(self::isUploaded($path) ? 'storage/'.ltrim($path, '/') : $path);
    }

    public static function mediaExists(?string $path): bool
    {
        if ($path === null || $path === '') {
            return true;
        }

        return self::isUploaded($path) ? Storage::disk('public')->exists($path) : is_file(public_path($path));
    }

    /**
     * Same array shape as the seed files in database/seeders/projects/.
     *
     * @return array<string, mixed>
     */
    public function toDefinition(): array
    {
        return [
            'name' => $this->name,
            'order' => $this->sort_order,
            'featured' => $this->is_featured,
            'status' => $this->status->value,
            'kind' => ($this->kind ?? ProjectKind::Product)->value,
            'year' => $this->year,
            'url' => $this->url,
            'repository' => $this->repository,
            'links' => $this->links ?? [],
            'accent' => $this->accent,
            'logo' => $this->logo_path,
            'cover' => $this->cover_path,
            'cover_small' => $this->cover_small_path,
            'gallery' => $this->gallery ?? [],
            'stack' => $this->stack ?? [],
            'content' => $this->content ?? [],
        ];
    }

    /**
     * Model attributes from a seed file definition.
     *
     * @param  array<string, mixed>  $definition
     * @return array<string, mixed>
     */
    public static function attributesFromDefinition(array $definition): array
    {
        return [
            'name' => $definition['name'],
            'sort_order' => $definition['order'] ?? 0,
            'is_featured' => $definition['featured'] ?? false,
            'is_published' => true,
            'status' => $definition['status'],
            'year' => $definition['year'] ?? null,
            'url' => $definition['url'] ?? null,
            'repository' => $definition['repository'] ?? null,
            'accent' => $definition['accent'] ?? '#FF6A1A',
            'logo_path' => $definition['logo'] ?? null,
            'cover_path' => $definition['cover'] ?? null,
            'cover_small_path' => $definition['cover_small'] ?? null,
            'gallery' => $definition['gallery'] ?? [],
            'stack' => $definition['stack'] ?? [],
            'content' => $definition['content'] ?? [],
            'links' => $definition['links'] ?? [],
            // Optional: the column default ("product") applies when omitted.
            ...(isset($definition['kind']) ? ['kind' => $definition['kind']] : []),
        ];
    }
}
