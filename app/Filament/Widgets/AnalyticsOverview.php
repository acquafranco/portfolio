<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\Analytics;
use App\Portfolio\AnalyticsReport;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected ?string $pollingInterval = null;

    protected function getColumns(): int
    {
        return 4;
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $report = new AnalyticsReport(Analytics::periodFrom($this->pageFilters));
        $summary = $report->summary();
        $daily = $report->daily();

        return [
            $this->stat('Visitantes', $summary['visitors'], $summary['previous']['visitors'])
                ->chart(array_column($daily, 'visitors'))
                ->icon(Heroicon::OutlinedUsers),
            $this->stat('Páginas vistas', $summary['pageviews'], $summary['previous']['pageviews'])
                ->chart(array_column($daily, 'pageviews'))
                ->icon(Heroicon::OutlinedEye),
            $this->stat('Personas que te contactaron', $summary['contacts'], $summary['previous']['contacts'])
                ->icon(Heroicon::OutlinedChatBubbleLeftRight),
            $this->stat('Descargas del CV', $summary['cv_downloads'], $summary['previous']['cv_downloads'])
                ->icon(Heroicon::OutlinedArrowDownTray),
            Stat::make('Tasa de contacto', number_format($summary['contact_rate'], 1, ',', '.').' %')
                ->description('Visitantes que tocaron WhatsApp o el email')
                ->icon(Heroicon::OutlinedCursorArrowRays),
        ];
    }

    private function stat(string $label, int $value, int $previous): Stat
    {
        $stat = Stat::make($label, number_format($value, 0, ',', '.'));

        if ($previous === 0) {
            return $stat->description($value > 0 ? 'Sin datos del período anterior' : 'Todavía sin datos');
        }

        $change = (int) round(($value - $previous) / $previous * 100);

        return $stat
            ->description(($change >= 0 ? '+' : '').$change.' % vs. período anterior')
            ->descriptionIcon($change >= 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
            ->color($change >= 0 ? 'success' : 'danger');
    }
}
