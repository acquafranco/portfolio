@props(['project', 'size' => 40])

@if ($project->logoUrl)
    <img src="{{ $project->logoUrl }}" alt="" width="{{ $size }}" height="{{ $size }}" loading="lazy" decoding="async" {{ $attributes->merge(['class' => 'shrink-0 rounded-lg ring-1 ring-white/10']) }} style="width: {{ $size }}px; height: {{ $size }}px">
@else
    <span {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center justify-center rounded-lg font-semibold text-ink']) }} style="width: {{ $size }}px; height: {{ $size }}px; background: {{ $project->accent }}" aria-hidden="true">{{ $project->initials() }}</span>
@endif
