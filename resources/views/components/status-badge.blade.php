@props(['status'])

@php
    $dot = match ($status) {
        \App\Portfolio\ProjectStatus::Production => 'status-dot-live',
        \App\Portfolio\ProjectStatus::Development => 'inline-flex h-2 w-2 rounded-full bg-amber',
        \App\Portfolio\ProjectStatus::Experimental => 'inline-flex h-2 w-2 rounded-full bg-sky-400',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 rounded-full border border-line bg-ink/60 px-2.5 py-1 font-mono text-[11px] uppercase tracking-wider text-muted']) }}>
    <span class="{{ $dot }}" aria-hidden="true"></span>
    {{ $status->label() }}
</span>
