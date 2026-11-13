@props(['project'])

@php
    use App\Portfolio\ScreenshotDevice;

    $desktopShots = $project->screenshots(ScreenshotDevice::Desktop);
    $mobileShots = $project->screenshots(ScreenshotDevice::Mobile);

    // Lightbox order: desktop first, then mobile (same order as on the page).
    $lightbox = array_map(fn (array $shot): array => [
        'url' => $shot['url'],
        'caption' => $shot['caption'],
        'mobile' => $shot['device'] === ScreenshotDevice::Mobile,
    ], [...$desktopShots, ...$mobileShots]);

    $altFor = fn (array $shot): string => $shot['caption']
        ? __('portfolio.gallery.shot_alt', ['name' => $project->name, 'caption' => $shot['caption']])
        : __('portfolio.projects.cover_alt', ['name' => $project->name]);
@endphp

@if ($lightbox !== [])
    <section
        {{ $attributes->merge(['class' => 'border-b border-line/60 py-16 md:py-24']) }}
        aria-labelledby="gallery-title"
        x-data="screenshotGallery(@js($lightbox))"
        @keydown.escape.window="close()"
        @keydown.arrow-right.window="isOpen && next()"
        @keydown.arrow-left.window="isOpen && previous()"
    >
        <div class="container-site">
            <div class="max-w-2xl">
                <p class="font-mono text-xs uppercase tracking-wider accent-text">{{ __('portfolio.gallery.eyebrow') }}</p>
                <h2 id="gallery-title" class="mt-3 text-3xl font-semibold tracking-tight text-fg md:text-4xl">{{ __('portfolio.gallery.heading') }}</h2>
                <p class="mt-4 leading-relaxed text-muted">{{ __('portfolio.gallery.text') }}</p>
            </div>

            @if ($desktopShots !== [])
                <h3 class="mt-12 flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-subtle">
                    <x-glyph name="monitor" />
                    {{ __('portfolio.gallery.desktop') }}
                </h3>
                <ul class="mt-5 grid gap-6 md:grid-cols-2">
                    @foreach ($desktopShots as $index => $shot)
                        <li>
                            <figure>
                                <button type="button" class="group block w-full text-left" @click="open({{ $index }})" aria-label="{{ __('portfolio.gallery.open') }}: {{ $shot['caption'] ?? $project->name }}">
                                    <div class="overflow-hidden rounded-xl border border-line bg-raised shadow-xl shadow-black/30 transition duration-300 group-hover:-translate-y-1 group-hover:border-subtle/60">
                                        <div class="flex gap-1.5 border-b border-line bg-surface px-3 py-2" aria-hidden="true">
                                            <span class="h-2 w-2 rounded-full bg-line"></span>
                                            <span class="h-2 w-2 rounded-full bg-line"></span>
                                            <span class="h-2 w-2 rounded-full bg-line"></span>
                                        </div>
                                        <div class="media-placeholder aspect-[16/10]">
                                            <img
                                                src="{{ $shot['url'] }}"
                                                @if ($shot['small_url']) srcset="{{ $shot['small_url'] }} 800w, {{ $shot['url'] }} 1600w" sizes="(min-width: 768px) 50vw, 100vw" @endif
                                                alt="{{ $altFor($shot) }}"
                                                width="1600"
                                                height="1000"
                                                loading="lazy"
                                                decoding="async"
                                                data-media
                                                class="h-full w-full object-cover object-top"
                                            >
                                        </div>
                                    </div>
                                </button>
                                @if ($shot['caption'])
                                    <figcaption class="mt-3 text-sm text-muted">{{ $shot['caption'] }}</figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($mobileShots !== [])
                <h3 class="mt-14 flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-subtle">
                    <x-glyph name="smartphone" />
                    {{ __('portfolio.gallery.mobile') }}
                </h3>
                <ul class="-mx-4 mt-5 flex snap-x snap-mandatory gap-5 overflow-x-auto px-4 pb-4 sm:mx-0 sm:grid sm:grid-cols-3 sm:overflow-visible sm:px-0 lg:grid-cols-6">
                    @foreach ($mobileShots as $index => $shot)
                        <li class="w-[44%] shrink-0 snap-start sm:w-auto">
                            <figure>
                                <button type="button" class="group block w-full text-left" @click="open({{ count($desktopShots) + $index }})" aria-label="{{ __('portfolio.gallery.open') }}: {{ $shot['caption'] ?? $project->name }}">
                                    <x-phone-frame :src="$shot['url']" :alt="$altFor($shot)" class="transition duration-300 group-hover:-translate-y-1" />
                                </button>
                                @if ($shot['caption'])
                                    <figcaption class="mt-3 text-center text-xs leading-snug text-muted">{{ $shot['caption'] }}</figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Lightbox ------------------------------------------------------ --}}
        <div
            x-cloak
            x-show="isOpen"
            x-transition.opacity
            class="fixed inset-0 z-[60] flex flex-col bg-ink/95 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
            :aria-label="current.caption ?? @js($project->name)"
            @click.self="close()"
        >
            <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6">
                <p class="min-w-0 truncate text-sm text-muted">
                    <span x-text="index + 1"></span> / {{ count($lightbox) }}
                    <span class="text-fg" x-show="current.caption" x-text="' · ' + current.caption"></span>
                </p>
                <button type="button" class="btn-ghost" @click="close()" x-ref="closeButton">
                    {{ __('portfolio.gallery.close') }}
                    <x-glyph name="close" />
                </button>
            </div>

            <div class="relative flex min-h-0 flex-1 items-center justify-center px-4 pb-6 sm:px-16" @click.self="close()">
                <img
                    :src="current.url"
                    :alt="current.caption ?? ''"
                    class="max-h-full max-w-full rounded-xl border border-line object-contain shadow-2xl shadow-black/60"
                    :class="current.mobile ? 'w-auto' : 'w-full max-w-6xl'"
                >

                @if (count($lightbox) > 1)
                    <button type="button" class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full border border-line bg-surface/90 p-3 text-fg transition hover:bg-raised sm:left-4" @click="previous()" aria-label="{{ __('portfolio.gallery.previous') }}">
                        <x-glyph name="arrow-left" />
                    </button>
                    <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-line bg-surface/90 p-3 text-fg transition hover:bg-raised sm:right-4" @click="next()" aria-label="{{ __('portfolio.gallery.next') }}">
                        <x-glyph name="arrow-right" />
                    </button>
                @endif
            </div>
        </div>
    </section>
@endif
