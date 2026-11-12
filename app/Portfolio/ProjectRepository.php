<?php

namespace App\Portfolio;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Reads portfolio projects from config/projects/*.php (one file per project,
 * the file name is the slug) and resolves them for a locale.
 *
 * Projects that fail validation are skipped and logged at runtime so a typo
 * never takes the site down; `php artisan portfolio:projects` and the test
 * suite report them as errors.
 */
class ProjectRepository
{
    /** @var array<string, Collection<int, Project>> */
    private array $resolved = [];

    /**
     * All valid projects, ordered by `order` and then by name.
     *
     * @return Collection<int, Project>
     */
    public function all(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return $this->resolved[$locale] ??= collect($this->definitions())
            ->filter(function (array $definition, string $slug): bool {
                $errors = $this->errorsFor($slug, $definition);

                if ($errors !== []) {
                    Log::warning("Portfolio project [{$slug}] skipped: invalid definition.", ['errors' => $errors]);

                    return false;
                }

                return true;
            })
            ->map(fn (array $definition, string $slug): Project => $this->make($slug, $definition, $locale))
            ->sortBy([
                fn (Project $a, Project $b): int => $a->order <=> $b->order,
                fn (Project $a, Project $b): int => strcasecmp($a->name, $b->name),
            ])
            ->values();
    }

    public function find(string $slug, ?string $locale = null): ?Project
    {
        return $this->all($locale)->firstWhere('slug', $slug);
    }

    /**
     * The project shown after $project on its detail page (wraps around).
     */
    public function next(Project $project, ?string $locale = null): ?Project
    {
        $projects = $this->all($locale);

        if ($projects->count() < 2) {
            return null;
        }

        $index = $projects->search(fn (Project $candidate): bool => $candidate->slug === $project->slug);

        return $projects->get(($index + 1) % $projects->count());
    }

    /**
     * Validation errors for every project file, keyed by slug. Includes checks
     * that need the filesystem (images must exist in public/).
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

                if (is_string($path) && ! is_file(public_path($path))) {
                    $projectErrors[] = "The {$imageKey} file [public/{$path}] does not exist.";
                }
            }

            if ($projectErrors !== []) {
                $errors[$slug] = $projectErrors;
            }
        }

        return $errors;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function definitions(): array
    {
        $definitions = config('projects', []);

        return is_array($definitions) ? array_filter($definitions, 'is_array') : [];
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
            $errors[] = "The file name [{$slug}.php] must be a lowercase slug (letters, numbers and dashes).";
        }

        $validator = Validator::make($definition, [
            'name' => ['required', 'string', 'max:80'],
            'order' => ['required', 'integer'],
            'featured' => ['required', 'boolean'],
            'status' => ['required', Rule::in(ProjectStatus::values())],
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
            'stack' => ['required', 'array', 'min:1'],
            'stack.*' => ['string'],
            'content' => ['required', 'array'],
            "content.{$default}" => ['required', 'array'],
            "content.{$default}.category" => ['required', 'string'],
            "content.{$default}.tagline" => ['required', 'string'],
            "content.{$default}.summary" => ['required', 'string'],
            "content.{$default}.problem" => ['required', 'string'],
            "content.{$default}.solution" => ['required', 'string'],
            "content.{$default}.features" => ['required', 'array', 'min:1'],
            "content.{$default}.audience" => ['nullable', 'string'],
            "content.{$default}.role" => ['nullable', 'string'],
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
    private function make(string $slug, array $definition, string $locale): Project
    {
        $default = config('portfolio.default_locale');
        $fallback = $definition['content'][$default];
        $content = array_merge($fallback, array_filter($definition['content'][$locale] ?? [], fn ($value) => $value !== null && $value !== ''));

        return new Project(
            slug: $slug,
            name: $definition['name'],
            order: (int) $definition['order'],
            featured: (bool) $definition['featured'],
            status: ProjectStatus::from($definition['status']),
            year: isset($definition['year']) ? (int) $definition['year'] : null,
            url: $definition['url'] ?? null,
            repository: $definition['repository'] ?? null,
            links: array_values(array_map(fn (array $link): array => [
                'label' => is_array($link['label']) ? ($link['label'][$locale] ?? $link['label'][$default] ?? reset($link['label'])) : $link['label'],
                'url' => $link['url'],
            ], $definition['links'] ?? [])),
            accent: $definition['accent'],
            logo: $definition['logo'] ?? null,
            cover: $definition['cover'] ?? null,
            coverSmall: $definition['cover_small'] ?? null,
            stack: array_values($definition['stack']),
            category: $content['category'],
            tagline: $content['tagline'],
            summary: $content['summary'],
            problem: $content['problem'],
            solution: $content['solution'],
            features: array_values($content['features']),
            audience: $content['audience'] ?? null,
            role: $content['role'] ?? null,
        );
    }
}
