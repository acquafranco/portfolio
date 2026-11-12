<?php

namespace App\Http\Controllers;

use App\Portfolio\LocalizedProject;
use App\Portfolio\ProjectRepository;
use App\Portfolio\ProjectStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(private readonly ProjectRepository $projects) {}

    /**
     * "/" → "/{locale}" using the browser's Accept-Language when it matches a
     * supported language, otherwise the default one.
     */
    public function redirectToLocale(Request $request): RedirectResponse
    {
        $supported = array_keys(config('portfolio.locales'));
        $default = config('portfolio.default_locale');

        $locale = $request->headers->has('Accept-Language')
            ? $request->getPreferredLanguage([$default, ...array_diff($supported, [$default])])
            : $default;

        return redirect()
            ->route('home', ['locale' => in_array($locale, $supported, true) ? $locale : $default])
            ->setVary('Accept-Language');
    }

    /**
     * One-page home: hero, products, about, stack, process, experience, contact.
     */
    public function home(): View
    {
        $projects = $this->projects->all();

        return view('portfolio.home', [
            'projects' => $projects,
            'statusFilters' => $this->statusFilters($projects->all()),
            'skillGroups' => collect(config('portfolio.skills'))
                ->map(fn (array $group): array => [
                    'label' => __('portfolio.skills.groups.'.$group['group']),
                    'items' => $group['items'],
                ])
                ->all(),
            'experience' => collect(config('portfolio.experience'))
                ->map(fn (array $item): array => [
                    'period' => __('portfolio.experience.periods.'.$item['period_key']),
                    'title' => __('portfolio.experience.items.'.$item['key'].'.title'),
                    'description' => __('portfolio.experience.items.'.$item['key'].'.description'),
                ])
                ->all(),
        ]);
    }

    /**
     * Case-study page for a single project.
     */
    public function project(string $locale, string $slug): View
    {
        $project = $this->projects->find($slug) ?? abort(404);

        return view('portfolio.project', [
            'project' => $project,
            'next' => $this->projects->next($project),
        ]);
    }

    /**
     * Status filter chips for the projects grid: only statuses that actually
     * have projects, with their counts.
     *
     * @param  list<LocalizedProject>  $projects
     * @return list<array{value: string, label: string, count: int}>
     */
    private function statusFilters(array $projects): array
    {
        $counts = array_count_values(array_map(fn (LocalizedProject $project): string => $project->status->value, $projects));

        return array_values(array_map(
            fn (ProjectStatus $status): array => [
                'value' => $status->value,
                'label' => $status->label(),
                'count' => $counts[$status->value],
            ],
            array_filter(ProjectStatus::cases(), fn (ProjectStatus $status): bool => isset($counts[$status->value])),
        ));
    }
}
