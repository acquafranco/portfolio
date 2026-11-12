@props(['projects'])

{{-- Animated terminal (resources/js/terminal.js). The full output is
     rendered server-side, so without JS or with reduced motion it is simply
     shown as is, and every product link works either way. --}}
@php $prompt = '<span class="text-live">franco</span><span class="text-subtle">@</span><span class="text-sky-400">portfolio</span> <span class="text-subtle">~</span> <span class="text-amber">$</span>'; @endphp

<div {{ $attributes->merge(['class' => 'card overflow-hidden bg-[#0c0d10]/95 shadow-2xl shadow-black/40 backdrop-blur']) }} data-terminal>
    <div class="flex items-center gap-3 border-b border-line bg-surface px-4 py-2.5">
        <div class="flex gap-1.5" aria-hidden="true">
            <span class="h-3 w-3 rounded-full bg-[#ff5f57]"></span>
            <span class="h-3 w-3 rounded-full bg-[#febc2e]"></span>
            <span class="h-3 w-3 rounded-full bg-[#28c840]"></span>
        </div>
        <p class="flex-1 text-center font-mono text-[11px] text-subtle">franco@portfolio: ~ — zsh</p>
        <button type="button" data-terminal-replay class="rounded p-1 text-subtle opacity-0 transition hover:text-fg focus-visible:opacity-100 [&.is-visible]:opacity-100" title="{{ __('portfolio.hero.terminal.replay') }}">
            <span class="sr-only">{{ __('portfolio.hero.terminal.replay') }}</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8M3 3v5h5"/></svg>
        </button>
    </div>

    <div class="min-h-[22rem] space-y-1.5 p-5 font-mono text-[12.5px] leading-relaxed sm:text-[13px]">
        <p data-line="cmd">{!! $prompt !!} <span data-type>whoami</span></p>
        <p data-line="out" class="text-muted">{{ config('portfolio.name') }} — {{ __('portfolio.seo.job_title') }}</p>

        <p data-line="cmd" class="pt-2">{!! $prompt !!} <span data-type>ls ~/products</span></p>
        <p data-line="out" class="flex flex-wrap gap-x-4">
            @foreach ($projects as $project)
                <span style="color: {{ $project->accent }}">{{ $project->slug }}/</span>
            @endforeach
        </p>

        <p data-line="cmd" class="pt-2">{!! $prompt !!} <span data-type>php artisan deploy --all</span></p>
        <div data-line="progress" class="flex items-center gap-3 text-subtle" aria-hidden="true">
            <span class="relative h-1.5 flex-1 overflow-hidden rounded-full bg-line">
                <span data-progress class="absolute inset-y-0 left-0 w-full rounded-full bg-gradient-to-r from-amber to-live"></span>
            </span>
            <span data-progress-label class="w-9 text-right">100%</span>
        </div>
        <ul class="space-y-1">
            @foreach ($projects as $project)
                <li data-line="out">
                    <a href="{{ route('projects.show', ['slug' => $project->slug]) }}" class="group grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-md px-1 -mx-1 transition-colors hover:bg-raised">
                        <span class="text-live" aria-hidden="true">✓</span>
                        <span class="min-w-0 truncate">
                            <span class="text-fg group-hover:underline">{{ $project->name }}</span>
                            <span class="text-subtle"> {{ $project->domain() ?? $project->slug }}</span>
                        </span>
                        <span class="text-[11px] uppercase tracking-wider" style="color: {{ $project->accent }}">{{ $project->status->label() }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        <p data-line="out" class="pt-1 text-subtle">
            <span class="text-amber">→</span> {{ trans_choice('portfolio.hero.terminal.done', $projects->count(), ['count' => $projects->count()]) }}
        </p>

        <p data-line="cmd" class="pt-2">{!! $prompt !!} <span class="terminal-cursor" aria-hidden="true"></span></p>
    </div>
</div>
