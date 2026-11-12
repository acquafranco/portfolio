@use('App\Portfolio\Localization')
@use('App\Portfolio\ProjectStatus')

@php
    $links = config('portfolio.links');
    $cv = Localization::cvPath();
    $liveCount = $projects->filter(fn ($project) => $project->status === ProjectStatus::Production)->count();
    $featured = $projects->where('featured', true)->values();
    $others = $projects->where('featured', false)->values();

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Person',
                '@id' => url('/').'#person',
                'name' => config('portfolio.name'),
                'jobTitle' => __('portfolio.seo.job_title'),
                'url' => route('home'),
                'email' => 'mailto:'.$links['email'],
                'sameAs' => [$links['linkedin'], $links['github']],
                'knowsAbout' => ['Laravel', 'PHP', 'SaaS', 'Filament', 'Tailwind CSS', 'MySQL'],
            ],
            [
                '@type' => 'WebSite',
                'name' => config('portfolio.seo.site_name'),
                'url' => route('home'),
                'inLanguage' => app()->getLocale(),
                'author' => ['@id' => url('/').'#person'],
            ],
            [
                '@type' => 'ItemList',
                'name' => __('portfolio.projects.eyebrow'),
                'itemListElement' => $projects->values()->map(fn ($project, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('projects.show', ['slug' => $project->slug]),
                    'name' => $project->name,
                ])->all(),
            ],
        ],
    ];
@endphp

