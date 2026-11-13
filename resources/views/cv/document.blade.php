{{-- Printable CV (A4). Rendered to PDF by `php artisan portfolio:cv`; content in lang/{locale}/cv.php. --}}
@php
    $links = config('portfolio.links');
    $phone = preg_replace('/^(\d{2})(\d)(\d{2})(\d{4})(\d{4})$/', '+$1 $2 $3 $4-$5', ltrim(parse_url($links['whatsapp'], PHP_URL_PATH), '/'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('portfolio.name') }} — CV</title>
    <style>
        @page{size:A4;margin:14mm 15mm}
        *{box-sizing:border-box}body{margin:0;font-family:-apple-system,"Helvetica Neue",Helvetica,Arial,sans-serif;color:#1d1d1f;font-size:9.6pt;line-height:1.42}
        .top{display:flex;justify-content:space-between;align-items:flex-start;gap:16px}
        h1{font-size:23pt;margin:0;letter-spacing:-.02em;font-weight:700}
        .role{text-align:left;margin:3px 0 0;color:#444;font-size:10.5pt}
        .contact{margin:8px 0 0;color:#444;font-size:9pt;display:flex;flex-wrap:wrap;gap:4px 14px}
        .qrs{display:flex;gap:10px;text-align:center;font-size:6.8pt;color:#555;line-height:1.2}
        .qrs div{width:64px}.qrs svg{width:58px;height:58px;display:block;margin:0 auto 3px}
        .rule{border:0;border-top:2px solid #E8622C;margin:10px 0 4px}
        h2{color:#E8622C;font-size:8.6pt;letter-spacing:.08em;text-transform:uppercase;margin:13px 0 5px;padding-bottom:3px;border-bottom:1px solid #e6e6e6}
        p{margin:0;text-align:justify}ul{margin:0;padding-left:15px}li{margin:1.5px 0}
        table{border-collapse:collapse}td{padding:1.5px 12px 1.5px 0;vertical-align:top}td:first-child{font-weight:700;white-space:nowrap}
        .job{margin-top:7px}.jh{display:flex;justify-content:space-between;gap:10px;margin-bottom:2px}.jh span{color:#555}.date{color:#555;white-space:nowrap}
        .langs{display:flex;gap:60px}
    </style>
</head>
<body>
    <div class="top">
        <div>
            <h1>{{ config('portfolio.name') }}</h1>
            <p class="role">{{ __('cv.role') }}</p>
            <div class="contact">
                <span>{{ __('cv.location') }}</span>
                <span>{{ $links['email'] }}</span>
                <span>{{ $phone }}</span>
                <span>{{ parse_url($portfolioUrl, PHP_URL_HOST) }}</span>
                <span>{{ preg_replace('#^https?://#', '', $links['github']) }}</span>
            </div>
        </div>
        <div class="qrs">
            <div>{!! $qrWhatsapp !!}{{ __('cv.qr_whatsapp') }}</div>
            <div>{!! $qrPortfolio !!}{{ __('cv.qr_portfolio') }}</div>
        </div>
    </div>
    <hr class="rule">

    <h2>{{ __('cv.headings.profile') }}</h2>
    <p>{!! __('cv.profile', ['years' => config('portfolio.years_programming')]) !!}</p>

    <h2>{{ __('cv.headings.services') }}</h2>
    <ul>
        @foreach (__('cv.services') as $service)
            <li>{{ $service }}</li>
        @endforeach
    </ul>

    <h2>{{ __('cv.headings.skills') }}</h2>
    <table>
        @foreach (__('cv.skills') as $group => $skills)
            <tr><td>{{ $group }}</td><td>{{ $skills }}</td></tr>
        @endforeach
    </table>

    <h2>{{ __('cv.headings.experience') }}</h2>
    @foreach (__('cv.jobs') as $job)
        <div class="job">
            <div class="jh">
                <div><b>{{ $job['title'] }}</b> <span>— {{ $job['subtitle'] }}</span></div>
                <div class="date">{{ $job['period'] }}</div>
            </div>
            <ul>
                @foreach ($job['items'] as $item)
                    <li>{!! $item !!}</li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <h2>{{ __('cv.headings.education') }}</h2>
    <p>{!! __('cv.education') !!}</p>

    <h2>{{ __('cv.headings.languages') }}</h2>
    <div class="langs">
        @foreach (__('cv.languages') as $language => $level)
            <span><b>{{ $language }}</b> — {{ $level }}</span>
        @endforeach
    </div>
</body>
</html>
