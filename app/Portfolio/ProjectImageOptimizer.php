<?php

namespace App\Portfolio;

use GdImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Turns uploaded screenshots and logos into small WebP files (GD).
 * Covers: 1200×630 + 720×378, cropped from the top (screenshots keep the
 * header visible). Logos: 160×160. Gallery screenshots keep their aspect
 * ratio: desktop 1600 + 800 px wide, mobile 600 px wide (very tall captures
 * are cut from the top). If GD/WebP is unavailable the original upload is
 * kept as is.
 */
class ProjectImageOptimizer
{
    private const SUFFIX = '.opt.webp';

    public function isOptimized(string $path): bool
    {
        return str_ends_with($path, self::SUFFIX);
    }

    /**
     * @return array{0: string, 1: string}|null [cover, cover_small]
     */
    public function cover(string $path): ?array
    {
        $source = $this->load($path);

        if ($source === null) {
            return null;
        }

        $base = 'projects/'.Str::random(24);
        $large = $this->store($this->fit($source, 1200, 630), $base.'-1200'.self::SUFFIX, 82);
        $small = $this->store($this->fit($source, 720, 378), $base.'-720'.self::SUFFIX, 78);

        if ($large === null || $small === null) {
            return null;
        }

        Storage::disk('public')->delete($path);

        return [$large, $small];
    }

    /**
     * @return array{0: string, 1: string|null}|null [image, image_small]
     */
    public function screenshot(string $path, ScreenshotDevice $device): ?array
    {
        $source = $this->load($path);

        if ($source === null) {
            return null;
        }

        $base = 'projects/'.Str::random(24);

        if ($device === ScreenshotDevice::Mobile) {
            $image = $this->store($this->scale($source, 600, maxRatio: 2.4), $base.'-600'.self::SUFFIX, 80);
            $small = null;
        } else {
            $image = $this->store($this->scale($source, 1600, maxRatio: 0.75), $base.'-1600'.self::SUFFIX, 80);
            $small = $this->store($this->scale($source, 800, maxRatio: 0.75), $base.'-800'.self::SUFFIX, 78);
        }

        if ($image === null || ($device === ScreenshotDevice::Desktop && $small === null)) {
            return null;
        }

        Storage::disk('public')->delete($path);

        return [$image, $small];
    }

    public function logo(string $path): ?string
    {
        if (str_ends_with(strtolower($path), '.svg')) {
            return null; // Vector logos are already small and sharp.
        }

        $source = $this->load($path);

        if ($source === null) {
            return null;
        }

        $logo = $this->store($this->fit($source, 160, 160, fromTop: false), 'projects/'.Str::random(24).'-logo'.self::SUFFIX, 88);

        if ($logo !== null) {
            Storage::disk('public')->delete($path);
        }

        return $logo;
    }

    private function load(string $path): ?GdImage
    {
        if (! function_exists('imagewebp') || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $image = @imagecreatefromstring((string) Storage::disk('public')->get($path));

        if (! $image instanceof GdImage) {
            Log::warning("Project image [{$path}] could not be decoded; kept original.");

            return null;
        }

        imagepalettetotruecolor($image);

        return $image;
    }

    /**
     * Scale to cover $width×$height and crop (top-anchored by default).
     */
    private function fit(GdImage $source, int $width, int $height, bool $fromTop = true): GdImage
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $scale = max($width / $sourceWidth, $height / $sourceHeight);

        $cropWidth = (int) round($width / $scale);
        $cropHeight = (int) round($height / $scale);
        $x = (int) round(($sourceWidth - $cropWidth) / 2);
        $y = $fromTop ? 0 : (int) round(($sourceHeight - $cropHeight) / 2);

        $target = imagecreatetruecolor($width, $height);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagecopyresampled($target, $source, 0, 0, $x, $y, $width, $height, $cropWidth, $cropHeight);

        return $target;
    }

    /**
     * Scale to $width (never upscaling) keeping the aspect ratio; anything
     * taller than $width × $maxRatio is cut from the bottom.
     */
    private function scale(GdImage $source, int $width, float $maxRatio): GdImage
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $width = min($width, $sourceWidth);
        $scale = $width / $sourceWidth;
        $cropHeight = (int) min($sourceHeight, round($sourceWidth * $maxRatio));
        $height = max(1, (int) round($cropHeight * $scale));

        $target = imagecreatetruecolor($width, $height);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $cropHeight);

        return $target;
    }

    private function store(GdImage $image, string $path, int $quality): ?string
    {
        ob_start();
        $ok = imagewebp($image, null, $quality);
        $bytes = (string) ob_get_clean();

        return $ok && Storage::disk('public')->put($path, $bytes) ? $path : null;
    }
}
