@props(['kind'])

{{-- Only client work is labelled; own products are the default. --}}
@if ($kind === \App\Portfolio\ProjectKind::Client)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full border border-line bg-ink/60 px-2.5 py-1 font-mono text-[11px] uppercase tracking-wider text-muted']) }}>
        {{ $kind->label() }}
    </span>
@endif
