<?php

namespace App\Portfolio;

/**
 * Whether a project is one of my own products or work built for a client.
 * The value is what goes in the project's seed file ('kind' => 'client').
 */
enum ProjectKind: string
{
    case Product = 'product';
    case Client = 'client';

    public function label(): string
    {
        return __('portfolio.projects.kind_'.$this->value);
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
