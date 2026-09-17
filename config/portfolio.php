<?php

// Toda la información no textual (o no traducible) del portfolio vive acá.
// Los textos que sí cambian por idioma (nombres de secciones, descripciones largas,
// bio, etc.) viven en lang/{es,pt,en}/portfolio.php. Este archivo guarda datos
// estructurales: links, tecnologías, orden, rutas de imágenes, flags de estado.
//
// TODO-FRANCO: buscá el texto "PLACEHOLDER" en este archivo y completalo con
// tus datos reales antes de publicar.

return [

    'name' => 'Franco Acqua',

    'locales' => [
    'es' => ['label' => 'Español', 'flag' => '🇦🇷'],
    'pt' => ['label' => 'Português', 'flag' => '🇧🇷'],
    'en' => ['label' => 'English', 'flag' => '🇺🇸'],
],

    'default_locale' => 'es',

    // Links de contacto y redes. No inventamos URLs: completá acá.
    'links' => [
        'email' => 'acquafranco97@gmail.com',
        'whatsapp' => 'https://wa.me/5491178233886',
        'linkedin' => 'https://linkedin.com/in/franco-acqua',
        'github' => 'https://github.com/francoacqua',
    ],

    // CV estático en PDF. Colocá los archivos reales en public/cv/.
    'cv' => [
        'es' => '/cv/franco-acqua-cv-es.pdf',
        'pt' => '/cv/franco-acqua-cv-pt.pdf',
        'en' => '/cv/franco-acqua-cv-en.pdf',
    ],

    // Skills agrupadas. La etiqueta visible sale de lang/*/portfolio.php (skills.groups.*).
    'skills' => [
        [
            'group' => 'backend',
            'items' => ['Laravel', 'PHP', 'MySQL', 'APIs REST'],
        ],
        [
            'group' => 'frontend',
            'items' => ['JavaScript', 'Tailwind CSS', 'Blade', 'Alpine.js'],
        ],
        [
            'group' => 'integrations',
            'items' => ['WhatsApp / Meta APIs', 'Mercado Pago', 'SMTP / DNS', 'Integraciones de terceros'],
        ],
        [
            'group' => 'infra',
            'items' => ['Docker', 'Git / GitHub', 'DigitalOcean', 'Linux (deploy y administración básica)'],
        ],
        [
            'group' => 'product',
            'items' => ['Sistemas multiempresa / multi-tenant', 'Paneles administrativos', 'Autenticación y roles', 'Diseño de producto SaaS'],
        ],
    ],

    // Proyectos. slug se usa para armar rutas de imágenes en public/images/projects/{slug}/.
    'projects' => [
        [
            'slug' => 'ascento',
            'name' => 'Ascento',
            'status' => 'active', // active | in_progress | exploration
            'stack' => ['Laravel 11', 'Filament', 'Blade', 'Tailwind CSS', 'Alpine.js', 'MySQL', 'Mercado Pago API', 'WhatsApp Business API'],
            'links' => [
                'live' => 'PLACEHOLDER', // ej: https://ascento.online
                'github' => null, // repo privado — dejar null si no es público
            ],
            'image' => '/images/projects/ascento/cover.PNG', // PLACEHOLDER: agregar imagen real
        ],
        [
            'slug' => 'gula',
            'name' => 'Gula',
            'status' => 'in_progress',
            'stack' => ['Laravel', 'Blade', 'Tailwind CSS', 'MySQL'],
            'links' => [
                'live' => null,
                'github' => null,
            ],
            'image' => '/images/projects/gula/cover.PNG', // PLACEHOLDER: agregar imagen real
        ],
    ],

    // Timeline de experiencia. El texto de cada item vive en lang/*/portfolio.php
    // (experience.items.{key}.*). 'period' queda libre/aproximado a propósito:
    // no inventamos fechas exactas.
    'experience' => [
        [
            'key' => 'products',
            'period_key' => 'present', // -> lang: experience.periods.present
        ],
        [
            'key' => 'elevators',
            'period_key' => 'years', // -> lang: experience.periods.years
        ],
    ],

    // Pasos del proceso de trabajo ("Cómo trabajo"). Textos en lang/*/portfolio.php
    // bajo process.steps.
    'process_steps' => ['understand', 'design', 'build', 'test', 'iterate', 'ship'],

    // SEO
    'seo' => [
        'site_name' => 'Franco Acqua',
        'twitter_handle' => null, // PLACEHOLDER si tenés uno, sino se omite la tag
        'og_image' => '/images/og-cover.jpg', // PLACEHOLDER: agregar imagen 1200x630
    ],

];
