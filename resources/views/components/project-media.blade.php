@props(['project', 'eager' => false, 'sizes' => '(min-width: 1024px) 640px, 100vw'])

{{-- Browser-style frame around the product screenshot. Falls back to the
     project logo/initial on its accent colour when there is no cover. --}}
<div {{ $attributes->merge(['class' => 'relative']) }} style="--accent: {{ $project->accent }}">
    <div class="accent-glow pointer-events-none absolute -inset-8 -z-10 opacity-80 blur-2xl" aria-hidden="true"></div>

    <div class="overflow-hidden rounded-xl border border-line bg-raised shadow-2xl shadow-black/40">
        <div class="flex items-center gap-3 border-b border-line bg-surface px-3.5 py-2.5" aria-hidden="true">
            <div class="flex gap-1.5">
                <span class="h-2.5 w-2.5 rounded-full bg-line"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-line"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-line"></span>
            </div>
            <div class="flex min-w-0 flex-1 items-center justify-center gap-1.5 truncate rounded-md bg-ink/70 px-3 py-1 font-mono text-[11px] text-subtle">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                <span class="truncate">{{ $project->domain() ?? $project->slug }}</span>
            </div>
        </div>

        <div class="media-placeholder aspect-[40/21]">
            @if ($project->coverUrl)
                <img
                    src="{{ $project->coverUrl }}"
                    @if ($project->coverSmallUrl) srcset="{{ $project->coverSmallUrl }} 720w, {{ $project->coverUrl }} 1200w" sizes="{{ $sizes }}" @endif
                    alt="{{ __('portfolio.projects.cover_alt', ['name' => $project->name]) }}"
                    width="1200"
                    height="630"
                    @if ($eager) fetchpriority="high" @else loading="lazy" @endif
                    decoding="async"
                    data-media
                    class="h-full w-full object-cover object-top"
                >
            @else
                <div class="flex h-full w-full items-center justify-center" style="background: color-mix(in srgb, {{ $project->accent }} 18%, rgb(var(--color-raised)))">
                    <span class="text-6xl font-semibold accent-text">{{ $project->initials() }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
