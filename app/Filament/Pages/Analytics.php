<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnalyticsBreakdown;
use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\AnalyticsTrafficChart;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * First-party analytics of the public site (no external services).
 */
class Analytics extends Dashboard
{
    use HasFiltersForm;

    /** Periods offered in the filter, in days. */
    public const PERIODS = [7 => 'Últimos 7 días', 30 => 'Últimos 30 días', 90 => 'Últimos 90 días', 365 => 'Último año'];

    public const DEFAULT_PERIOD = 30;

    protected static string $routePath = 'analytics';

    protected static ?string $title = 'Analíticas';

    protected static ?string $navigationLabel = 'Analíticas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 0;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('days')
                ->label('Período')
                ->options(self::PERIODS)
                ->default(self::DEFAULT_PERIOD)
                ->selectablePlaceholder(false)
                ->native(false),
        ]);
    }

    public function getWidgets(): array
    {
        return [
            AnalyticsOverview::class,
            AnalyticsTrafficChart::class,
            AnalyticsBreakdown::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }

    /**
     * Selected period in days, limited to the offered options.
     *
     * @param  array<string, mixed>|null  $filters
     */
    public static function periodFrom(?array $filters): int
    {
        $days = (int) ($filters['days'] ?? self::DEFAULT_PERIOD);

        return array_key_exists($days, self::PERIODS) ? $days : self::DEFAULT_PERIOD;
    }
}
