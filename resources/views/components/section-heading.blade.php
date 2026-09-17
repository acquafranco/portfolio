@props(['title', 'lead' => null])

<div class="mb-10 max-w-measure">
    <h2 class="font-display text-2xl md:text-3xl font-semibold text-paper tracking-tight">
        {{ $title }}
    </h2>
    @if ($lead)
        <p class="mt-3 text-slate leading-relaxed">
            {{ $lead }}
        </p>
    @endif
</div>
