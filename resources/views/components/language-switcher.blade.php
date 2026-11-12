@use('App\Portfolio\Localization')

<div {{ $attributes->merge(['class' => 'items-center gap-0.5 rounded-full border border-line bg-surface/60 p-0.5 text-xs font-medium']) }} role="group" aria-label="{{ __('portfolio.nav.language') }}">
    @foreach (Localization::locales() as $code => $locale)
        @php $active = Localization::current() === $code; @endphp
        <a
            href="{{ Localization::urlFor($code) }}"
            hreflang="{{ $code }}"
            lang="{{ $code }}"
            title="{{ $locale['label'] }}"
            @if ($active) aria-current="true" @endif
            class="rounded-full px-2.5 py-1.5 font-mono uppercase transition-colors {{ $active ? 'bg-raised text-fg' : 'text-subtle hover:text-fg' }}"
        >
            <span class="sr-only">{{ $locale['label'] }}</span>
            <span aria-hidden="true">{{ $code }}</span>
        </a>
    @endforeach
</div>
