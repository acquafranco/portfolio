@php $links = config('portfolio.links'); @endphp

<footer class="border-t border-line">
    <div class="container-site flex flex-col gap-6 py-10 text-sm text-subtle sm:flex-row sm:items-center sm:justify-between">
        <p>&copy; {{ date('Y') }} · {{ __('portfolio.footer.text') }}</p>

        <div class="flex items-center gap-5">
            <a href="{{ $links['github'] }}" data-track="github_click" target="_blank" rel="noopener noreferrer" class="transition-colors hover:text-fg">
                <span class="sr-only">GitHub {{ __('portfolio.projects.opens_new_tab') }}</span>
                <x-glyph name="github" :size="18" />
            </a>
            <a href="mailto:{{ $links['email'] }}" data-track="email_click" class="transition-colors hover:text-fg">
                <span class="sr-only">{{ __('portfolio.contact.email') }}</span>
                <x-glyph name="mail" :size="18" />
            </a>
            <x-language-switcher class="inline-flex" />
        </div>
    </div>
</footer>
