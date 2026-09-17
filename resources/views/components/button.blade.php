@props([
    'href' => null,
    'variant' => 'primary', // primary | secondary
    'external' => false,
])

@php
    $base = 'inline-flex items-center justify-center px-5 py-3 text-sm font-medium transition-colors';
    $styles = match ($variant) {
        'secondary' => 'border border-steel text-paper hover:border-amber',
        default => 'bg-amber text-graphite hover:bg-amber/90',
    };
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        @if ($external) target="_blank" rel="noopener noreferrer" @endif
        {{ $attributes->merge(['class' => "$base $styles"]) }}
    >
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => "$base $styles"]) }}>
        {{ $slot }}
    </button>
@endif
