<?php

namespace App\Console\Commands;

use App\Portfolio\ProjectRepository;
use App\Portfolio\ProjectStatus;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

#[Signature('portfolio:make-project
    {slug : Lowercase slug, used as file name and URL (e.g. "my-app")}
    {--name= : Public name of the project}
    {--status=development : production, development or experimental}
    {--url= : Live URL (https://...)}
    {--featured : Show it as a featured product on the home page}')]
#[Description('Create config/projects/{slug}.php with every field ready to fill in')]
class MakePortfolioProject extends Command
{
    public function handle(Filesystem $files, ProjectRepository $repository): int
    {
        $slug = (string) $this->argument('slug');
        $status = (string) $this->option('status');
        $url = $this->option('url');

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $this->components->error('The slug must be lowercase letters, numbers and dashes (e.g. "my-app").');

            return self::FAILURE;
        }

        if (ProjectStatus::tryFrom($status) === null) {
            $this->components->error('Invalid status. Use one of: '.implode(', ', ProjectStatus::values()).'.');

            return self::FAILURE;
        }

        if ($url !== null && filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->components->error('The --url option must be a full URL (https://...).');

            return self::FAILURE;
        }

        $path = config_path("projects/{$slug}.php");

        if ($files->exists($path)) {
            $this->components->error("config/projects/{$slug}.php already exists.");

            return self::FAILURE;
        }

        $nextOrder = (int) collect($repository->definitions())->max('order') + 10;

        $files->ensureDirectoryExists(dirname($path));
        $files->put($path, $this->stub([
            '{{ name }}' => var_export($this->option('name') ?: str($slug)->headline()->toString(), true),
            '{{ order }}' => (string) $nextOrder,
            '{{ featured }}' => $this->option('featured') ? 'true' : 'false',
            '{{ status }}' => var_export($status, true),
            '{{ year }}' => now()->format('Y'),
            '{{ url }}' => $url === null ? 'null' : var_export($url, true),
            '{{ slug }}' => $slug,
        ]));

        $files->ensureDirectoryExists(public_path("images/projects/{$slug}"));

        $this->components->info("Created config/projects/{$slug}.php");
        $this->components->bulletList([
            'Fill in the texts for each language under "content".',
            "Add images to public/images/projects/{$slug}/ (cover.webp 1200×630, cover-720.webp, logo) or set them to null.",
            'Run "php artisan portfolio:projects" to validate it.',
        ]);

        return self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private function stub(array $replacements): string
    {
        $stub = <<<'PHP'
<?php

/*
| Proyecto del portfolio. Ver README.md → "Agregar un proyecto".
| Validar con: php artisan portfolio:projects
*/

return [
    'name' => {{ name }},
    'order' => {{ order }},
    'featured' => {{ featured }},
    'status' => {{ status }}, // production | development | experimental
    'year' => {{ year }},

    'url' => {{ url }},
    'repository' => null, // https://github.com/... si el código es público
    'links' => [
        // ['label' => ['es' => 'Documentación', 'pt' => 'Documentação', 'en' => 'Docs'], 'url' => 'https://...'],
    ],

    'accent' => '#7C8CFF', // color de identidad del proyecto (hex)
    'logo' => null, // 'images/projects/{{ slug }}/logo.webp'
    'cover' => null, // 'images/projects/{{ slug }}/cover.webp' (1200×630)
    'cover_small' => null, // 'images/projects/{{ slug }}/cover-720.webp' (720×378)

    'stack' => ['Laravel'],

    'content' => [
        'es' => [
            'category' => 'TODO: categoría',
            'tagline' => 'TODO: una frase que resuma el producto.',
            'summary' => 'TODO: qué es y para quién.',
            'problem' => 'TODO: qué problema resuelve.',
            'solution' => 'TODO: cómo lo resuelve.',
            'features' => [
                'TODO: funcionalidad real',
            ],
            'audience' => null,
            'role' => null,
        ],
        // 'pt' => [...], 'en' => [...]: los campos que falten se muestran en español.
    ],
];

PHP;

        return strtr($stub, $replacements);
    }
}
