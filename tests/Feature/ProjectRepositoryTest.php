<?php

namespace Tests\Feature;

use App\Portfolio\ProjectRepository;
use App\Portfolio\ProjectStatus;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProjectRepositoryTest extends TestCase
{
    public function test_every_project_file_is_valid(): void
    {
        $this->assertSame([], app(ProjectRepository::class)->validationErrors());
    }

    public function test_every_project_has_content_in_every_locale(): void
    {
        foreach (app(ProjectRepository::class)->definitions() as $slug => $definition) {
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

        config(['projects.domus.order' => 1]);

        $this->assertSame('domus', (new ProjectRepository)->all('es')->first()->slug);
    }

    public function test_missing_translations_fall_back_to_the_default_locale(): void
    {
        config(['projects.domus.content.en' => ['tagline' => 'Only the tagline is translated.']]);

        $project = (new ProjectRepository)->find('domus', 'en');

        $this->assertSame('Only the tagline is translated.', $project->tagline);
        $this->assertSame(config('projects.domus.content.es.summary'), $project->summary);
    }

    public function test_invalid_projects_are_skipped_and_logged_instead_of_breaking_the_site(): void
    {
        Log::spy();

        config(['projects.broken' => ['name' => 'Broken', 'status' => 'shipped']]);

        $repository = new ProjectRepository;

        $this->assertNull($repository->find('broken', 'es'));
        $this->assertCount(3, $repository->all('es'));
        $this->assertArrayHasKey('broken', $repository->validationErrors());
        Log::shouldHaveReceived('warning')->withArgs(fn (string $message): bool => str_contains($message, '[broken]'));

        $this->get('/es')->assertOk();
    }

    public function test_missing_images_are_reported(): void
    {
        config(['projects.gula.cover' => 'images/projects/gula/missing.webp']);

        $errors = (new ProjectRepository)->validationErrors();

        $this->assertStringContainsString('missing.webp', $errors['gula'][0]);
    }

    public function test_project_links_resolve_their_label_per_locale(): void
    {
        config(['projects.ascento.links' => [
            ['label' => ['es' => 'Tutoriales', 'en' => 'Tutorials'], 'url' => 'https://ascento.online/tutoriales'],
        ]]);

        $repository = new ProjectRepository;

        $this->assertSame('Tutorials', $repository->find('ascento', 'en')->links[0]['label']);
        $this->assertSame('Tutoriales', $repository->find('ascento', 'pt')->links[0]['label']);
        $this->get('/en/projects/ascento')->assertSee('Tutorials');
    }

    public function test_status_filters_only_show_statuses_in_use(): void
    {
        config(['projects.gula.status' => ProjectStatus::Experimental->value]);

        $this->get('/es')
            ->assertOk()
            ->assertSee(ProjectStatus::Experimental->label())
            ->assertDontSee('>'.__('portfolio.status.development', [], 'es').' <', false);
    }

    public function test_non_featured_projects_render_as_compact_cards(): void
    {
        config(['projects.gula.featured' => false]);

        $this->get('/es')->assertOk()->assertSee('GULA');
    }

    public function test_next_project_wraps_around(): void
    {
        $repository = app(ProjectRepository::class);

        $this->assertSame('gula', $repository->next($repository->find('ascento', 'es'), 'es')->slug);
        $this->assertSame('ascento', $repository->next($repository->find('domus', 'es'), 'es')->slug);
    }
}
