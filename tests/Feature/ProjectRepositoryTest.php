<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Portfolio\ProjectRepository;
use App\Portfolio\ProjectStatus;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_migration_imports_the_initial_projects_with_their_images(): void
    {
        $this->assertSame(['ascento', 'gula', 'domus'], Project::query()->orderBy('sort_order')->pluck('slug')->all());

        foreach (Project::all() as $project) {
            foreach (Project::IMAGE_COLUMNS as $column) {
                $this->assertTrue(Project::isUploaded($project->{$column}), "{$project->slug}.{$column} should live on the public disk.");
                Storage::disk('public')->assertExists($project->{$column});
            }
        }

        $this->assertSame([], app(ProjectRepository::class)->validationErrors());
    }

    public function test_the_seeder_never_overwrites_admin_edits(): void
    {
        Project::query()->where('slug', 'ascento')->update(['name' => 'Ascento (editado)']);

        (new ProjectSeeder)->run();

        $this->assertSame('Ascento (editado)', Project::query()->where('slug', 'ascento')->value('name'));
        $this->assertSame(3, Project::query()->count());
    }

    public function test_every_seed_file_has_content_in_every_locale(): void
    {
        foreach (ProjectSeeder::definitions() as $slug => $definition) {
            foreach (array_keys(config('portfolio.locales')) as $locale) {
                $this->assertArrayHasKey($locale, $definition['content'], "{$slug} has no {$locale} content.");
                $this->assertCount(
                    count($definition['content'][config('portfolio.default_locale')]['features']),
                    $definition['content'][$locale]['features'],
                    "{$slug}: the {$locale} feature list differs in length from the default locale."
                );
            }
        }
    }

    public function test_projects_are_sorted_by_order(): void
    {
        $this->assertSame(['ascento', 'gula', 'domus'], app(ProjectRepository::class)->all('es')->pluck('slug')->all());

        Project::query()->where('slug', 'domus')->update(['sort_order' => 1]);

        $this->assertSame('domus', (new ProjectRepository)->all('es')->first()->slug);
    }

    public function test_missing_translations_fall_back_to_the_default_locale(): void
    {
        $project = Project::query()->where('slug', 'domus')->first();
        $content = $project->content;
        $content['en'] = ['tagline' => 'Only the tagline is translated.', 'features' => [], 'summary' => ''];
        $project->update(['content' => $content]);

        $resolved = (new ProjectRepository)->find('domus', 'en');

        $this->assertSame('Only the tagline is translated.', $resolved->tagline);
        $this->assertSame($content['es']['summary'], $resolved->summary);
        $this->assertSame($content['es']['features'], $resolved->features);
    }

    public function test_invalid_projects_are_skipped_and_logged_instead_of_breaking_the_site(): void
    {
        Log::spy();

        Project::withoutEvents(fn () => Project::query()->create([
            'slug' => 'broken', 'name' => 'Broken', 'status' => 'production',
            'accent' => 'red', 'stack' => [], 'content' => ['es' => ['tagline' => 'x']],
        ]));

        $repository = new ProjectRepository;

        $this->assertNull($repository->find('broken', 'es'));
        $this->assertCount(3, $repository->all('es'));
        $this->assertArrayHasKey('broken', $repository->validationErrors());
        Log::shouldHaveReceived('warning')->withArgs(fn (string $message): bool => str_contains($message, '[broken]'));

        $this->get('/es')->assertOk();
    }

    public function test_missing_images_are_reported(): void
    {
        Project::query()->where('slug', 'gula')->update(['cover_path' => 'projects/missing.webp']);

        $this->assertStringContainsString('missing.webp', (new ProjectRepository)->validationErrors()['gula'][0]);
    }

    public function test_project_links_are_shown_on_the_project_page(): void
    {
        Project::query()->where('slug', 'ascento')->first()->update(['links' => [
            ['label' => 'Tutoriales', 'url' => 'https://ascento.online/tutoriales'],
        ]]);

        $this->get('/en/projects/ascento')->assertSee('Tutoriales')->assertSee('https://ascento.online/tutoriales');
    }

    public function test_status_filters_only_show_statuses_in_use(): void
    {
        Project::query()->where('slug', 'gula')->update(['status' => ProjectStatus::Experimental->value]);

        $this->get('/es')
            ->assertOk()
            ->assertSee(ProjectStatus::Experimental->label())
            ->assertDontSee(__('portfolio.status.development', [], 'es'));
    }

    public function test_non_featured_projects_render_as_compact_cards(): void
    {
        Project::query()->where('slug', 'gula')->update(['is_featured' => false]);

        $this->get('/es')->assertOk()->assertSee('GULA');
    }

    public function test_next_project_wraps_around(): void
    {
        $repository = app(ProjectRepository::class);

        $this->assertSame('gula', $repository->next($repository->find('ascento', 'es'), 'es')->slug);
        $this->assertSame('ascento', $repository->next($repository->find('domus', 'es'), 'es')->slug);
    }
}
