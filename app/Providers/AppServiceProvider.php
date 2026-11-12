<?php

namespace App\Providers;

use App\Portfolio\ProjectRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(ProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // SetLocale overrides this per request; error pages rendered outside
        // a localized route still get a working route('home').
        URL::defaults(['locale' => config('portfolio.default_locale')]);

        // Canonical, hreflang, Open Graph and sitemap URLs must be absolute
        // https URLs even when TLS terminates at a proxy (Cloudflare).
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
