@props(['project'])

<article class="grid md:grid-cols-5 gap-8 md:gap-10 py-12 border-t border-steel">
    <div class="md:col-span-2">
        <div class="corner-frame overflow-hidden">
            <img
                src="{{ asset($project['image']) }}"
                alt="{{ $project['name'] }}"
                loading="lazy"
                class="w-full h-auto block"
            >
        </div>
    </div>

    <div class="md:col-span-3">
        <div class="flex items-start justify-between gap-4 flex-wrap mb-1">
            <h3 class="font-display text-xl font-semibold text-paper">{{ $project['name'] }}</h3>
            <span class="text-xs text-slate border border-steel px-2 py-1 whitespace-nowrap">
                {{ $project['statusLabel'] }}
            </span>
        </div>

        <p class="text-xs text-slate mb-5">{{ $project['category'] }}</p>

        <p class="text-paper/90 leading-relaxed mb-6 max-w-measure">{{ $project['description'] }}</p>

        <dl class="grid sm:grid-cols-2 gap-6 mb-6 text-sm">
            <div>
                <dt class="text-slate mb-1">{{ $project['labels']['problem'] }}</dt>
                <dd class="text-paper/90 leading-relaxed">{{ $project['problem'] }}</dd>
            </div>
            <div>
                <dt class="text-slate mb-1">{{ $project['labels']['solution'] }}</dt>
                <dd class="text-paper/90 leading-relaxed">{{ $project['solution'] }}</dd>
            </div>
        </dl>

        @if (!empty($project['explorationNote']))
            <p class="text-sm text-slate italic mb-6 max-w-measure">{{ $project['explorationNote'] }}</p>
        @endif

        <div class="mb-6">
            <p class="text-slate text-xs mb-2">{{ $project['labels']['stack'] }}</p>
            <ul class="flex flex-wrap gap-2 text-xs">
                @foreach ($project['stack'] as $tech)
                    <li class="border border-steel px-2 py-1 text-slate">{{ $tech }}</li>
                @endforeach
            </ul>
        </div>

        @if ($project['live'] || $project['github'])
            <div class="flex flex-wrap gap-4">
                @if ($project['live'])
                    <x-button variant="secondary" :href="$project['live']" external>
                        {{ $project['labels']['visit'] }}
                    </x-button>
                @endif
                @if ($project['github'])
                    <x-button variant="secondary" :href="$project['github']" external>
                        {{ $project['labels']['code'] }}
                    </x-button>
                @endif
            </div>
        @endif
    </div>
</article>
