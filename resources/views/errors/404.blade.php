@php
    // Error pages are rendered outside the localized route group: take the
    // language from the first URL segment (/pt/...) or use the default one.
    \App\Portfolio\Localization::detectFromPath();
@endphp

<x-layout :title="__('portfolio.not_found.title')" noindex>
    <section class="relative isolate overflow-hidden">
        <div class="grid-backdrop absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="container-site flex min-h-[70vh] flex-col items-start justify-center py-24">
            <p class="font-mono text-sm text-amber">404</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tightest text-fg md:text-6xl">{{ __('portfolio.not_found.heading') }}</h1>
            <p class="mt-4 max-w-md text-lg text-muted">{{ __('portfolio.not_found.text') }}</p>
            <a href="{{ route('home') }}" class="btn-primary mt-10">
                <x-glyph name="arrow-left" />
                {{ __('portfolio.not_found.cta') }}
            </a>
        </div>
    </section>
</x-layout>
