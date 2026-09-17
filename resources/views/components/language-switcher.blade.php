@props(['class' => ''])

<div class="{{ $class }} items-center gap-3 text-sm">
    @foreach (config('portfolio.locales') as $code => $locale)
        @php $active = app()->getLocale() === $code; @endphp
        <a href="{{ url('/'.$code) }}" @if ($active) aria-current="page" @endif class="{{ $active ? 'text-paper border-b border-amber' : 'text-slate hover:text-paper' }} transition-colors pb-0.5 inline-flex items-center gap-1.5">
            <span aria-hidden="true">{{ $locale['flag'] }}</span>
            <span>{{ strtoupper($code) }}</span>
        </a>
    @endforeach
</div>
