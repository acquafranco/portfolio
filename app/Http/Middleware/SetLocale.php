<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the locale from the {locale} URL segment (/es, /pt, /en) and make
     * it the default parameter for route() so views don't have to pass it.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! is_string($locale) || ! array_key_exists($locale, config('portfolio.locales'))) {
            abort(404);
        }

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
