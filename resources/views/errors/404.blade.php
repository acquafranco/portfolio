@php
    // La request pudo no pasar por el middleware setlocale (ej: /pt/algo-que-no-existe),
    // así que intentamos tomar el idioma del primer segmento de la URL antes de
    // caer al idioma por defecto.
    $segment = request()->segment(1);
    $supported = array_keys(config('portfolio.locales'));
    app()->setLocale(in_array($segment, $supported, true) ? $segment : config('portfolio.default_locale'));
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>{{ __('portfolio.not_found.heading') }} — {{ config('portfolio.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-graphite text-paper font-body min-h-screen flex items-center">
    <div class="max-w-md mx-auto px-6 text-center">
        <p class="font-display text-6xl font-semibold text-amber mb-4">404</p>
        <h1 class="font-display text-2xl font-semibold mb-3">{{ __('portfolio.not_found.heading') }}</h1>
        <p class="text-slate mb-8">{{ __('portfolio.not_found.text') }}</p>
        <a href="{{ url('/'.app()->getLocale()) }}" class="inline-flex items-center justify-center px-5 py-3 text-sm font-medium bg-amber text-graphite hover:bg-amber/90 transition-colors">
            {{ __('portfolio.not_found.cta') }}
        </a>
    </div>
</body>
</html>
