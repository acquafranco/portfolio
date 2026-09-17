<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portfolio.seo.title') }}</title>
    <meta name="description" content="{{ __('portfolio.seo.description') }}">

    {{-- Canonical + hreflang: misma página, tres idiomas --}}
    <link rel="canonical" href="{{ url()->current() }}">
    @foreach (config('portfolio.locales') as $code => $label)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ url('/'.$code) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ url('/'.config('portfolio.default_locale')) }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('portfolio.seo.site_name') }}">
    <meta property="og:title" content="{{ __('portfolio.seo.title') }}">
    <meta property="og:description" content="{{ __('portfolio.seo.description') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(config('portfolio.seo.og_image')) }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta name="twitter:card" content="summary_large_image">
    @if (config('portfolio.seo.twitter_handle'))
        <meta name="twitter:site" content="{{ config('portfolio.seo.twitter_handle') }}">
    @endif

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    {{-- Fuentes: Space Grotesk (titulares) + Inter (cuerpo) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-graphite text-paper font-body antialiased">

    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:bg-amber focus:text-graphite focus:px-4 focus:py-2 focus:z-50">
        {{ __('portfolio.nav.home') }}
    </a>

    <x-navbar />

    <main id="contenido">
        {{ $slot }}
    </main>

    <x-footer />

</body>
</html>
