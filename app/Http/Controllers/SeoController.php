<?php

namespace App\Http\Controllers;

use App\Portfolio\ProjectRepository;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * sitemap.xml generated from the configured locales and projects, so it
     * always matches the real domain (APP_URL) and the current project list.
     */
    public function sitemap(ProjectRepository $projects): Response
    {
        $locales = array_keys(config('portfolio.locales'));

        $pages = [['route' => 'home', 'parameters' => [], 'priority' => '1.0']];

        foreach ($projects->all(config('portfolio.default_locale')) as $project) {
            $pages[] = ['route' => 'projects.show', 'parameters' => ['slug' => $project->slug], 'priority' => '0.8'];
        }

        return response()
            ->view('seo.sitemap', ['pages' => $pages, 'locales' => $locales])
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $lines = ['User-agent: *', 'Allow: /', '', 'Sitemap: '.route('sitemap')];

        return response(implode("\n", $lines)."\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
