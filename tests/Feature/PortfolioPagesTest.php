<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Portfolio\ProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PortfolioPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function locales(): array
    {
        return ['es' => ['es'], 'pt' => ['pt'], 'en' => ['en']];
    }

    public function test_root_redirects_to_the_default_locale_without_accept_language(): void
    {
        // Symfony's test client sends "Accept-Language: en-us" by default.
        $this->get('/', ['Accept-Language' => ''])
            ->assertRedirect('/es')
            ->assertHeader('Vary', 'Accept-Language');
    }

    public function test_root_redirects_to_the_browser_language_when_supported(): void
    {
        $this->get('/', ['Accept-Language' => 'pt-BR,pt;q=0.9,en;q=0.8'])->assertRedirect('/pt');
        $this->get('/', ['Accept-Language' => 'en-US,en;q=0.9'])->assertRedirect('/en');
        $this->get('/', ['Accept-Language' => 'fr-FR,fr;q=0.9'])->assertRedirect('/es');
    }

    #[DataProvider('locales')]
    public function test_home_renders_every_project_in_each_locale(string $locale): void
    {
        $response = $this->get("/{$locale}")
            ->assertOk()
            ->assertSee('<html lang="'.$locale.'">', false)
            ->assertSee(__('portfolio.hero.headline', [], $locale));

        foreach (app(ProjectRepository::class)->all($locale) as $project) {
            $response
                ->assertSee($project->name)
                ->assertSee($project->tagline)
                ->assertSee(route('projects.show', ['locale' => $locale, 'slug' => $project->slug]), false);

            if ($project->url) {
                $response->assertSee('href="'.$project->url.'"', false);
            }
        }
    }

    public function test_home_lists_ascento_gula_and_domus(): void
    {
        $this->get('/es')
            ->assertOk()
            ->assertSeeInOrder(['Ascento', 'GULA', 'DOMUS'])
            ->assertSee('https://ascento.online')
            ->assertSee('https://gula.on-forge.com')
            ->assertSee('https://domus.on-forge.com');
    }

    #[DataProvider('locales')]
    public function test_each_project_page_renders_in_each_locale(string $locale): void
    {
        foreach (app(ProjectRepository::class)->all($locale) as $project) {
            $response = $this->get("/{$locale}/projects/{$project->slug}")
                ->assertOk()
                ->assertSee($project->name)
                ->assertSee($project->problem)
                ->assertSee($project->solution)
                ->assertSee('"@type":"SoftwareApplication"', false);

            foreach ($project->features as $feature) {
                $response->assertSee($feature);
            }
        }
    }

    public function test_domus_does_not_promise_features_it_does_not_implement(): void
    {
        foreach (['es' => 'expensa', 'pt' => 'cobran', 'en' => 'dues'] as $locale => $word) {
            $this->get("/{$locale}/projects/domus")->assertOk()->assertDontSee($word);
        }
    }

    public function test_unknown_project_and_unknown_locale_return_404(): void
    {
        $this->get('/es/projects/does-not-exist')
            ->assertNotFound()
            ->assertSee(__('portfolio.not_found.heading', [], 'es'))
            ->assertSee('noindex', false);

        $this->get('/pt/projects/does-not-exist')
            ->assertNotFound()
            ->assertSee(__('portfolio.not_found.heading', [], 'pt'));

        $this->get('/fr')->assertNotFound();
        $this->get('/es/projects/Bad_Slug')->assertNotFound();
    }

    public function test_pages_are_stateless_and_cacheable(): void
    {
        $response = $this->get('/es')->assertOk();

        $this->assertEmpty($response->headers->getCookies(), 'Public pages must not set cookies.');
        $this->assertStringContainsString('public', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('max-age=600', (string) $response->headers->get('Cache-Control'));
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/es')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_seo_metadata_and_alternates(): void
    {
        $response = $this->get('/pt/projects/ascento')->assertOk();

        $response
            ->assertSee('<link rel="canonical" href="'.url('/pt/projects/ascento').'">', false)
            ->assertSee('hreflang="es" href="'.url('/es/projects/ascento').'"', false)
            ->assertSee('hreflang="en" href="'.url('/en/projects/ascento').'"', false)
            ->assertSee('hreflang="x-default" href="'.url('/es/projects/ascento').'"', false)
            ->assertSee('<meta property="og:locale" content="pt_BR">', false)
            ->assertSee('<meta property="og:image" content="'.Project::mediaUrl(Project::query()->where('slug', 'ascento')->value('cover_path')).'">', false);

        $this->get('/es')->assertSee('<meta property="og:image" content="'.asset('images/og-cover.png').'">', false);
        $this->assertFileExists(public_path('images/og-cover.png'));
    }

    public function test_json_ld_is_valid_json(): void
    {
        $html = $this->get('/en')->assertOk()->getContent();

        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $matches);

        $this->assertNotEmpty($matches, 'JSON-LD block missing.');
        $data = json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame('Person', $data['@graph'][0]['@type']);
        $this->assertContains('https://github.com/acquafranco', $data['@graph'][0]['sameAs']);
    }

    public function test_language_switcher_links_to_the_same_page_in_other_languages(): void
    {
        $this->get('/es/projects/gula')
            ->assertSee('href="'.url('/pt/projects/gula').'"', false)
            ->assertSee('href="'.url('/en/projects/gula').'"', false);
    }

    public function test_cv_links_point_to_existing_files(): void
    {
        $this->get('/es')->assertSee(asset('cv/franco-acqua-cv-es.pdf'));
        $this->get('/pt')->assertSee(asset('cv/franco-acqua-cv-pt.pdf'));
        // No English CV yet: falls back to the default language instead of a broken link.
        $this->get('/en')->assertSee(asset('cv/franco-acqua-cv-es.pdf'))->assertDontSee('cv-en.pdf');

        foreach (config('portfolio.cv') as $path) {
            $this->assertFileExists(public_path($path));
        }
    }

    public function test_contact_links_use_the_real_accounts(): void
    {
        $this->get('/es')
            ->assertSee('https://github.com/acquafranco')
            ->assertSee('mailto:'.config('portfolio.links.email'), false)
            ->assertSee(config('portfolio.links.whatsapp').'?text='.rawurlencode(__('portfolio.contact.whatsapp_message', [], 'es')), false)
            ->assertDontSee('github.com/francoacqua');
    }

    public function test_sitemap_lists_every_page_in_every_locale(): void
    {
        $response = $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'sitemap.xml is not valid XML.');

        $projects = app(ProjectRepository::class)->all();
        $this->assertCount((1 + $projects->count()) * 3, $xml->url);

        foreach (['es', 'pt', 'en'] as $locale) {
            $response->assertSee('<loc>'.url("/{$locale}").'</loc>', false);
            foreach ($projects as $project) {
                $response->assertSee('<loc>'.url("/{$locale}/projects/{$project->slug}").'</loc>', false);
            }
        }
    }

    public function test_robots_txt_points_to_the_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Allow: /')
            ->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    #[DataProvider('locales')]
    public function test_whatsapp_link_has_a_prefilled_message_per_locale(string $locale): void
    {
        $this->get("/{$locale}")
            ->assertSee('wa.me/', false)
            ->assertSee('?text='.rawurlencode(__('portfolio.contact.whatsapp_message', [], $locale)), false);
    }

    public function test_hero_terminal_lists_every_project_with_links(): void
    {
        $response = $this->get('/en')->assertOk()->assertSee('data-terminal', false)->assertSee('php artisan deploy --all');

        foreach (app(ProjectRepository::class)->all('en') as $project) {
            $response->assertSee('<span style="color: '.$project->accent.'">'.$project->slug.'/</span>', false);
        }

        $response->assertSee('3 products live.');
    }

    public function test_unpublished_projects_are_hidden_everywhere(): void
    {
        Project::query()->where('slug', 'gula')->update(['is_published' => false]);

        $this->get('/es')->assertOk()->assertDontSee('gula.on-forge.com');
        $this->get('/es/projects/gula')->assertNotFound();
        $this->get('/sitemap.xml')->assertDontSee('/projects/gula');
    }

    public function test_public_site_survives_without_the_projects_table(): void
    {
        Schema::drop('projects');

        $this->get('/es')->assertOk()->assertSee('Ascento')->assertSee('DOMUS');
        $this->get('/es/projects/domus')->assertOk();
    }

    public function test_health_check_responds(): void
    {
        $this->get('/up')->assertOk();
    }
}
