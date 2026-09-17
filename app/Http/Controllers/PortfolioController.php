<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Página principal del portfolio (one-page): hero, sobre mí, qué hago,
     * proyectos, experiencia, cómo trabajo y contacto.
     *
     * Los datos "estructurales" (links, stack, orden, slugs) viven en
     * config/portfolio.php. Los textos por idioma viven en
     * lang/{locale}/portfolio.php. Acá simplemente los combinamos.
     */
    public function home(): View
    {
        return view('portfolio.home', [
            'profile' => config('portfolio'),
            'skillGroups' => $this->skillGroups(),
            'projects' => $this->projects(),
            'experience' => $this->experience(),
        ]);
    }

    private function skillGroups(): array
    {
        return collect(config('portfolio.skills'))
            ->map(fn (array $group) => [
                'label' => __('portfolio.skills.groups.'.$group['group']),
                'items' => $group['items'],
            ])
            ->all();
    }

    private function projects(): array
    {
        $labels = [
            'problem' => __('portfolio.projects.labels.problem'),
            'solution' => __('portfolio.projects.labels.solution'),
            'stack' => __('portfolio.projects.labels.stack'),
            'visit' => __('portfolio.projects.labels.visit'),
            'code' => __('portfolio.projects.labels.code'),
        ];

        return collect(config('portfolio.projects'))
            ->map(function (array $project) use ($labels) {
                $translated = __('portfolio.projects.items.'.$project['slug']);

                return [
                    'slug' => $project['slug'],
                    'name' => $project['name'],
                    'statusLabel' => __('portfolio.projects.status.'.$project['status']),
                    'category' => $translated['category'],
                    'description' => $translated['description'],
                    'problem' => $translated['problem'],
                    'solution' => $translated['solution'],
                    'explorationNote' => $translated['exploration_note'] ?? null,
                    'stack' => $project['stack'],
                    'image' => $project['image'],
                    'live' => $project['links']['live'] === 'PLACEHOLDER' ? null : $project['links']['live'],
                    'github' => $project['links']['github'],
                    'labels' => $labels,
                ];
            })
            ->all();
    }

    private function experience(): array
    {
        return collect(config('portfolio.experience'))
            ->map(fn (array $item) => [
                'period' => __('portfolio.experience.periods.'.$item['period_key']),
                'title' => __('portfolio.experience.items.'.$item['key'].'.title'),
                'description' => __('portfolio.experience.items.'.$item['key'].'.description'),
            ])
            ->all();
    }
}
