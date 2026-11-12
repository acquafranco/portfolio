@php
    $home = route('home');
    $navItems = [
        'projects' => __('portfolio.nav.projects'),
        'about' => __('portfolio.nav.about'),
        'process' => __('portfolio.nav.process'),
        'experience' => __('portfolio.nav.experience'),
    ];
@endphp

<header x-data="siteNav" @keydown.escape.window="close()" class="sticky top-0 z-50 border-b border-line/70 bg-ink/80 backdrop-blur-xl supports-[backdrop-filter]:bg-ink/65">
    <div class="container-site flex h-16 items-center justify-between gap-6">
        <a href="{{ $home }}" class="group flex items-center gap-2.5 font-medium tracking-tight text-fg">
            <img src="{{ asset('favicon.svg') }}" alt="" width="28" height="28" class="h-7 w-7">
            <span class="whitespace-nowrap">{{ config('portfolio.name') }}</span>
        </a>

        <nav aria-label="{{ __('portfolio.nav.label') }}" class="hidden lg:block">
            <ul class="flex items-center gap-1 text-sm text-muted">
                @foreach ($navItems as $id => $label)
                    <li><a href="{{ $home }}#{{ $id }}" class="rounded-full px-3 py-2 transition-colors hover:text-fg">{{ $label }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <x-language-switcher class="hidden lg:flex" />
            <a href="{{ $home }}#contact" class="btn-primary hidden py-2 lg:inline-flex">{{ __('portfolio.nav.cta') }}</a>

            <button
                type="button"
                @click="toggle()"
                class="-mr-2 inline-flex h-10 w-10 items-center justify-center rounded-full text-fg transition-colors hover:bg-raised lg:hidden"
                :aria-expanded="open.toString()"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <span class="sr-only" x-text="open ? @js(__('portfolio.nav.close_menu')) : @js(__('portfolio.nav.open_menu'))">{{ __('portfolio.nav.open_menu') }}</span>
                <x-glyph name="menu" :size="22" x-show="!open" />
                <x-glyph name="close" :size="22" x-show="open" x-cloak />
            </button>
        </div>
    </div>

    <nav
        id="mobile-menu"
        aria-label="{{ __('portfolio.nav.label') }}"
        x-show="open"
        x-cloak
        x-transition.opacity.duration.150ms
        class="border-t border-line bg-ink lg:hidden"
    >
        <ul class="container-site flex flex-col py-3 text-base">
            @foreach ($navItems + ['contact' => __('portfolio.nav.contact')] as $id => $label)
                <li>
                    <a href="{{ $home }}#{{ $id }}" @click="close()" class="flex items-center justify-between border-b border-line/60 py-3.5 text-fg">
                        {{ $label }}
                        <x-glyph name="arrow-right" class="text-subtle" />
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="container-site pb-5 pt-1">
            <x-language-switcher class="flex" />
        </div>
    </nav>
</header>
