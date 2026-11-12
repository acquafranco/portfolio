@props(['project', 'reverse' => false, 'eager' => false])

<article
    aria-labelledby="project-{{ $project->slug }}"
    style="--accent: {{ $project->accent }}"
    {{ $attributes->merge(['class' => 'card relative overflow-hidden p-5 sm:p-8 lg:p-10']) }}
>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px" style="background: linear-gradient(90deg, transparent, {{ $project->accent }}, transparent)" aria-hidden="true"></div>

    <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2 lg:gap-14">
        <div @class(['lg:order-2' => $reverse])>
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <x-project-logo :project="$project" :size="40" />
                    <div>
                        <h3 id="project-{{ $project->slug }}" class="text-lg font-semibold leading-tight tracking-tight text-fg">{{ $project->name }}</h3>
                        <p class="font-mono text-xs text-subtle">{{ $project->category }}</p>
                    </div>
                </div>
                <x-status-badge :status="$project->status" />
            </div>

            <p class="text-2xl font-semibold leading-snug tracking-tight text-fg sm:text-[1.7rem]">{{ $project->tagline }}</p>
            <p class="mt-4 leading-relaxed text-muted">{{ $project->summary }}</p>

            <ul class="mt-6 grid gap-2.5 text-sm text-fg/90 sm:grid-cols-2">
                @foreach (array_slice($project->features, 0, 4) as $feature)
                    <li class="flex gap-2.5">
                        <x-icon name="check" class="mt-0.5 accent-text" />
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>

            <ul class="mt-6 flex flex-wrap gap-1.5" aria-label="{{ __('portfolio.projects.stack') }}">
                @foreach (array_slice($project->stack, 0, 6) as $tech)
                    <li class="chip">{{ $tech }}</li>
                @endforeach
            </ul>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('projects.show', ['slug' => $project->slug]) }}" class="btn-primary">
                    {{ __('portfolio.projects.case_study') }}
                    <span class="sr-only">: {{ $project->name }}</span>
                    <x-icon name="arrow-right" />
                </a>
                @if ($project->url)
                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="btn-secondary">
                        {{ $project->domain() }}
                        <span class="sr-only">{{ __('portfolio.projects.opens_new_tab') }}</span>
                        <x-icon name="arrow-up-right" />
                    </a>
                @endif
            </div>
        </div>

        <a href="{{ route('projects.show', ['slug' => $project->slug]) }}" tabindex="-1" aria-hidden="true" @class(['group block', 'lg:order-1' => $reverse])>
            <x-project-media :project="$project" :eager="$eager" class="transition duration-500 ease-out group-hover:-translate-y-1" />
        </a>
    </div>
</article>
