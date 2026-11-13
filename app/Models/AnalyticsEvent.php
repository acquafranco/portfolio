<?php

namespace App\Models;

use App\Portfolio\TrackedEvent;
use Database\Factories\AnalyticsEventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Carbon;

/**
 * One page view or tracked click on the public site.
 *
 * @property int $id
 * @property TrackedEvent $name
 * @property string $path
 * @property string|null $locale
 * @property string|null $referrer_host
 * @property string|null $country
 * @property string $device
 * @property string $visitor_hash
 * @property Carbon $created_at
 */
class AnalyticsEvent extends Model
{
    /** @use HasFactory<AnalyticsEventFactory> */
    use HasFactory, Prunable;

    public const UPDATED_AT = null;

    /** Events older than this are deleted by `php artisan model:prune`. */
    public const RETENTION_MONTHS = 13;

    protected $fillable = ['name', 'path', 'locale', 'referrer_host', 'country', 'device', 'visitor_hash', 'created_at'];

    protected function casts(): array
    {
        return [
            'name' => TrackedEvent::class,
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return Builder<AnalyticsEvent>
     */
    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<', now()->subMonths(self::RETENTION_MONTHS));
    }
}
