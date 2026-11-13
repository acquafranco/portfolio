<?php

namespace App\Portfolio;

/**
 * Where a gallery screenshot was taken: shown in a browser frame (desktop)
 * or in a phone frame (mobile). The value is what goes in the seed files.
 */
enum ScreenshotDevice: string
{
    case Desktop = 'desktop';
    case Mobile = 'mobile';

    public function label(): string
    {
        return __('portfolio.gallery.'.$this->value);
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
