<?php

namespace App\Portfolio;

use App\Models\Project;
use Database\Seeders\ProjectSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PDOException;

/**
 * Published projects for the public site, resolved for a locale.
 *
 * Source of truth: the `projects` table (managed from /admin). If the
 * database is not reachable or not migrated yet, the seed files in
 * database/seeders/projects/ are used so the public site never goes down.
 * Invalid entries are skipped and logged.
 */
class ProjectRepository
{
    /** @var array<string, array<string, mixed>>|null */
    private ?array $definitions = null;

    /** @var array<string, Collection<int, LocalizedProject>> */
    private array $resolved = [];

    /**
     * @return Collection<int, LocalizedProject>
     */
    public function all(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return $this->resolved[$locale] ??= collect($this->definitions())
            ->filter(function (array $definition, string $slug): bool {
                $errors = $this->errorsFor($slug, $definition);

                if ($errors !== []) {
                    Log::warning("Portfolio project [{$slug}] skipped: invalid data.", ['errors' => $errors]);

                    return false;
                }

                return true;
            })
            ->map(fn (array $definition, string $slug): LocalizedProject => $this->make($slug, $definition, $locale))
            ->sortBy([
                fn (LocalizedProject $a, LocalizedProject $b): int => $a->order <=> $b->order,
                fn (LocalizedProject $a, LocalizedProject $b): int => strcasecmp($a->name, $b->name),
            ])
            ->values();
    }

    public function find(string $slug, ?string $locale = null): ?LocalizedProject
    {
        return $this->all($locale)->firstWhere('slug', $slug);
    }

    /**
     * The project shown after $project on its detail page (wraps around).
     */
    public function next(LocalizedProject $project, ?string $locale = null): ?LocalizedProject
    {
        $projects = $this->all($locale);

        if ($projects->count() < 2) {
            return null;
        }

        $index = $projects->search(fn (LocalizedProject $candidate): bool => $candidate->slug === $project->slug);

        return $projects->get(($index + 1) % $projects->count());
    }

    /**
     * Validation errors keyed by slug, including missing image files.
     *
     * @return array<string, list<string>>
     */
    public function validationErrors(): array
    {
        $errors = [];

        foreach ($this->definitions() as $slug => $definition) {
            $projectErrors = $this->errorsFor($slug, $definition);

            foreach (['logo', 'cover', 'cover_small'] as $imageKey) {
                $path = $definition[$imageKey] ?? null;

                if (! Project::mediaExists($path)) {
                    $projectErrors[] = "The {$imageKey} file [{$path}] does not exist.";
                }
            }

            if ($projectErrors !== []) {
                $errors[$slug] = $projectErrors;
            }
        }

        return $errors;
    }

    /**
     * Raw project data keyed by slug (same shape as the seed files).
     *
     * @return array<string, array<string, mixed>>
     */
    public function definitions(): array
    {
        if ($this->definitions !== null) {
            return $this->definitions;
        }

        try {
            return $this->definitions = Project::query()
                ->published()
                ->orderBy('sort_order')
                ->get()
                ->mapWithKeys(fn (Project $project): array => [$project->slug => $project->toDefinition()])
                ->all();
        } catch (QueryException|PDOException $exception) {
            Log::error('Portfolio projects table unavailable, using seed files.', ['exception' => $exception->getMessage()]);

            return $this->definitions = ProjectSeeder::definitions();
        }
    }

    /**
     * @param  array<string, mixed>  $definition
     * @return list<string>
     */
    private function errorsFor(string $slug, array $definition): array
    {
        $default = config('portfolio.default_locale');
        $errors = [];

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $errors[] = "The slug [{$slug}] must be lowercase letters, numbers and dashes.";
        }

        $validator = Validator::make($definition, [
            'name' => ['required', 'string', 'max:80'],
            'order' => ['required', 'integer'],
            'featured' => ['required', 'boolean'],
            'status' => ['required', Rule::in(ProjectStatus::values())],
            'kind' => ['nullable', Rule::in(ProjectKind::values())],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'url' => ['nullable', 'url:https,http'],
            'repository' => ['nullable', 'url:https'],
            'links' => ['present', 'array'],
            'links.*.url' => ['required', 'url:https,http,mailto'],
            'links.*.label' => ['required'],
            'accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['nullable', 'string'],
            'cover' => ['nullable', 'string'],
            'cover_small' => ['nullable', 'string'],
            'stack' => ['present', 'array'],
            'stack.*' => ['string'],
            'content' => ['required', 'array'],
            "content.{$default}" => ['required', 'array'],
            "content.{$default}.category" => ['required', 'string'],
            "content.{$default}.tagline" => ['required', 'string'],
            "content.{$default}.summary" => ['required', 'string'],
            "content.{$default}.problem" => ['required', 'string'],
            "content.{$default}.solution" => ['required', 'string'],
            "content.{$default}.features" => ['present', 'array'],
            "content.{$default}.audience" => ['nullable', 'string'],
            "content.{$default}.role" => ['nullable', 'string'],
            "content.{$default}.testimonial" => ['nullable', 'string'],
            "content.{$default}.testimonial_author" => ['nullable', 'string'],
        ]);

        foreach ($validator->errors()->all() as $message) {
            $errors[] = $message;
        }

        $unknownLocales = array_diff(array_keys($definition['content'] ?? []), array_keys(config('portfolio.locales')));

        foreach ($unknownLocales as $locale) {
            $errors[] = "The content locale [{$locale}] is not enabled in config/portfolio.php.";
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $definition
     */
    private function make(string $slug, array $definition, string $locale): LocalizedProject
    {
        $default = config('portfolio.default_locale');
        $translated = array_filter(
            $definition['content'][$locale] ?? [],
            fn ($value): bool => $value !== null && $value !== '' && $value !== [],
        );
        $content = array_merge($definition['content'][$default], $translated);

        return new LocalizedProject(
            slug: $slug,
            name: $definition['name'],
            order: (int) $definition['order'],
            featured: (bool) $definition['featured'],
            status: ProjectStatus::from($definition['status']),
            kind: ProjectKind::tryFrom($definition['kind'] ?? '') ?? ProjectKind::Product,
            year: isset($definition['year']) ? (int) $definition['year'] : null,
            url: $definition['url'] ?? null,
            repository: $definition['repository'] ?? null,
            links: array_values(array_map(fn (array $link): array => [
                'label' => is_array($link['label']) ? ($link['label'][$locale] ?? $link['label'][$default] ?? reset($link['label'])) : $link['label'],
                'url' => $link['url'],
            ], $definition['links'] ?? [])),
            accent: $definition['accent'],
            logoUrl: Project::mediaUrl($definition['logo'] ?? null),
            coverUrl: Project::mediaUrl($definition['cover'] ?? null),
            coverSmallUrl: Project::mediaUrl($definition['cover_small'] ?? null),
            stack: array_values(array_filter($definition['stack'] ?? [], 'is_string')),
            category: $content['category'],
            tagline: $content['tagline'],
            summary: $content['summary'],
            problem: $content['problem'],
            solution: $content['solution'],
            features: array_values(array_filter($content['features'] ?? [], fn ($feature): bool => is_string($feature) && $feature !== '')),
            audience: $content['audience'] ?? null,
            role: $content['role'] ?? null,
            testimonial: $content['testimonial'] ?? null,
            testimonialAuthor: $content['testimonial_author'] ?? null,
        );
    }
}
