<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Imports the initial projects from database/seeders/projects/*.php.
 * Only creates missing slugs: edits made from /admin are never overwritten.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::definitions() as $slug => $definition) {
            if (Project::query()->where('slug', $slug)->exists()) {
                continue;
            }

            $attributes = Project::attributesFromDefinition($definition);

            foreach (Project::IMAGE_COLUMNS as $column) {
                $attributes[$column] = $this->copyToStorage($attributes[$column], $slug);
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
    private function copyToStorage(?string $path, string $slug): ?string
    {
        if ($path === null || Project::isUploaded($path) || ! is_file(public_path($path))) {
            return $path;
        }

        $target = 'projects/'.$slug.'-'.basename($path);

        return Storage::disk('public')->put($target, (string) file_get_contents(public_path($path))) ? $target : $path;
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
