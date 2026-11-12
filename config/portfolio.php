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

    'locales' => [
        'es' => ['label' => 'Español', 'og' => 'es_AR'],
        'pt' => ['label' => 'Português', 'og' => 'pt_BR'],
        'en' => ['label' => 'English', 'og' => 'en_US'],
    ],

    'default_locale' => 'es',

    'links' => [
        'email' => 'acquafranco97@gmail.com',
        'whatsapp' => 'https://wa.me/5491178233886',
        'linkedin' => 'https://www.linkedin.com/in/franco-acqua',
        'github' => 'https://github.com/acquafranco',
    ],

    /*
    | CV en PDF por idioma (rutas relativas a public/). Si un idioma no tiene
    | CV propio se ofrece el del idioma por defecto.
    */
    'cv' => [
        'es' => 'cv/franco-acqua-cv-es.pdf',
        'pt' => 'cv/franco-acqua-cv-pt.pdf',
    ],

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
        ['key' => 'elevators', 'period_key' => 'years'],
    ],

    'capabilities' => ['product', 'saas', 'automation', 'interfaces', 'business'],

    'process_steps' => ['understand', 'design', 'build', 'test', 'iterate', 'ship'],

    'seo' => [
        'site_name' => 'Franco Acqua',
        'twitter_handle' => null,
        'og_image' => 'images/og-cover.png',
    ],

];