<x-layout :json-ld="$jsonLd">

    {{-- HERO ----------------------------------------------------------- --}}
    <section class="relative isolate overflow-hidden border-b border-line/60" aria-labelledby="hero-title" data-spotlight>
        <div class="hero-spotlight pointer-events-none absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="grid-backdrop absolute inset-0 -z-10" aria-hidden="true"></div>
        <div class="absolute -top-40 left-1/2 -z-10 h-80 w-[48rem] -translate-x-1/2 rounded-full bg-amber/10 blur-3xl" aria-hidden="true"></div>

        <div class="container-site grid grid-cols-1 gap-14 pb-20 pt-14 md:pt-24 lg:grid-cols-12 lg:items-center lg:gap-12 lg:pb-28">
            <div class="lg:col-span-7">
                <p class="reveal inline-flex items-center gap-2.5 rounded-full border border-line bg-surface/70 px-3 py-1.5 text-xs text-muted">
                    <span class="status-dot-live" aria-hidden="true"></span>
                    {{ __('portfolio.hero.availability') }}
                </p>

                <h1 id="hero-title" class="reveal reveal-1 mt-6 text-[2.5rem] font-semibold leading-[1.05] tracking-tightest text-fg sm:text-5xl lg:text-[4rem]">
                    {{ __('portfolio.hero.headline') }}
                </h1>

                <p class="reveal reveal-2 mt-6 max-w-xl text-lg leading-relaxed text-muted">
                    {{ __('portfolio.hero.subheadline') }}
                </p>

                <div class="reveal reveal-3 mt-9 flex flex-wrap items-center gap-3">
                    <a href="#projects" class="btn-primary">
                        {{ __('portfolio.hero.cta_projects') }}
                        <x-glyph name="arrow-down" />
                    </a>
                    <a href="#contact" class="btn-secondary">{{ __('portfolio.hero.cta_contact') }}</a>
                    @if ($cv)
                        <a href="{{ asset($cv) }}" class="btn-ghost" download>
                            <x-glyph name="download" />
                            {{ __('portfolio.hero.cta_cv') }}
                        </a>
                    @endif
                </div>

                <dl class="reveal reveal-4 mt-12 grid max-w-xl grid-cols-3 gap-6 border-t border-line pt-6">
                    <div class="flex flex-col-reverse justify-end">
                        <dt class="mt-1 text-xs leading-snug text-subtle">{{ __('portfolio.hero.facts.products') }}</dt>
                        <dd class="text-2xl font-semibold leading-8 tracking-tight text-fg">{{ $projects->count() }}</dd>
                    </div>
                    <div class="flex flex-col-reverse justify-end">
                        <dt class="mt-1 text-xs leading-snug text-subtle">{{ __('portfolio.hero.facts.live') }}</dt>
                        <dd class="text-2xl font-semibold leading-8 tracking-tight text-fg">{{ $liveCount }}</dd>
                    </div>
                    <div class="flex flex-col-reverse justify-end">
                        <dt class="mt-1 text-xs leading-snug text-subtle">{{ __('portfolio.hero.facts.ownership') }}</dt>
                        <dd class="font-mono text-2xl font-semibold leading-8 tracking-tight text-fg">0→1</dd>
                    </div>
                </dl>
            </div>

            {{-- Animated terminal: "deploys" every product, each line links to it. --}}
            <div class="reveal reveal-2 lg:col-span-5">
                <x-hero-terminal :projects="$projects" />
            </div>
        </div>
    </section>

    {{-- PRODUCTS ------------------------------------------------------- --}}
    <section id="projects" class="py-20 md:py-28" aria-labelledby="projects-title" x-data="projectFilter">
        <div class="container-site">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <x-section-heading
                    id="projects-title"
                    :eyebrow="__('portfolio.projects.eyebrow')"
                    :title="__('portfolio.projects.heading')"
                    :lead="__('portfolio.projects.intro')"
                    class="md:mb-14"
                />

                @if (count($statusFilters) > 1)
                    <div class="mb-10 flex flex-wrap gap-2 md:mb-14" role="group" aria-label="{{ __('portfolio.projects.filter_label') }}" x-cloak>
                        <button type="button" @click="status = 'all'" :aria-pressed="(status === 'all').toString()" :class="status === 'all' ? 'border-fg/40 bg-raised text-fg' : 'text-subtle hover:text-fg'" class="chip px-3.5 py-1.5 transition-colors">
                            {{ __('portfolio.projects.filter_all') }} <span class="ml-1.5 font-mono text-subtle">{{ $projects->count() }}</span>
                        </button>
                        @foreach ($statusFilters as $filter)
                            <button type="button" @click="status = @js($filter['value'])" :aria-pressed="(status === @js($filter['value'])).toString()" :class="status === @js($filter['value']) ? 'border-fg/40 bg-raised text-fg' : 'text-subtle hover:text-fg'" class="chip px-3.5 py-1.5 transition-colors">
                                {{ $filter['label'] }} <span class="ml-1.5 font-mono text-subtle">{{ $filter['count'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-8 md:space-y-10">
                @foreach ($featured as $project)
                    <div x-show="matches(@js($project->status->value))" x-transition.opacity.duration.200ms>
                        <x-project-showcase :project="$project" :reverse="$loop->odd === false" />
                    </div>
                @endforeach
            </div>

            @if ($others->isNotEmpty())
                <ul class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 md:mt-10">
                    @foreach ($others as $project)
                        <li x-show="matches(@js($project->status->value))" class="flex">
                            <x-project-card :project="$project" class="w-full" />
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    {{-- ABOUT + CAPABILITIES + STACK ------------------------------------ --}}
    <section id="about" class="border-y border-line/60 bg-surface/40 py-20 md:py-28" aria-labelledby="about-title">
        <div class="container-site">
            <div class="grid grid-cols-1 gap-14 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <x-section-heading id="about-title" :eyebrow="__('portfolio.about.eyebrow')" :title="__('portfolio.about.heading')" class="mb-8 md:mb-8" />
                    <div class="space-y-5 leading-relaxed text-muted">
                        @foreach (__('portfolio.about.paragraphs') as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <h3 class="mb-5 font-mono text-xs uppercase tracking-wider text-subtle">{{ __('portfolio.capabilities.heading') }}</h3>
                    @php
                        $capabilityIcons = ['product' => 'box', 'saas' => 'layers', 'automation' => 'zap', 'interfaces' => 'layout', 'business' => 'wrench'];
                    @endphp
                    <ul class="grid gap-4 sm:grid-cols-2">
                        @foreach (config('portfolio.capabilities') as $key)
                            <li @class(['card p-5', 'sm:col-span-2' => $loop->last && $loop->count % 2 === 1])>
                                <span class="mb-4 inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line bg-raised text-amber">
                                    <x-glyph :name="$capabilityIcons[$key] ?? 'box'" :size="18" />
                                </span>
                                <h4 class="font-medium text-fg">{{ __("portfolio.capabilities.items.{$key}.title") }}</h4>
                                <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ __("portfolio.capabilities.items.{$key}.description") }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mt-14 divide-y divide-line">
                <h3 class="px-5 py-4 font-mono text-xs uppercase tracking-wider text-subtle sm:px-6">{{ __('portfolio.skills.heading') }}</h3>
                @foreach ($skillGroups as $group)
                    <div class="grid gap-3 px-5 py-4 sm:grid-cols-4 sm:items-center sm:gap-6 sm:px-6">
                        <p class="text-sm font-medium text-fg">{{ $group['label'] }}</p>
                        <ul class="flex flex-wrap gap-1.5 sm:col-span-3">
                            @foreach ($group['items'] as $skill)
                                <li class="chip">{{ $skill }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PROCESS -------------------------------------------------------- --}}
    <section id="process" class="py-20 md:py-28" aria-labelledby="process-title">
        <div class="container-site">
            <x-section-heading id="process-title" :eyebrow="__('portfolio.process.eyebrow')" :title="__('portfolio.process.heading')" :lead="__('portfolio.process.intro')" />

            <ol class="grid gap-px overflow-hidden rounded-2xl border border-line bg-line sm:grid-cols-2 lg:grid-cols-3">
                @foreach (config('portfolio.process_steps') as $i => $key)
                    <li class="bg-ink p-6 md:p-7">
                        <span class="font-mono text-sm text-amber">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3 class="mt-3 font-medium text-fg">{{ __("portfolio.process.steps.{$key}.title") }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ __("portfolio.process.steps.{$key}.description") }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- EXPERIENCE ----------------------------------------------------- --}}
    <section id="experience" class="border-t border-line/60 py-20 md:py-28" aria-labelledby="experience-title">
        <div class="container-site grid grid-cols-1 gap-10 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <x-section-heading id="experience-title" :eyebrow="__('portfolio.experience.eyebrow')" :title="__('portfolio.experience.heading')" />
            </div>
            <ol class="lg:col-span-7">
                @foreach ($experience as $item)
                    <x-timeline-item :period="$item['period']" :title="$item['title']" :description="$item['description']" />
                @endforeach
            </ol>
        </div>
    </section>

    {{-- CONTACT -------------------------------------------------------- --}}
    <section id="contact" class="pb-20 md:pb-28" aria-labelledby="contact-title">
        <div class="container-site">
            <div class="card relative isolate overflow-hidden px-6 py-12 sm:px-10 md:px-14 md:py-16">
                <div class="absolute -right-24 -top-24 -z-10 h-72 w-72 rounded-full bg-amber/15 blur-3xl" aria-hidden="true"></div>
                <div class="grid-backdrop absolute inset-0 -z-10 opacity-60" aria-hidden="true"></div>

                <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-end">
                    <div class="lg:col-span-7">
                        <p class="eyebrow mb-4">{{ __('portfolio.contact.eyebrow') }}</p>
                        <h2 id="contact-title" class="text-3xl font-semibold tracking-tight text-fg md:text-5xl">{{ __('portfolio.contact.heading') }}</h2>
                        <p class="mt-4 max-w-xl text-lg leading-relaxed text-muted">{{ __('portfolio.contact.intro') }}</p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ Localization::whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                                <x-glyph name="chat" />
                                {{ __('portfolio.contact.cta') }}
                                <span class="sr-only">{{ __('portfolio.projects.opens_new_tab') }}</span>
                            </a>
                            @if ($cv)
                                <a href="{{ asset($cv) }}" class="btn-secondary" download>
                                    <x-glyph name="download" />
                                    {{ __('portfolio.hero.cta_cv') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <ul class="space-y-2 lg:col-span-5">
                        <li x-data="copyText(@js($links['email']))" class="flex items-center gap-2 rounded-xl border border-line bg-ink/60 p-1.5 pl-4">
                            <x-glyph name="mail" class="text-subtle" />
                            <a href="mailto:{{ $links['email'] }}" class="min-w-0 flex-1 truncate py-2 text-sm text-fg hover:underline">{{ $links['email'] }}</a>
                            <button type="button" @click="copy()" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs text-muted transition-colors hover:bg-raised hover:text-fg">
                                <span x-show="!copied" class="inline-flex items-center gap-1.5"><x-glyph name="copy" :size="14" /> {{ __('portfolio.contact.copy') }}</span>
                                <span x-show="copied" x-cloak class="inline-flex items-center gap-1.5 text-live"><x-glyph name="check" :size="14" /> {{ __('portfolio.contact.copied') }}</span>
                            </button>
                            <span class="sr-only" role="status" aria-live="polite" x-text="copied ? @js(__('portfolio.contact.copied')) : ''"></span>
                        </li>
                        @foreach (['linkedin' => 'linkedin', 'github' => 'github'] as $key => $icon)
                            <li>
                                <a href="{{ $links[$key] }}" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-2 rounded-xl border border-line bg-ink/60 px-4 py-3.5 text-sm text-fg transition-colors hover:border-subtle/60">
                                    <x-glyph :name="$icon" class="text-subtle" />
                                    <span class="flex-1">{{ __("portfolio.contact.{$key}") }}</span>
                                    <span class="sr-only">{{ __('portfolio.projects.opens_new_tab') }}</span>
                                    <x-glyph name="arrow-up-right" class="text-subtle transition-colors group-hover:text-fg" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

</x-layout>
