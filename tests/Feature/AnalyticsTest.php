<?php

namespace Tests\Feature;

use App\Filament\Pages\Analytics;
use App\Filament\Widgets\AnalyticsBreakdown;
use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\AnalyticsTrafficChart;
use App\Models\AnalyticsEvent;
use App\Models\User;
use App\Portfolio\AnalyticsReport;
use App\Portfolio\TrackedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER = 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1';

    /**
     * @param  array<string, mixed>  $payload
     */
    private function beacon(array $payload, string $userAgent = self::BROWSER, array $headers = []): TestResponse
    {
        return $this->withHeaders(['User-Agent' => $userAgent, ...$headers])->postJson('/a/e', $payload);
    }

    public function test_a_page_view_is_stored_without_ip_or_cookies(): void
    {
        $response = $this->beacon(
            ['n' => 'pageview', 'p' => '/pt/projects/ascento?utm_source=x', 'r' => 'https://www.google.com/search?q=franco', 'w' => 390],
            headers: ['CF-IPCountry' => 'br'],
        );

        $response->assertNoContent()->assertHeader('Cache-Control', 'no-store, private');
        $this->assertEmpty($response->headers->getCookies());

        $event = AnalyticsEvent::query()->sole();
        $this->assertSame(TrackedEvent::PageView, $event->name);
        $this->assertSame('/pt/projects/ascento', $event->path);
        $this->assertSame('pt', $event->locale);
        $this->assertSame('google.com', $event->referrer_host);
        $this->assertSame('BR', $event->country);
        $this->assertSame('mobile', $event->device);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{16}$/', $event->visitor_hash);
        $this->assertStringNotContainsString('127.0.0.1', json_encode($event->getAttributes()));
    }

    public function test_internal_navigation_is_not_counted_as_a_referrer(): void
    {
        $this->beacon(['n' => 'pageview', 'p' => '/es', 'r' => url('/en'), 'w' => 1440]);

        $event = AnalyticsEvent::query()->sole();
        $this->assertNull($event->referrer_host);
        $this->assertSame('desktop', $event->device);
    }

    public function test_the_same_visitor_gets_the_same_hash_during_the_day_only(): void
    {
        $this->beacon(['n' => 'pageview', 'p' => '/es']);
        $this->beacon(['n' => 'cv_download', 'p' => '/es']);
        $this->travel(1)->days();
        $this->beacon(['n' => 'pageview', 'p' => '/es']);

        [$first, $second, $nextDay] = AnalyticsEvent::query()->orderBy('id')->pluck('visitor_hash')->all();
        $this->assertSame($first, $second);
        $this->assertNotSame($first, $nextDay);
    }

    public function test_unknown_events_and_bots_are_ignored(): void
    {
        $this->beacon(['n' => 'hack', 'p' => '/es'])->assertStatus(422);
        $this->beacon(['n' => 'pageview', 'p' => 'https://evil.example'])->assertStatus(422);
        $this->beacon(['n' => 'pageview', 'p' => '/es'], 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)')->assertNoContent();
        $this->beacon(['n' => 'pageview', 'p' => '/es'], '')->assertNoContent();

        $this->assertSame(0, AnalyticsEvent::query()->count());
    }

    public function test_public_pages_mark_the_links_that_are_tracked(): void
    {
        $this->get('/es')
            ->assertSee('data-track="cv_download"', false)
            ->assertSee('data-track="whatsapp_click"', false)
            ->assertSee('data-track="email_copy"', false)
            ->assertSee('data-track="project_link"', false);
    }

    public function test_the_report_compares_with_the_previous_period(): void
    {
        $this->travelTo(now()->setTime(12, 0));

        AnalyticsEvent::factory()->count(3)->byVisitor('aaaaaaaaaaaaaaaa')->create();
        AnalyticsEvent::factory()->byVisitor('bbbbbbbbbbbbbbbb')->create(['referrer_host' => 'google.com', 'path' => '/es/projects/ascento']);
        AnalyticsEvent::factory()->event(TrackedEvent::WhatsappClick)->byVisitor('aaaaaaaaaaaaaaaa')->create();
        AnalyticsEvent::factory()->event(TrackedEvent::EmailCopy)->byVisitor('aaaaaaaaaaaaaaaa')->create();
        AnalyticsEvent::factory()->event(TrackedEvent::CvDownload)->byVisitor('bbbbbbbbbbbbbbbb')->create();
        // Previous 7-day period and outside both periods.
        AnalyticsEvent::factory()->create(['created_at' => now()->subDays(10)]);
        AnalyticsEvent::factory()->create(['created_at' => now()->subDays(40)]);

        $report = new AnalyticsReport(7);
        $summary = $report->summary();

        $this->assertSame(4, $summary['pageviews']);
        $this->assertSame(2, $summary['visitors']);
        $this->assertSame(1, $summary['contacts'], 'One visitor clicked WhatsApp and copied the email.');
        $this->assertSame(1, $summary['cv_downloads']);
        $this->assertSame(50.0, $summary['contact_rate']);
        $this->assertSame(['pageviews' => 1, 'visitors' => 1, 'contacts' => 0, 'cv_downloads' => 0], $summary['previous']);

        $daily = $report->daily();
        $this->assertCount(7, $daily);
        $this->assertSame(['date' => now()->toDateString(), 'pageviews' => 4, 'visitors' => 2], end($daily));

        $this->assertSame(['label' => '/es', 'count' => 3], $report->topPages()[0]);
        $this->assertContains(['label' => 'google.com', 'count' => 1], $report->topReferrers());
        $this->assertContains(['label' => 'Directo / sin origen', 'count' => 3], $report->topReferrers());
        $this->assertContains(['label' => TrackedEvent::WhatsappClick->label(), 'count' => 1], $report->clicks());
    }

    public function test_old_events_are_pruned(): void
    {
        $old = AnalyticsEvent::factory()->create(['created_at' => now()->subMonths(AnalyticsEvent::RETENTION_MONTHS)->subDay()]);
        $recent = AnalyticsEvent::factory()->create();

        $this->artisan('model:prune', ['--model' => [AnalyticsEvent::class]])->assertSuccessful();

        $this->assertModelMissing($old);
        $this->assertModelExists($recent);
    }

    public function test_admins_see_the_analytics_page(): void
    {
        AnalyticsEvent::factory()->count(2)->create();
        AnalyticsEvent::factory()->event(TrackedEvent::CvDownload)->create();

        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin/analytics')->assertOk()->assertSee('Analíticas');

        Livewire::test(AnalyticsOverview::class, ['pageFilters' => ['days' => 7]])
            ->assertSee('Visitantes')
            ->assertSee('Descargas del CV');
        Livewire::test(AnalyticsTrafficChart::class, ['pageFilters' => ['days' => 30]])->assertOk();
        Livewire::test(AnalyticsBreakdown::class, ['pageFilters' => ['days' => 90]])
            ->assertSee('Páginas más vistas')
            ->assertSee('/es');
    }

    public function test_the_period_filter_only_accepts_offered_values(): void
    {
        $this->assertSame(7, Analytics::periodFrom(['days' => '7']));
        $this->assertSame(Analytics::DEFAULT_PERIOD, Analytics::periodFrom(['days' => 9999]));
        $this->assertSame(Analytics::DEFAULT_PERIOD, Analytics::periodFrom(null));
    }

    public function test_guests_cannot_see_the_analytics_page(): void
    {
        $this->get('/admin/analytics')->assertRedirect('/admin/login');
    }

    public function test_the_panel_stops_the_owner_from_being_tracked(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin')->assertSee('fa:no-track', false);
    }
}
