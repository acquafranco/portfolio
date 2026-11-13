<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\Analytics;
use App\Portfolio\AnalyticsReport;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

/**
 * Ranked lists: clicks, pages, traffic sources, countries, devices, languages.
 */
class AnalyticsBreakdown extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.analytics-breakdown';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $report = new AnalyticsReport(Analytics::periodFrom($this->pageFilters));

        return [
            'groups' => [
                ['title' => 'Acciones', 'description' => 'Clics en lo que importa', 'rows' => $report->clicks()],
                ['title' => 'Páginas más vistas', 'description' => null, 'rows' => $report->topPages()],
                ['title' => 'De dónde llegan', 'description' => 'Sitio que los mandó', 'rows' => $report->topReferrers()],
                ['title' => 'Países', 'description' => null, 'rows' => $report->countries()],
                ['title' => 'Dispositivos', 'description' => null, 'rows' => $report->devices()],
                ['title' => 'Idioma del sitio', 'description' => null, 'rows' => $report->languages()],
            ],
        ];
    }
}
