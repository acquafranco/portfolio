<?php

namespace Tests\Feature;

use App\Portfolio\ProjectRepository;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PortfolioCommandsTest extends TestCase
{
    private const SLUG = 'zz-test-project';

    protected function tearDown(): void
    {
        File::delete(config_path('projects/'.self::SLUG.'.php'));
        File::deleteDirectory(public_path('images/projects/'.self::SLUG));

        parent::tearDown();
    }

    public function test_projects_command_lists_and_validates_projects(): void
    {
        $this->artisan('portfolio:projects')
            ->expectsOutputToContain('ascento')
            ->expectsOutputToContain('domus')
            ->expectsOutputToContain('All project files are valid.')
            ->assertSuccessful();
    }

    public function test_projects_command_fails_when_a_project_is_invalid(): void
    {
        config(['projects.broken' => ['name' => 'Broken']]);

        $this->artisan('portfolio:projects')
            ->expectsOutputToContain('config/projects/broken.php')
            ->assertFailed();
    }

    public function test_make_project_creates_a_valid_project_file(): void
    {
        $this->artisan('portfolio:make-project', [
            'slug' => self::SLUG,
            '--name' => 'Test Project',
            '--status' => 'experimental',
            '--url' => 'https://example.com',
        ])->assertSuccessful();

        $path = config_path('projects/'.self::SLUG.'.php');
        $this->assertFileExists($path);
        $this->assertDirectoryExists(public_path('images/projects/'.self::SLUG));

        $definition = require $path;
        config(['projects.'.self::SLUG => $definition]);

        $repository = new ProjectRepository;
        $this->assertArrayNotHasKey(self::SLUG, $repository->validationErrors());

        $project = $repository->find(self::SLUG, 'pt');
        $this->assertSame('Test Project', $project->name);
        $this->assertSame('experimental', $project->status->value);
        $this->assertSame(40, $project->order);
        $this->assertFalse($project->featured);
        $this->assertSame('example.com', $project->domain());

        $this->get('/es/projects/'.self::SLUG)->assertOk()->assertSee('Test Project');
    }

    public function test_make_project_rejects_invalid_input_and_existing_files(): void
    {
        $this->artisan('portfolio:make-project', ['slug' => 'Bad Slug'])->assertFailed();
        $this->artisan('portfolio:make-project', ['slug' => self::SLUG, '--status' => 'shipped'])->assertFailed();
        $this->artisan('portfolio:make-project', ['slug' => self::SLUG, '--url' => 'not a url'])->assertFailed();
        $this->artisan('portfolio:make-project', ['slug' => 'ascento'])->assertFailed();

        $this->assertFileDoesNotExist(config_path('projects/'.self::SLUG.'.php'));
    }
}
