@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'jsonLd' => null,
    'noindex' => false,
])

@use('App\Portfolio\Localization')

@php
    $pageTitle = $title ? $title.' — '.config('portfolio.name') : __('portfolio.seo.title');
    $pageDescription = $description ?? __('portfolio.seo.description');
    $ogImage = asset($image ?? config('portfolio.seo.og_image'));
    $localized = Localization::onLocalizedRoute();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="author" content="{{ config('portfolio.name') }}">
    <meta name="theme-color" content="#090a0c">
    @if ($noindex)
        <meta name="robots" content="noindex">
    @endif

    @if ($localized)
        <link rel="canonical" href="{{ url()->current() }}">
        @foreach (Localization::locales() as $code => $locale)
            <link rel="alternate" hreflang="{{ $code }}" href="{{ Localization::urlFor($code) }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ Localization::urlFor(config('portfolio.default_locale')) }}">
    @endif

    <meta property="og:type" content="{{ $type }}">
    <meta property="og:site_name" content="{{ config('portfolio.seo.site_name') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ Localization::ogLocale() }}">
    @foreach (Localization::locales() as $code => $locale)
        @continue($code === app()->getLocale())
        <meta property="og:locale:alternate" content="{{ $locale['og'] }}">
    @endforeach
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @if (config('portfolio.seo.twitter_handle'))
        <meta name="twitter:site" content="{{ config('portfolio.seo.twitter_handle') }}">
    @endif

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Geist+Mono:wght@400;500&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    @endif
</head>
<body class="min-h-screen">
    <a href="#content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-fg focus:px-4 focus:py-2 focus:text-sm focus:text-ink">
        {{ __('portfolio.nav.skip') }}
    </a>

    <x-navbar />

    <main id="content" tabindex="-1" class="outline-none">
        {{ $slot }}
    </main>

    <x-footer />
</body>
</html>
