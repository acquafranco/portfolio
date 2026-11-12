@props(['eyebrow' => null, 'title', 'lead' => null, 'id' => null])

<div {{ $attributes->merge(['class' => 'mb-10 max-w-2xl md:mb-14']) }}>
    @if ($eyebrow)
        <p class="eyebrow mb-4">{{ $eyebrow }}</p>
    @endif
    <h2 @if ($id) id="{{ $id }}" @endif class="text-3xl font-semibold tracking-tight text-fg md:text-4xl">{{ $title }}</h2>
    @if ($lead)
        <p class="mt-4 text-base leading-relaxed text-muted md:text-lg">{{ $lead }}</p>
    @endif
</div>
