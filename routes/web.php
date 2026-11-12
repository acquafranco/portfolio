<?php

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SeoController;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/*
| Public site: read-only, so it runs without sessions or cookies. Pages never
| touch the session store and can be cached by browsers and Cloudflare.
| The admin panel (/admin, Filament) keeps the full "web" stack.
*/
Route::withoutMiddleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    ShareErrorsFromSession::class,
    PreventRequestForgery::class,
])->group(function () {
    // "/" redirects to the visitor's preferred language (Accept-Language).
    Route::get('/', [PortfolioController::class, 'redirectToLocale'])->name('root');

    Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
    Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

    Route::prefix('{locale}')
        ->whereIn('locale', array_keys(config('portfolio.locales')))
        ->middleware(['setlocale', 'cache.headers:public;max_age=600;etag'])
        ->group(function () {
            Route::get('/', [PortfolioController::class, 'home'])->name('home');
            Route::get('/projects/{slug}', [PortfolioController::class, 'project'])
                ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
                ->name('projects.show');
        });
});
