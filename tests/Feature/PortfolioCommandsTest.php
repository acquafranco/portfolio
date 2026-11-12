<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
}
