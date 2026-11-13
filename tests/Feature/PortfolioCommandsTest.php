<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class PortfolioCommandsTest extends TestCase
{
    use RefreshDatabase;

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
        Project::query()->where('slug', 'gula')->update(['cover_path' => 'projects/missing.webp']);

        $this->artisan('portfolio:projects')->expectsOutputToContain('gula')->assertFailed();
    }

    public function test_admin_command_creates_an_administrator(): void
    {
        $this->artisan('portfolio:admin', ['email' => 'Admin@Example.com', '--password' => 'a-strong-pass-123'])
            ->assertSuccessful();

        $user = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('a-strong-pass-123', $user->password));
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_admin_command_resets_the_password_of_an_existing_user(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.com', 'is_admin' => false]);

        $this->artisan('portfolio:admin', ['email' => 'admin@example.com', '--password' => 'another-pass-456'])
            ->expectsOutputToContain('Password updated')
            ->assertSuccessful();

        $user->refresh();
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('another-pass-456', $user->password));
        $this->assertSame(1, User::query()->count());
    }

    public function test_admin_command_rejects_weak_passwords_and_invalid_emails(): void
    {
        $this->artisan('portfolio:admin', ['email' => 'admin@example.com', '--password' => 'short'])->assertFailed();
        $this->artisan('portfolio:admin', ['email' => 'not-an-email', '--password' => 'long-enough-123'])->assertFailed();
        $this->artisan('portfolio:admin', ['email' => 'admin@example.com', '--no-interaction' => true])->assertFailed();

        $this->assertSame(0, User::query()->count());
    }

    public function test_cv_command_renders_every_language_with_the_new_career_story(): void
    {
        $this->artisan('portfolio:cv', ['--html' => true])->assertSuccessful();

        foreach (['es' => 'más de 10 años programando', 'pt' => 'mais de 10 anos programando', 'en' => 'more than 10 years of programming'] as $locale => $years) {
            $html = File::get(storage_path("app/cv/cv-{$locale}.html"));

            $this->assertStringContainsString($years, $html);
            $this->assertStringContainsString('<svg', $html, 'QR codes are embedded.');
            $this->assertStringContainsString('acquafranco.on-forge.com', $html);
            $this->assertStringNotContainsStringIgnoringCase('linkedin', $html);
            $this->assertStringNotContainsStringIgnoringCase('Armor', $html);
        }
    }

    public function test_cv_command_prints_each_pdf_with_chrome(): void
    {
        config(['portfolio.cv_chrome_path' => PHP_BINARY]);
        Process::fake();

        // Fake Chrome does not write files: the command reports it instead of claiming success.
        $this->artisan('portfolio:cv', ['--locale' => ['en']])->assertFailed();

        Process::assertRan(fn ($process): bool => in_array('--print-to-pdf='.storage_path('app/cv/cv-en.pdf'), $process->command, true));
        $this->assertFileExists(public_path('cv/franco-acqua-cv-en.pdf'), 'The published CV is kept when printing fails.');
    }

    public function test_cv_command_rejects_languages_without_a_cv(): void
    {
        $this->artisan('portfolio:cv', ['--locale' => ['fr'], '--html' => true])->assertFailed();
    }
}
