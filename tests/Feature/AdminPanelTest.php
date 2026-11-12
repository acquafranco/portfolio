<?php

namespace Tests\Feature;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Models\Project;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    /**
     * @return array<string, mixed>
     */
    private function validProjectData(array $overrides = []): array
    {
        return array_replace_recursive([
            'name' => 'Nuevo Producto',
            'slug' => 'nuevo-producto',
            'status' => 'experimental',
            'is_published' => true,
            'is_featured' => true,
            'year' => 2026,
            'url' => 'https://nuevo.example.com',
            'accent' => '#7C8CFF',
            'stack' => ['Laravel', 'MySQL'],
            'content' => [
                'es' => [
                    'category' => 'SaaS · Pruebas',
                    'tagline' => 'Un producto nuevo cargado desde el panel.',
                    'summary' => 'Resumen del producto nuevo.',
                    'problem' => 'El problema que resuelve.',
                    'solution' => 'Cómo lo resuelve.',
                    // Simple repeater internal state: [key => ['feature' => text]].
                    'features' => ['a' => ['feature' => 'Primera funcionalidad'], 'b' => ['feature' => 'Segunda funcionalidad']],
                ],
            ],
        ], $overrides);
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Franco Acqua');
    }

    public function test_non_admin_users_cannot_enter_the_panel(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_admins_see_the_projects_list(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')->assertOk();
        $this->get('/admin/projects')->assertOk();

        Livewire::test(ListProjects::class)
            ->assertCanSeeTableRecords(Project::query()->orderBy('sort_order')->get(), inOrder: true);
    }

    public function test_admin_creates_a_project_with_a_screenshot_and_it_appears_on_the_site(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateProject::class)
            ->fillForm($this->validProjectData())
            // set() replaces the field state (fillForm merges with defaults).
            ->set('data.content.es.features', ['a' => ['feature' => 'Primera funcionalidad'], 'b' => ['feature' => 'Segunda funcionalidad']])
            ->fillForm(['cover_path' => UploadedFile::fake()->image('captura.png', 1440, 900)])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::query()->where('slug', 'nuevo-producto')->firstOrFail();

        // Stored as a plain list of strings.
        $this->assertSame(['Primera funcionalidad', 'Segunda funcionalidad'], $project->content['es']['features']);

        // The upload is converted to two WebP sizes and the original removed.
        $this->assertStringEndsWith('.opt.webp', $project->cover_path);
        $this->assertStringEndsWith('.opt.webp', $project->cover_small_path);
        Storage::disk('public')->assertExists([$project->cover_path, $project->cover_small_path]);
        $this->assertSame([], Storage::disk('public')->files('projects/uploads'));
        [$width, $height] = getimagesizefromstring(Storage::disk('public')->get($project->cover_path));
        $this->assertSame([1200, 630], [$width, $height]);

        $this->assertSame(40, $project->sort_order, 'New projects go after the existing ones.');

        $this->get('/es')->assertOk()->assertSee('Nuevo Producto')->assertSee('Un producto nuevo cargado desde el panel.');
        $this->get('/pt/projects/nuevo-producto')
            ->assertOk()
            ->assertSee('Segunda funcionalidad') // falls back to Spanish
            ->assertSee(asset('storage/'.$project->cover_path));
        $this->get('/sitemap.xml')->assertSee('/projects/nuevo-producto');
    }

    public function test_spanish_content_is_required_and_slugs_are_unique(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateProject::class)
            ->fillForm($this->validProjectData(['slug' => 'ascento', 'content' => ['es' => ['tagline' => '']]]))
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique', 'content.es.tagline' => 'required']);

        Livewire::test(CreateProject::class)
            ->fillForm($this->validProjectData(['slug' => 'Con Espacios', 'accent' => 'red']))
            ->call('create')
            ->assertHasFormErrors(['slug', 'accent']);

        $this->assertSame(3, Project::query()->count());
    }

    public function test_admin_edits_a_project_and_replacing_the_screenshot_deletes_the_old_files(): void
    {
        $this->actingAs($this->admin());
        $project = Project::query()->where('slug', 'gula')->firstOrFail();
        $oldFiles = [$project->cover_path, $project->cover_small_path];

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
            ->fillForm([
                'status' => 'production',
                'content.es.tagline' => 'Frase editada desde el panel.',
            ])
            ->set('data.cover_path', []) // drop the current file, then upload the new one
            ->fillForm(['cover_path' => UploadedFile::fake()->image('nueva.jpg', 1600, 1000)])
            ->call('save')
            ->assertHasNoFormErrors();

        $project->refresh();
        $this->assertSame('production', $project->status->value);
        Storage::disk('public')->assertMissing($oldFiles);
        Storage::disk('public')->assertExists($project->cover_path);

        $this->get('/es/projects/gula')->assertSee('Frase editada desde el panel.')->assertSee(__('portfolio.status.production', [], 'es'));
    }

    public function test_deleting_a_project_removes_it_and_its_files(): void
    {
        $this->actingAs($this->admin());
        $project = Project::query()->where('slug', 'domus')->firstOrFail();
        $files = array_values(array_filter($project->only(Project::IMAGE_COLUMNS)));

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
            ->callAction(DeleteAction::class);

        $this->assertModelMissing($project);
        Storage::disk('public')->assertMissing($files);
        $this->get('/es/projects/domus')->assertNotFound();
    }

    public function test_public_pages_still_set_no_cookies_while_the_panel_uses_sessions(): void
    {
        $this->assertEmpty($this->get('/es')->headers->getCookies());
        $this->assertNotEmpty($this->get('/admin/login')->headers->getCookies());
    }
}
