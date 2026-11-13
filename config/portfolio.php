<?php

/*
|--------------------------------------------------------------------------
| Portfolio: datos del perfil
|--------------------------------------------------------------------------
|
| Datos estructurales del sitio (links, stack, experiencia, SEO). Los textos
| que cambian por idioma viven en lang/{es,pt,en}/portfolio.php.
|
| Los PROYECTOS no están acá: cada uno es un archivo en config/projects/.
| Ver README.md → "Agregar un proyecto".
|
*/

return [

    'name' => 'Franco Acqua',

    /*
    | Años programando, para el dato "+10" del inicio. Mantenelo igual que en el CV.
    */
    'years_programming' => 10,

    'locales' => [
        'es' => ['label' => 'Español', 'og' => 'es_AR'],
        'pt' => ['label' => 'Português', 'og' => 'pt_BR'],
        'en' => ['label' => 'English', 'og' => 'en_US'],
    ],

    'default_locale' => 'es',

    'links' => [
        'email' => 'acquafranco97@gmail.com',
        'whatsapp' => 'https://wa.me/5491178233886',
        'github' => 'https://github.com/acquafranco',
    ],

    /*
    | CV en PDF por idioma (rutas relativas a public/). Si un idioma no tiene
    | CV propio se ofrece el del idioma por defecto.
    */
    'cv' => [
        'es' => 'cv/franco-acqua-cv-es.pdf',
        'pt' => 'cv/franco-acqua-cv-pt.pdf',
        'en' => 'cv/franco-acqua-cv-en.pdf',
    ],

    /*
    | CV: el contenido está en lang/{es,pt,en}/cv.php y los PDF se regeneran
    | con `php artisan portfolio:cv` (necesita Google Chrome). public_url es
    | la dirección pública que va en el CV y en su código QR.
    */
    'public_url' => env('PORTFOLIO_PUBLIC_URL', 'https://acquafranco.on-forge.com'),

    'cv_chrome_path' => env('CV_CHROME_PATH'),

    /*
    | Stack agrupado. La etiqueta de cada grupo sale de
    | lang/*\/portfolio.php → skills.groups.{group}.
    */
    'skills' => [
        ['group' => 'backend', 'items' => ['PHP 8', 'Laravel', 'Filament', 'Livewire', 'MySQL', 'APIs REST']],
        ['group' => 'frontend', 'items' => ['Blade', 'Alpine.js', 'Tailwind CSS', 'JavaScript', 'PWA']],
        ['group' => 'integrations', 'items' => ['Mercado Pago', 'WhatsApp Cloud API', 'Web Push', 'Mapas y geocodificación', 'Facturación electrónica']],
        ['group' => 'infra', 'items' => ['Laravel Forge', 'Linux', 'Nginx', 'Docker', 'Git / GitHub', 'Cloudflare']],
        ['group' => 'product', 'items' => ['SaaS multiempresa', 'Roles y permisos', 'Paneles administrativos', 'Diseño de producto']],
    ],

    /*
    | Timeline de experiencia. Textos en lang/*\/portfolio.php →
    | experience.items.{key}.
    */
    'experience' => [
        ['key' => 'products', 'period_key' => 'present'],
        ['key' => 'custom', 'period_key' => 'parallel'],
        ['key' => 'fullstack', 'period_key' => 'decade'],
    ],

    'capabilities' => ['product', 'saas', 'automation', 'interfaces', 'business'],

    'process_steps' => ['understand', 'design', 'build', 'test', 'iterate', 'ship'],

    'seo' => [
        'site_name' => 'Franco Acqua',
        'twitter_handle' => null,
        'og_image' => 'images/og-cover.png',
    ],

];
