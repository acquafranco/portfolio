<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

// "/" redirige al idioma por defecto. Así "/" nunca es una URL "real" distinta
// de "/es", lo que evita contenido duplicado para SEO.
Route::get('/', function () {
    return redirect('/'.config('portfolio.default_locale'));
});

Route::prefix('{locale}')
    ->whereIn('locale', array_keys(config('portfolio.locales')))
    ->middleware('setlocale')
    ->group(function () {
        Route::get('/', [PortfolioController::class, 'home'])->name('home');
    });

// robots.txt y sitemap.xml servidos como archivos estáticos desde /public
// (ver public/robots.txt y public/sitemap.xml). No hace falta ruta dinámica
// para esto ya que el sitio no tiene contenido generado por base de datos.
