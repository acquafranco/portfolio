<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Resuelve el idioma desde el segmento {locale} de la URL (/es, /pt, /en)
     * y lo aplica a la app. Si el segmento no es uno de los soportados,
     * devuelve 404 en vez de asumir un idioma por defecto silenciosamente.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        $supported = array_keys(config('portfolio.locales'));

        if (! in_array($locale, $supported, true)) {
            abort(404);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
