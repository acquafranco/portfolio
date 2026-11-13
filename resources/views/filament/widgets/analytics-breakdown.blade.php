<x-filament-widgets::widget>
    {{-- Inline styles: Filament's prebuilt CSS does not include arbitrary utilities from app views. --}}
    <div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 18rem), 1fr));">
        @foreach ($groups as $group)
            @php $max = max([1, ...array_column($group['rows'], 'count')]); @endphp
            <x-filament::section :heading="$group['title']" :description="$group['description']">
                @if (array_sum(array_column($group['rows'], 'count')) === 0)
                    <p style="font-size: .875rem; opacity: .65;">Todavía sin datos en este período.</p>
                @else
                    <ul style="display: grid; gap: .65rem;">
                        @foreach ($group['rows'] as $row)
                            <li>
                                <div style="display: flex; justify-content: space-between; gap: .75rem; font-size: .875rem;">
                                    <span style="min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; opacity: .85;" title="{{ $row['label'] }}">{{ $row['label'] }}</span>
                                    <span style="flex-shrink: 0; font-weight: 600; font-variant-numeric: tabular-nums;">{{ number_format($row['count'], 0, ',', '.') }}</span>
                                </div>
                                <div style="margin-top: .3rem; height: .375rem; border-radius: 9999px; overflow: hidden; background: color-mix(in oklab, currentColor 10%, transparent);">
                                    <div style="height: 100%; border-radius: 9999px; background: var(--primary-500, #f97316); width: {{ round($row['count'] / $max * 100) }}%;"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-filament::section>
        @endforeach
    </div>
</x-filament-widgets::widget>
