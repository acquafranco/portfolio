<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Portfolio\TrackedEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AnalyticsController extends Controller
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|facebookexternalhit|whatsapp|telegram|preview|headless|lighthouse|pingdom|curl|wget|python|httpclient|go-http|java\//i';

    /**
     * Receives a page view or click sent with navigator.sendBeacon().
     * Always answers 204 so the browser never retries or shows errors.
     */
    public function store(Request $request): Response
    {
        $response = response()->noContent()->header('Cache-Control', 'no-store');
        $userAgent = (string) $request->userAgent();

        if ($userAgent === '' || preg_match(self::BOT_PATTERN, $userAgent)) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'n' => ['required', Rule::in(TrackedEvent::values())],
            'p' => ['required', 'string', 'max:2000', 'starts_with:/'],
            'r' => ['nullable', 'string', 'max:2000'],
            'w' => ['nullable', 'integer', 'between:0,10000'],
        ]);

        if ($validator->fails()) {
            return $response->setStatusCode(422);
        }

        $path = mb_substr((string) parse_url($request->input('p'), PHP_URL_PATH), 0, 255) ?: '/';

        AnalyticsEvent::query()->create([
            'name' => $request->input('n'),
            'path' => $path,
            'locale' => $this->localeFromPath($path),
            'referrer_host' => $this->externalReferrerHost($request),
            'country' => $this->country($request),
            'device' => $this->device($request->integer('w'), $userAgent),
            'visitor_hash' => $this->visitorHash($request, $userAgent),
        ]);

        return $response;
    }

    private function localeFromPath(string $path): ?string
    {
        $segment = explode('/', trim($path, '/'))[0];

        return array_key_exists($segment, config('portfolio.locales')) ? $segment : null;
    }

    /**
     * Host of the page that sent the visitor, ignoring internal navigation.
     */
    private function externalReferrerHost(Request $request): ?string
    {
        $host = parse_url((string) $request->input('r'), PHP_URL_HOST);

        if (! is_string($host) || $host === '' || strcasecmp($host, $request->getHost()) === 0) {
            return null;
        }

        return mb_substr(preg_replace('/^www\./i', '', mb_strtolower($host)), 0, 255);
    }

    /**
     * Country from Cloudflare's CF-IPCountry header (free, no lookup needed).
     */
    private function country(Request $request): ?string
    {
        $country = strtoupper((string) $request->header('CF-IPCountry'));

        return preg_match('/^[A-Z]{2}$/', $country) && ! in_array($country, ['XX', 'T1'], true) ? $country : null;
    }

    private function device(int $viewportWidth, string $userAgent): string
    {
        if ($viewportWidth === 0) {
            return preg_match('/Mobi|Android|iPhone/i', $userAgent) ? 'mobile' : 'desktop';
        }

        return match (true) {
            $viewportWidth < 768 => 'mobile',
            $viewportWidth < 1024 => 'tablet',
            default => 'desktop',
        };
    }

    /**
     * Anonymous id that changes every day: counts unique visitors without
     * storing the IP or setting cookies.
     */
    private function visitorHash(Request $request, string $userAgent): string
    {
        $seed = $request->ip().'|'.$userAgent.'|'.now()->toDateString();

        return substr(hash_hmac('sha256', $seed, (string) config('app.key')), 0, 16);
    }
}
