@props(['project'])

@if ($project->testimonial)
    <figure {{ $attributes->merge(['class' => 'rounded-xl border border-line bg-ink/40 p-5']) }}>
        <figcaption class="mb-2 font-mono text-[11px] uppercase tracking-wider accent-text">{{ __('portfolio.projects.testimonial') }}</figcaption>
        <blockquote class="leading-relaxed text-fg/90">“{{ $project->testimonial }}”</blockquote>
        @if ($project->testimonialAuthor)
            <p class="mt-3 text-sm text-subtle">— {{ $project->testimonialAuthor }}</p>
        @endif
    </figure>
@endif
