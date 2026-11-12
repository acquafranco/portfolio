<?php

namespace App\Portfolio;

/**
 * A portfolio project already resolved for one locale, ready for the views.
 * Image properties are absolute URLs.
 */
final readonly class LocalizedProject
{
    /**
     * @param  list<array{label: string, url: string}>  $links
     * @param  list<string>  $stack
     * @param  list<string>  $features
     */
    public function __construct(
        public string $slug,
        public string $name,
        public int $order,
        public bool $featured,
        public ProjectStatus $status,
        public ?int $year,
        public ?string $url,
        public ?string $repository,
        public array $links,
        public string $accent,
        public ?string $logoUrl,
        public ?string $coverUrl,
        public ?string $coverSmallUrl,
        public array $stack,
        public string $category,
        public string $tagline,
        public string $summary,
        public string $problem,
        public string $solution,
        public array $features,
        public ?string $audience,
        public ?string $role,
    ) {}

    /**
     * Bare host of the live URL, e.g. "ascento.online".
     */
    public function domain(): ?string
    {
        if ($this->url === null) {
            return null;
        }

        $host = parse_url($this->url, PHP_URL_HOST);

        return is_string($host) ? preg_replace('/^www\./', '', $host) : null;
    }

    public function initials(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
