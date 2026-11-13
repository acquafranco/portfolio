<?php

namespace Database\Factories;

use App\Models\AnalyticsEvent;
use App\Portfolio\TrackedEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalyticsEvent>
 */
class AnalyticsEventFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => TrackedEvent::PageView,
            'path' => '/es',
            'locale' => 'es',
            'referrer_host' => null,
            'country' => 'AR',
            'device' => 'desktop',
            'visitor_hash' => fake()->regexify('[0-9a-f]{16}'),
            'created_at' => now(),
        ];
    }

    public function event(TrackedEvent $event): static
    {
        return $this->state(['name' => $event]);
    }

    public function byVisitor(string $visitorHash): static
    {
        return $this->state(['visitor_hash' => $visitorHash]);
    }
}
