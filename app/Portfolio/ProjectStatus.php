<?php

namespace App\Portfolio;

/**
 * Lifecycle stage of a portfolio project. The value is what goes in the
 * project's config file ('status' => 'production').
 */
enum ProjectStatus: string
{
    case Production = 'production';
    case Development = 'development';
    case Experimental = 'experimental';

    public function label(): string
    {
        return __('portfolio.status.'.$this->value);
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
