@props(['src', 'alt', 'eager' => false])

{{-- Phone bezel around a mobile screenshot (390×844 viewport). --}}
<div {{ $attributes->merge(['class' => 'relative rounded-[1.6rem] border border-line bg-ink p-[5px] shadow-2xl shadow-black/50 ring-1 ring-white/5']) }}>
    <div class="pointer-events-none absolute left-1/2 top-[9px] z-10 h-[9px] w-[30%] -translate-x-1/2 rounded-full bg-ink" aria-hidden="true"></div>
    <div class="media-placeholder aspect-[390/844] overflow-hidden rounded-[1.3rem]">
        <img
            src="{{ $src }}"
            alt="{{ $alt }}"
            width="600"
            height="1299"
            @if ($eager) fetchpriority="high" @else loading="lazy" @endif
            decoding="async"
            data-media
            class="h-full w-full object-cover object-top"
        >
    </div>
</div>
