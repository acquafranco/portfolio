<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Imports the initial projects from database/seeders/projects/*.php.
 * Only creates missing slugs: edits made from /admin are never overwritten.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Runs from the first projects migration: columns added by later
        // migrations (e.g. gallery) are filled by those migrations.
        $columns = array_flip(Schema::getColumnListing('projects'));

        foreach (self::definitions() as $slug => $definition) {
            if (Project::query()->where('slug', $slug)->exists()) {
                continue;
            }

            $attributes = array_intersect_key(Project::attributesFromDefinition($definition), $columns);

            if (isset($attributes['gallery'])) {
                $attributes['gallery'] = self::copyGalleryToStorage($attributes['gallery'], $slug);
            }

            foreach (Project::IMAGE_COLUMNS as $column) {
                $attributes[$column] = self::copyToStorage($attributes[$column], $slug);
            }

            // Seed images are already optimized WebP: skip the upload pipeline.
            Project::withoutEvents(fn () => Project::query()->create(['slug' => $slug, ...$attributes]));
        }
    }

    /**
     * Copy an image shipped in public/ to the "public" storage disk so the
     * admin panel can show, replace and delete it like any upload. Keeps the
     * public/ path if the copy is not possible.
     */
    public static function copyToStorage(?string $path, string $slug): ?string
    {
        if ($path === null || Project::isUploaded($path) || ! is_file(public_path($path))) {
            return $path;
        }

        $target = 'projects/'.$slug.'-'.basename($path);

        return Storage::disk('public')->put($target, (string) file_get_contents(public_path($path))) ? $target : $path;
    }

    /**
     * The seed gallery with its images copied to the storage disk.
     *
     * @param  list<array<string, mixed>>  $gallery
     * @return list<array<string, mixed>>
     */
    public static function copyGalleryToStorage(array $gallery, string $slug): array
    {
        return array_map(function (array $shot) use ($slug): array {
            $shot['image'] = self::copyToStorage($shot['image'] ?? null, $slug.'-gallery');
            $shot['image_small'] = self::copyToStorage($shot['image_small'] ?? null, $slug.'-gallery');

            return $shot;
        }, $gallery);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function definitions(): array
    {
        $definitions = [];

        foreach (glob(__DIR__.'/projects/*.php') ?: [] as $file) {
            $definitions[basename($file, '.php')] = require $file;
        }

        return $definitions;
    }
}
