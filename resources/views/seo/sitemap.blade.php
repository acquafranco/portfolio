{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($pages as $page)
@foreach ($locales as $locale)
    <url>
        <loc>{{ route($page['route'], [...$page['parameters'], 'locale' => $locale]) }}</loc>
@foreach ($locales as $alternate)
        <xhtml:link rel="alternate" hreflang="{{ $alternate }}" href="{{ route($page['route'], [...$page['parameters'], 'locale' => $alternate]) }}"/>
@endforeach
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ route($page['route'], [...$page['parameters'], 'locale' => config('portfolio.default_locale')]) }}"/>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@endforeach
</urlset>
