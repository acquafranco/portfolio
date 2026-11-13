<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\Analytics;
use App\Portfolio\AnalyticsReport;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class AnalyticsTrafficChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Visitas por día';

    protected ?string $maxHeight = '280px';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $daily = (new AnalyticsReport(Analytics::periodFrom($this->pageFilters)))->daily();

        return [
            'datasets' => [
                [
                    'label' => 'Visitantes',
                    'data' => array_column($daily, 'visitors'),
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Páginas vistas',
                    'data' => array_column($daily, 'pageviews'),
                    'borderColor' => '#94a3b8',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                ],
            ],
            'labels' => array_map(fn (array $day): string => Carbon::parse($day['date'])->format('d/m'), $daily),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
            'plugins' => ['legend' => ['display' => true]],
        ];
    }
}
