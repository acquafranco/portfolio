<?php

use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

// "/" redirects to the visitor's preferred language (Accept-Language), so
// "/" is never a separate indexable URL.
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
