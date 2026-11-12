@php
    $links = config('portfolio.links');

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => $project->name,
        'description' => $project->summary,
        'applicationCategory' => 'BusinessApplication',
        'operatingSystem' => 'Web',
        'url' => $project->url ?? route('projects.show', ['slug' => $project->slug]),
        'image' => $project->coverUrl ? $project->coverUrl : null,
        'inLanguage' => app()->getLocale(),
        'creator' => ['@type' => 'Person', 'name' => config('portfolio.name'), 'url' => route('home')],
    ];

    $meta = array_filter([
        __('portfolio.project.category') => $project->category,
        __('portfolio.project.status') => $project->status->label(),
        __('portfolio.project.year') => $project->year,
    ]);
@endphp

<x-layout
    :title="$project->name.' · '.$project->category"
    :description="$project->summary"
    :image="$project->coverUrl"
    type="article"
    :json-ld="array_filter($jsonLd)"
>
    <article style="--accent: {{ $project->accent }}" aria-labelledby="project-title">
        {{-- HEADER ---------------------------------------------------- --}}
        <header class="relative isolate overflow-hidden border-b border-line/60">
            <div class="grid-backdrop absolute inset-0 -z-10" aria-hidden="true"></div>
            <div class="accent-glow absolute inset-0 -z-10 opacity-70" aria-hidden="true"></div>

            <div class="container-site pb-16 pt-10 md:pb-20 md:pt-14">
                <a href="{{ route('home') }}#projects" class="btn-ghost -ml-3 mb-10">
                    <x-glyph name="arrow-left" />
                    {{ __('portfolio.project.back') }}
                </a>

                <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-6">
                        <div class="reveal flex flex-wrap items-center gap-3">
                            <x-project-logo :project="$project" :size="48" />
                            <x-status-badge :status="$project->status" />
                        </div>

                        <h1 id="project-title" class="reveal reveal-1 mt-6 text-5xl font-semibold tracking-tightest text-fg md:text-6xl">{{ $project->name }}</h1>
                        <p class="reveal reveal-2 mt-4 text-xl font-medium leading-snug tracking-tight text-fg/90 md:text-2xl">{{ $project->tagline }}</p>
                        <p class="reveal reveal-3 mt-5 max-w-xl leading-relaxed text-muted">{{ $project->summary }}</p>

                        <div class="reveal reveal-4 mt-8 flex flex-wrap gap-3">
                            @if ($project->url)
                                <a href="{{ $project->url }}" target="_blank" rel="noopener" class="btn-primary">
                                    {{ __('portfolio.projects.visit') }}
                                    <span class="sr-only">{{ __('portfolio.projects.opens_new_tab') }}</span>
                                    <x-glyph name="arrow-up-right" />
                                </a>
                            @endif
                            @if ($project->repository)
                                <a href="{{ $project->repository }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                                    <x-glyph name="github" />
                                    {{ __('portfolio.projects.code') }}
                                    <span class="sr-only">{{ __('portfolio.projects.opens_new_tab') }}</span>
                                </a>
                            @endif
                            @foreach ($project->links as $link)
                                <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                                    {{ $link['label'] }}
                                    <span class="sr-only">{{ __('portfolio.projects.opens_new_tab') }}</span>
                                    <x-glyph name="arrow-up-right" />
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="reveal reveal-2 lg:col-span-6">
                        <x-project-media :project="$project" eager sizes="(min-width: 1024px) 560px, 100vw" />
                    </div>
                </div>
            </div>
        </header>

        {{-- BODY ------------------------------------------------------ --}}
        <div class="container-site grid grid-cols-1 gap-14 py-16 md:py-24 lg:grid-cols-12">
            <aside class="lg:col-span-4">
                <dl class="card divide-y divide-line lg:sticky lg:top-24">
                    @foreach ($meta as $label => $value)
                        <div class="flex justify-between gap-4 px-5 py-3.5 text-sm">
                            <dt class="text-subtle">{{ $label }}</dt>
                            <dd class="text-right text-fg">{{ $value }}</dd>
                        </div>
                    @endforeach
                    @if ($project->url)
                        <div class="flex justify-between gap-4 px-5 py-3.5 text-sm">
                            <dt class="text-subtle">{{ __('portfolio.project.website') }}</dt>
                            <dd class="min-w-0 truncate text-right">
                                <a href="{{ $project->url }}" target="_blank" rel="noopener" class="link-underline text-fg">{{ $project->domain() }}</a>
                            </dd>
                        </div>
                    @endif
                    <div class="px-5 py-4">
                        <dt class="mb-3 text-sm text-subtle">{{ __('portfolio.projects.stack') }}</dt>
                        <dd>
                            <ul class="flex flex-wrap gap-1.5">
                                @foreach ($project->stack as $tech)
                                    <li class="chip">{{ $tech }}</li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                </dl>
            </aside>

            <div class="space-y-16 lg:col-span-8">
                <section aria-labelledby="problem-title" class="grid gap-6 md:grid-cols-2">
                    <h2 id="problem-title" class="sr-only">{{ __('portfolio.project.overview') }}</h2>
                    <div class="card p-6">
                        <p class="font-mono text-xs uppercase tracking-wider text-subtle">{{ __('portfolio.projects.problem') }}</p>
                        <p class="mt-3 leading-relaxed text-fg/90">{{ $project->problem }}</p>
                    </div>
                    <div class="card relative overflow-hidden p-6">
                        <div class="absolute inset-x-0 top-0 h-px" style="background: {{ $project->accent }}" aria-hidden="true"></div>
                        <p class="font-mono text-xs uppercase tracking-wider accent-text">{{ __('portfolio.projects.solution') }}</p>
                        <p class="mt-3 leading-relaxed text-fg/90">{{ $project->solution }}</p>
                    </div>
                </section>

                <section aria-labelledby="features-title">
                    <h2 id="features-title" class="text-2xl font-semibold tracking-tight text-fg">{{ __('portfolio.projects.features') }}</h2>
                    <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                        @foreach ($project->features as $feature)
                            <li class="flex gap-3 rounded-xl border border-line bg-surface/60 p-4 text-sm leading-relaxed text-fg/90">
                                <x-glyph name="check" class="mt-0.5 accent-text" />
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                @if ($project->audience || $project->role)
                    <section class="grid gap-10 border-t border-line pt-12 md:grid-cols-2" aria-label="{{ __('portfolio.project.overview') }}">
                        @if ($project->audience)
                            <div>
                                <h2 class="font-mono text-xs uppercase tracking-wider text-subtle">{{ __('portfolio.project.audience') }}</h2>
                                <p class="mt-3 leading-relaxed text-muted">{{ $project->audience }}</p>
                            </div>
                        @endif
                        @if ($project->role)
                            <div>
                                <h2 class="font-mono text-xs uppercase tracking-wider text-subtle">{{ __('portfolio.project.role') }}</h2>
                                <p class="mt-3 leading-relaxed text-muted">{{ $project->role }}</p>
                            </div>
                        @endif
                    </section>
                @endif
            </div>
        </div>
    </article>

    {{-- NEXT + CTA ---------------------------------------------------- --}}
    <div class="container-site grid grid-cols-1 gap-5 pb-20 md:grid-cols-2 md:pb-28">
        @if ($next)
            <a href="{{ route('projects.show', ['slug' => $next->slug]) }}" style="--accent: {{ $next->accent }}" class="card group flex items-center gap-5 p-6 transition-colors hover:border-subtle/60">
                <x-project-logo :project="$next" :size="48" />
                <span class="min-w-0 flex-1">
                    <span class="block font-mono text-xs uppercase tracking-wider text-subtle">{{ __('portfolio.project.next') }}</span>
                    <span class="mt-1 block text-lg font-semibold tracking-tight text-fg">{{ $next->name }}</span>
                    <span class="block truncate text-sm text-muted">{{ $next->tagline }}</span>
                </span>
                <x-glyph name="arrow-right" :size="20" class="text-subtle transition-transform group-hover:translate-x-1 group-hover:text-fg" />
            </a>
        @endif

        <div @class(['card flex flex-col justify-between gap-5 p-6 sm:flex-row sm:items-center', 'md:col-span-2' => ! $next])>
            <div>
                <p class="font-semibold tracking-tight text-fg">{{ __('portfolio.project.cta_heading') }}</p>
                <p class="mt-1 text-sm text-muted">{{ __('portfolio.project.cta_text') }}</p>
            </div>
            <a href="{{ route('home') }}#contact" class="btn-primary shrink-0">{{ __('portfolio.nav.cta') }}</a>
        </div>
    </div>
</x-layout>
