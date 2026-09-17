<x-layout>

    {{-- HERO ---------------------------------------------------------- --}}
    <section class="max-w-5xl mx-auto px-6 pt-16 pb-20 md:pt-24 md:pb-28 grid md:grid-cols-5 gap-10 items-center">
        <div class="md:col-span-3">
            <p class="hero-reveal text-sm text-slate mb-4">{{ __('portfolio.hero.location') }}</p>

            <h1 class="hero-reveal hero-reveal-delay-1 font-display text-3xl sm:text-4xl md:text-5xl font-semibold leading-tight tracking-tight text-paper max-w-measure">
                {{ __('portfolio.hero.headline') }}
            </h1>

            <p class="hero-reveal hero-reveal-delay-2 mt-6 text-slate text-lg leading-relaxed max-w-measure">
                {{ __('portfolio.hero.subheadline') }}
            </p>

            <div class="hero-reveal hero-reveal-delay-3 mt-8 flex flex-wrap items-center gap-4">
                <x-button href="#proyectos" variant="primary">{{ __('portfolio.hero.cta_projects') }}</x-button>
                <x-button href="#contacto" variant="secondary">{{ __('portfolio.hero.cta_contact') }}</x-button>
                <a href="{{ asset($profile['cv'][app()->getLocale()]) }}" class="text-sm text-slate hover:text-paper transition-colors" download>
                    {{ __('portfolio.hero.cta_cv') }}
                </a>
            </div>
        </div>

        {{-- Corte transversal de un edificio: la metáfora del recorrido de
             Franco (Técnico de ascensores → software) en una sola
             ilustración, en vez de un ícono genérico. --}}
        <div class="hidden md:flex md:col-span-2 justify-center" aria-hidden="true">
            <svg viewBox="0 0 220 320" class="w-full max-w-[220px]" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20.5" y="10.5" width="140" height="299" stroke="#2B313A" />
                @for ($i = 1; $i < 7; $i++)
                    <line x1="20.5" y1="{{ 10.5 + $i * 42.7 }}" x2="160.5" y2="{{ 10.5 + $i * 42.7 }}" stroke="#2B313A" />
                @endfor

                {{-- shaft --}}
                <rect x="76.5" y="10.5" width="28" height="299" stroke="#2B313A" />

                {{-- car, parked at the top floor: where he is now --}}
                <rect x="80.5" y="18" width="20" height="27" fill="#FF6A1A" />

                {{-- measurement ticks --}}
                @for ($i = 0; $i < 8; $i++)
                    <line x1="12" y1="{{ 10.5 + $i * 42.7 }}" x2="20.5" y2="{{ 10.5 + $i * 42.7 }}" stroke="#9AA1AC" stroke-width="0.75" />
                @endfor

                <line x1="160.5" y1="10.5" x2="185" y2="10.5" stroke="#9AA1AC" stroke-width="0.75" stroke-dasharray="2 2" />
                <line x1="185" y1="10.5" x2="185" y2="309.5" stroke="#9AA1AC" stroke-width="0.75" />
                <line x1="160.5" y1="309.5" x2="185" y2="309.5" stroke="#9AA1AC" stroke-width="0.75" stroke-dasharray="2 2" />
            </svg>
        </div>
    </section>

    {{-- SOBRE MÍ -------------------------------------------------------- --}}
    <section id="sobre-mi" class="max-w-5xl mx-auto px-6 py-16 md:py-20">
        <div class="spec-line">
            <x-section-heading :title="__('portfolio.about.heading')" />
            <div class="space-y-5 max-w-measure text-paper/90 leading-relaxed">
                @foreach (__('portfolio.about.paragraphs') as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>

    {{-- QUÉ HAGO ---------------------------------------------------------- --}}
    <section id="que-hago" class="max-w-5xl mx-auto px-6 py-16 md:py-20">
        <div class="spec-line">
            <x-section-heading :title="__('portfolio.work.heading')" />

            <div class="grid sm:grid-cols-2 gap-x-10 gap-y-10">
                @foreach (__('portfolio.work.items') as $item)
                    <div>
                        <h3 class="font-display text-lg font-semibold text-paper mb-2">{{ $item['title'] }}</h3>
                        <p class="text-slate leading-relaxed">{{ $item['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-14">
                <p class="text-sm text-slate mb-4">{{ __('portfolio.skills.heading') }}</p>
                <div class="space-y-6">
                    @foreach ($skillGroups as $group)
                        <div class="grid sm:grid-cols-4 gap-3 sm:gap-6">
                            <p class="text-sm text-paper/80 sm:col-span-1">{{ $group['label'] }}</p>
                            <ul class="flex flex-wrap gap-2 sm:col-span-3">
                                @foreach ($group['items'] as $skill)
                                    <x-skill-badge :label="$skill" />
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- PROYECTOS ---------------------------------------------------------- --}}
    <section id="proyectos" class="max-w-5xl mx-auto px-6 py-16 md:py-20">
        <div class="spec-line">
            <x-section-heading
                :title="__('portfolio.projects.heading')"
                :lead="__('portfolio.projects.intro')"
            />

            <div>
                @foreach ($projects as $project)
                    <x-project-card :project="$project" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- EXPERIENCIA ---------------------------------------------------------- --}}
    <section id="experiencia" class="max-w-5xl mx-auto px-6 py-16 md:py-20">
        <div class="spec-line">
            <x-section-heading :title="__('portfolio.experience.heading')" />

            <div class="max-w-measure">
                @foreach ($experience as $item)
                    <x-timeline-item
                        :period="$item['period']"
                        :title="$item['title']"
                        :description="$item['description']"
                    />
                @endforeach
            </div>
        </div>
    </section>

    {{-- CÓMO TRABAJO ---------------------------------------------------------- --}}
    <section id="como-trabajo" class="max-w-5xl mx-auto px-6 py-16 md:py-20">
        <div class="spec-line">
            <x-section-heading
                :title="__('portfolio.process.heading')"
                :lead="__('portfolio.process.intro')"
            />

            <ol class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach (config('portfolio.process_steps') as $i => $key)
                    <li>
                        <span class="font-display text-sm text-amber">{{ sprintf('%02d', $i + 1) }}</span>
                        <h3 class="font-display text-base font-semibold text-paper mt-2 mb-2">
                            {{ __('portfolio.process.steps.'.$key.'.title') }}
                        </h3>
                        <p class="text-slate leading-relaxed text-sm">
                            {{ __('portfolio.process.steps.'.$key.'.description') }}
                        </p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- CONTACTO ---------------------------------------------------------- --}}
    <section id="contacto" class="max-w-5xl mx-auto px-6 py-16 md:py-24">
        <div class="spec-line">
            <x-section-heading
                :title="__('portfolio.contact.heading')"
                :lead="__('portfolio.contact.intro')"
            />

            <div class="flex flex-wrap items-center gap-4 mb-10">
                <x-button href="{{ $profile['links']['whatsapp'] }}" variant="primary" external>
                    {{ __('portfolio.contact.cta') }}
                </x-button>
            </div>

            <ul class="flex flex-wrap gap-x-10 gap-y-4 text-sm">
                <li>
                    <a href="mailto:{{ $profile['links']['email'] }}" class="text-slate hover:text-paper transition-colors">
                        {{ __('portfolio.contact.email') }} — {{ $profile['links']['email'] }}
                    </a>
                </li>
                <li>
                    <a href="{{ $profile['links']['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="text-slate hover:text-paper transition-colors">
                        {{ __('portfolio.contact.whatsapp') }}
                    </a>
                </li>
                <li>
                    <a href="{{ $profile['links']['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-slate hover:text-paper transition-colors">
                        {{ __('portfolio.contact.linkedin') }}
                    </a>
                </li>
                <li>
                    <a href="{{ $profile['links']['github'] }}" target="_blank" rel="noopener noreferrer" class="text-slate hover:text-paper transition-colors">
                        {{ __('portfolio.contact.github') }}
                    </a>
                </li>
            </ul>
        </div>
    </section>

</x-layout>
