@props(['period', 'title', 'description'])

<div class="relative pl-8 pb-12 border-l border-steel last:pb-0 last:border-transparent">
    <span class="absolute -left-[5px] top-1 w-[9px] h-[9px] bg-amber"></span>
    <p class="text-xs text-slate mb-2">{{ $period }}</p>
    <h3 class="font-display text-lg font-semibold text-paper mb-2">{{ $title }}</h3>
    <p class="text-paper/90 leading-relaxed max-w-measure">{{ $description }}</p>
</div>
