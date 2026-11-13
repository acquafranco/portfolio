@props(['project'])

{{-- Compact card for non-featured projects. --}}
<article
    aria-labelledby="project-{{ $project->slug }}"
    style="--accent: {{ $project->accent }}"
    {{ $attributes->merge(['class' => 'card group relative flex flex-col p-6 transition-colors hover:border-subtle/60']) }}
>
    <div class="mb-5 flex items-start justify-between gap-3">
        <x-project-logo :project="$project" :size="36" />
        <div class="flex flex-wrap justify-end gap-2">
            <x-kind-badge :kind="$project->kind" />
            <x-status-badge :status="$project->status" />
        </div>
    </div>

    <h3 id="project-{{ $project->slug }}" class="text-lg font-semibold tracking-tight text-fg">
        <a href="{{ route('projects.show', ['slug' => $project->slug]) }}" class="after:absolute after:inset-0 after:rounded-2xl">{{ $project->name }}</a>
    </h3>
    <p class="mt-1 font-mono text-xs text-subtle">{{ $project->category }}</p>
    <p class="mt-4 flex-1 text-sm leading-relaxed text-muted">{{ $project->tagline }}</p>

    <ul class="mt-5 flex flex-wrap gap-1.5" aria-label="{{ __('portfolio.projects.stack') }}">
        @foreach (array_slice($project->stack, 0, 4) as $tech)
            <li class="chip">{{ $tech }}</li>
        @endforeach
    </ul>

    <span class="mt-6 inline-flex items-center gap-1.5 text-sm text-fg" aria-hidden="true">
        {{ __('portfolio.projects.case_study') }}
        <x-glyph name="arrow-right" class="transition-transform group-hover:translate-x-0.5" />
    </span>
</article>
