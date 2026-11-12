<?php

namespace App\Portfolio;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/**
 * Builds the URL of the current page in another language, used by the
 * language switcher and the hreflang alternates.
 */
class Localization
{
    /**
     * @return array<string, array{label: string, og: string}>
     */
    public static function locales(): array
    {
        return config('portfolio.locales');
    }

    /**
     * Use the first URL segment as locale when it is a supported one. For
     * pages rendered outside the localized route group (e.g. error pages).
     */
    public static function detectFromPath(): void
    {
        $segment = request()->segment(1);
        $locale = is_string($segment) && array_key_exists($segment, self::locales())
            ? $segment
            : config('portfolio.default_locale');

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);
    }

    public static function current(): string
    {
        return app()->getLocale();
    }

    /**
     * Whether the current route is localized (has a {locale} parameter).
     */
    public static function onLocalizedRoute(): bool
    {
        $route = Route::current();

        return $route !== null && $route->getName() !== null && $route->hasParameter('locale');
    }

    public static function urlFor(string $locale): string
    {
        $route = Route::current();

        if (! self::onLocalizedRoute()) {
            return route('home', ['locale' => $locale]);
        }

        return route($route->getName(), array_merge($route->parameters(), ['locale' => $locale]));
    }

    public static function ogLocale(?string $locale = null): string
    {
        $locale ??= self::current();

        return self::locales()[$locale]['og'] ?? $locale;
    }

    /**
     * wa.me link with a ready-to-send greeting in the current language.
     */
    public static function whatsappUrl(): string
    {
        return config('portfolio.links.whatsapp').'?text='.rawurlencode(__('portfolio.contact.whatsapp_message'));
    }

    /**
     * Public path of the CV for the current language, falling back to the
     * default language, or null when no file exists.
     */
    public static function cvPath(): ?string
    {
        $cv = config('portfolio.cv', []);
        $path = $cv[self::current()] ?? $cv[config('portfolio.default_locale')] ?? null;

        return $path !== null && is_file(public_path($path)) ? $path : null;
    }
}
