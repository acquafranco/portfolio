@php
    $navItems = [
        '#sobre-mi' => __('portfolio.nav.about'),
        '#que-hago' => __('portfolio.nav.work'),
        '#proyectos' => __('portfolio.nav.projects'),
        '#experiencia' => __('portfolio.nav.experience'),
        '#contacto' => __('portfolio.nav.contact'),
    ];
@endphp

<header x-data="{ open: false }" class="border-b border-steel">
    <div class="max-w-5xl mx-auto px-6 py-5 flex items-center justify-between">
        <a href="{{ url('/'.app()->getLocale()) }}" class="font-display font-semibold text-2xl md:text-3xl tracking-tight text-paper">
            {{ config('portfolio.name') }}
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm text-slate">
            @foreach ($navItems as $href => $label)
                <a href="{{ $href }}" class="hover:text-paper transition-colors">{{ $label }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-6">
            <x-language-switcher class="hidden md:flex" />

            <button
                type="button"
                @click="open = !open"
                class="md:hidden text-paper"
                :aria-expanded="open"
                aria-controls="mobile-menu"
                aria-label="{{ __('portfolio.nav.home') }}"
            >
                <svg x-show="!open" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="open" x-cloak width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </div>

    <nav
        id="mobile-menu"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        @click="open = false"
        class="md:hidden border-t border-steel px-6 py-4 flex flex-col gap-4 text-sm text-slate"
    >
        @foreach ($navItems as $href => $label)
            <a href="{{ $href }}" class="hover:text-paper transition-colors">{{ $label }}</a>
        @endforeach
        <x-language-switcher class="inline-flex pt-2 border-t border-steel" />
    </nav>
</header>
