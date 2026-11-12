@props(['period', 'title', 'description'])

<li class="group relative pb-10 pl-8 last:pb-0">
    <span class="absolute left-0 top-1.5 h-full w-px bg-line group-last:hidden" aria-hidden="true"></span>
    <span class="absolute -left-[4px] top-1.5 h-[9px] w-[9px] rounded-full border-2 border-ink bg-amber" aria-hidden="true"></span>
    <p class="font-mono text-xs uppercase tracking-wider text-subtle">{{ $period }}</p>
    <h3 class="mt-2 text-lg font-semibold tracking-tight text-fg">{{ $title }}</h3>
    <p class="mt-2 max-w-measure leading-relaxed text-muted">{{ $description }}</p>
</li>
