<?php

namespace App\Console\Commands;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

#[Signature('portfolio:cv {--locale=* : Only these languages (default: every language in config portfolio.cv)} {--html : Only write the HTML preview to storage/app/cv, without Chrome}')]
#[Description('Build the CV PDFs in public/cv from lang/{locale}/cv.php using headless Chrome')]
class BuildPortfolioCv extends Command
{
    public function handle(): int
    {
        $paths = config('portfolio.cv');
        $locales = $this->option('locale') ?: array_keys($paths);
        $unknown = array_diff($locales, array_keys($paths));

        if ($unknown !== []) {
            $this->components->error('No CV configured for: '.implode(', ', $unknown).' (config/portfolio.php → cv).');

            return self::FAILURE;
        }

        $chrome = $this->option('html') ? null : $this->chromeBinary();

        if (! $this->option('html') && $chrome === null) {
            $this->components->error('Google Chrome not found. Set CV_CHROME_PATH in .env or use --html.');

            return self::FAILURE;
        }

        File::ensureDirectoryExists(storage_path('app/cv'));

        foreach ($locales as $locale) {
            $html = storage_path("app/cv/cv-{$locale}.html");
            File::put($html, $this->render($locale));

            if ($chrome === null) {
                $this->components->info("HTML preview: {$html}");

                continue;
            }

            // Printed to a temporary file first: the published PDF is only replaced when Chrome succeeds.
            $draft = storage_path("app/cv/cv-{$locale}.pdf");
            File::delete($draft);

            $result = Process::timeout(60)->run([
                $chrome, '--headless=new', '--disable-gpu', '--no-pdf-header-footer',
                '--user-data-dir='.storage_path('app/cv/chrome-profile'),
                '--print-to-pdf='.$draft, 'file://'.$html,
            ]);

            if ($result->failed() || ! is_file($draft) || filesize($draft) === 0) {
                $this->components->error("Chrome could not create {$paths[$locale]}: ".trim($result->errorOutput()));

                return self::FAILURE;
            }

            File::ensureDirectoryExists(dirname(public_path($paths[$locale])));
            File::move($draft, public_path($paths[$locale]));

            $this->components->info("CV ({$locale}) → public/{$paths[$locale]}");
        }

        return self::SUCCESS;
    }

    public function render(string $locale): string
    {
        $previousLocale = app()->getLocale();
        app()->setLocale($locale);

        try {
            $portfolioUrl = rtrim(config('portfolio.public_url'), '/').'/'.$locale;
            $whatsappUrl = config('portfolio.links.whatsapp').'?text='.rawurlencode(__('cv.whatsapp_message'));

            return view('cv.document', [
                'portfolioUrl' => $portfolioUrl,
                'qrWhatsapp' => $this->qrSvg($whatsappUrl),
                'qrPortfolio' => $this->qrSvg($portfolioUrl),
            ])->render();
        } finally {
            app()->setLocale($previousLocale);
        }
    }

    private function qrSvg(string $data): string
    {
        $options = new QROptions([
            'outputType' => 'svg',
            'outputBase64' => false,
            'addQuietzone' => false,
            'svgUseFillAttributes' => true,
            'drawLightModules' => false,
        ]);

        return (string) preg_replace('/^<\?xml[^>]*>\s*/', '', (new QRCode($options))->render($data));
    }

    private function chromeBinary(): ?string
    {
        $candidates = array_filter([
            config('portfolio.cv_chrome_path'),
            '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
            '/usr/bin/google-chrome',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate) && is_executable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
