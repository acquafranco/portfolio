<?php

namespace App\Portfolio;

use App\Models\AnalyticsEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Aggregated numbers for the admin analytics page, for the last N days
 * (today included) compared with the N days before.
 */
class AnalyticsReport
{
    public readonly Carbon $from;

    public readonly Carbon $previousFrom;

    public function __construct(public readonly int $days = 30)
    {
        $this->from = now()->subDays($days - 1)->startOfDay();
        $this->previousFrom = $this->from->copy()->subDays($days);
    }

    /**
     * @return array{pageviews: int, visitors: int, contacts: int, cv_downloads: int, contact_rate: float, previous: array{pageviews: int, visitors: int, contacts: int, cv_downloads: int}}
     */
    public function summary(): array
    {
        $current = $this->totals($this->from, null);
        $current['contact_rate'] = $current['visitors'] > 0 ? round($current['contacts'] / $current['visitors'] * 100, 1) : 0.0;
        $current['previous'] = $this->totals($this->previousFrom, $this->from);

        return $current;
    }

    /**
     * One entry per day, including days without traffic.
     *
     * @return list<array{date: string, pageviews: int, visitors: int}>
     */
    public function daily(): array
    {
        $rows = $this->pageViews()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as pageviews, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $days = [];

        for ($date = $this->from->copy(); $date->lte(now()); $date->addDay()) {
            $row = $rows->get($date->toDateString());
            $days[] = [
                'date' => $date->toDateString(),
                'pageviews' => (int) ($row->pageviews ?? 0),
                'visitors' => (int) ($row->visitors ?? 0),
            ];
        }

        return $days;
    }

    /**
     * @return list<array{label: string, count: int}>
     */
    public function topPages(int $limit = 8): array
    {
        return $this->ranked($this->pageViews(), 'path', $limit);
    }

    /**
     * @return list<array{label: string, count: int}>
     */
    public function topReferrers(int $limit = 8): array
    {
        return $this->ranked($this->pageViews(), 'referrer_host', $limit, 'Directo / sin origen');
    }

    /**
     * @return list<array{label: string, count: int}>
     */
    public function countries(int $limit = 8): array
    {
        return $this->ranked($this->pageViews(), 'country', $limit, 'Desconocido');
    }

    /**
     * @return list<array{label: string, count: int}>
     */
    public function devices(): array
    {
        $labels = ['mobile' => 'Celular', 'tablet' => 'Tablet', 'desktop' => 'Computadora'];

        return array_map(
            fn (array $row): array => ['label' => $labels[$row['label']] ?? $row['label'], 'count' => $row['count']],
            $this->ranked($this->pageViews(), 'device', 3),
        );
    }

    /**
     * @return list<array{label: string, count: int}>
     */
    public function languages(): array
    {
        $labels = collect(config('portfolio.locales'))->map(fn (array $locale): string => $locale['label'])->all();

        return array_map(
            fn (array $row): array => ['label' => $labels[$row['label']] ?? $row['label'], 'count' => $row['count']],
            $this->ranked($this->pageViews(), 'locale', 5, 'Otro'),
        );
    }

    /**
     * Click counts for every tracked event except page views.
     *
     * @return list<array{label: string, count: int}>
     */
    public function clicks(): array
    {
        $counts = $this->inPeriod()
            ->where('name', '!=', TrackedEvent::PageView->value)
            ->select('name', DB::raw('COUNT(*) as total'))
            ->groupBy('name')
            ->pluck('total', 'name');

        return collect(TrackedEvent::cases())
            ->reject(fn (TrackedEvent $event): bool => $event === TrackedEvent::PageView)
            ->map(fn (TrackedEvent $event): array => ['label' => $event->label(), 'count' => (int) ($counts[$event->value] ?? 0)])
            ->sortByDesc('count')
            ->values()
            ->all();
    }

    /**
     * @return array{pageviews: int, visitors: int, contacts: int, cv_downloads: int}
     */
    private function totals(Carbon $from, ?Carbon $until): array
    {
        $query = fn (): Builder => AnalyticsEvent::query()
            ->where('created_at', '>=', $from)
            ->when($until, fn (Builder $query) => $query->where('created_at', '<', $until));

        return [
            'pageviews' => $query()->where('name', TrackedEvent::PageView->value)->count(),
            'visitors' => (int) $query()->where('name', TrackedEvent::PageView->value)->distinct()->count('visitor_hash'),
            'contacts' => (int) $query()->whereIn('name', array_map(fn (TrackedEvent $event): string => $event->value, TrackedEvent::contactIntents()))->distinct()->count('visitor_hash'),
            'cv_downloads' => $query()->where('name', TrackedEvent::CvDownload->value)->count(),
        ];
    }

    /**
     * @return Builder<AnalyticsEvent>
     */
    private function inPeriod(): Builder
    {
        return AnalyticsEvent::query()->where('created_at', '>=', $this->from);
    }

    /**
     * @return Builder<AnalyticsEvent>
     */
    private function pageViews(): Builder
    {
        return $this->inPeriod()->where('name', TrackedEvent::PageView->value);
    }

    /**
     * @param  Builder<AnalyticsEvent>  $query
     * @return list<array{label: string, count: int}>
     */
    private function ranked(Builder $query, string $column, int $limit, ?string $nullLabel = null): array
    {
        return $query
            ->select($column, DB::raw('COUNT(*) as total'))
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(fn (AnalyticsEvent $row): array => ['label' => (string) ($row->{$column} ?? $nullLabel), 'count' => (int) $row->getAttribute('total')])
            ->all();
    }
}
