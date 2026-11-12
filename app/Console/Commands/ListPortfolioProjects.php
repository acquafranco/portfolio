<?php

namespace App\Console\Commands;

use App\Portfolio\ProjectRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('portfolio:projects')]
#[Description('List the portfolio projects and validate every file in config/projects/')]
class ListPortfolioProjects extends Command
{
    public function handle(ProjectRepository $repository): int
    {
        $locale = config('portfolio.default_locale');

        $this->table(
            ['Order', 'Slug', 'Name', 'Status', 'Featured', 'URL'],
            $repository->all($locale)->map(fn ($project): array => [
                $project->order,
                $project->slug,
                $project->name,
                $project->status->value,
                $project->featured ? 'yes' : 'no',
                $project->url ?? '—',
            ])->all(),
        );

        $errors = $repository->validationErrors();

        if ($errors === []) {
            $this->components->info('All project files are valid.');

            return self::SUCCESS;
        }

        foreach ($errors as $slug => $messages) {
            $this->components->error("config/projects/{$slug}.php");
            $this->components->bulletList($messages);
        }

        return self::FAILURE;
    }
}
