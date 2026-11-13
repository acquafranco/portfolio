<?php

/*
| Contenido del CV en PDF. Se genera con: php artisan portfolio:cv
| El HTML (<b>, <i>) está permitido en profile, education y los ítems de experiencia.
*/

return [
    'role' => 'Full-Stack Developer & Product Builder — SaaS y software a medida (Laravel/PHP)',
    'location' => 'Brasil / Argentina',
    'qr_whatsapp' => 'Hablemos por WhatsApp',
    'qr_portfolio' => 'Ver portfolio',
    'whatsapp_message' => 'Hola Franco! Vi tu CV y me gustaría hablar sobre un proyecto.',

    'headings' => [
        'profile' => 'Perfil',
        'services' => 'Qué hago',
        'skills' => 'Habilidades técnicas',
        'experience' => 'Experiencia',
        'education' => 'Formación',
        'languages' => 'Idiomas',
    ],

    'profile' => 'Desarrollador full-stack con <b>más de :years años programando</b>, desde mi formación como Técnico en Informática, y desde 2019 construyendo software para negocios reales. Diseño, desarrollo y llevo a producción productos completos: entiendo cómo trabaja la operación, encuentro dónde se rompe y la convierto en un sistema que funciona solo. Creé y opero mis propios productos SaaS —<b>Ascento</b> y <b>DOMUS</b> en producción, <b>GULA</b> en desarrollo— y construyo sistemas a medida para empresas que llegaron al límite de los papeles, las planillas y los grupos de WhatsApp.',

    'services' => [
        'Productos SaaS multiempresa: usuarios, roles, planes y datos aislados por cliente',
        'Sistemas de gestión y paneles administrativos a medida para la oficina y el equipo en la calle',
        'Automatización e integraciones: Mercado Pago, WhatsApp Cloud API, facturación electrónica, mapas y notificaciones',
        'Sitios web y landing pages rápidos y responsive, pensados para generar contactos',
        'Puesta en producción, mantenimiento y soporte continuo',
    ],

    'skills' => [
        'Backend' => 'PHP 8, Laravel, Filament, Livewire, MySQL, APIs REST',
        'Frontend' => 'Blade, Alpine.js, Tailwind CSS, JavaScript, PWA',
        'Integraciones' => 'Mercado Pago, WhatsApp Cloud API, Web Push, mapas y geocodificación, facturación electrónica',
        'Infraestructura' => 'Laravel Forge, Linux, Nginx, Docker, Git / GitHub, Cloudflare',
        'Producto' => 'SaaS multiempresa, roles y permisos, diseño de producto, investigación con usuarios',
    ],

    'jobs' => [
        [
            'title' => 'Creador de productos SaaS',
            'subtitle' => 'Productos propios, de punta a punta',
            'period' => '2025 — Actualidad',
            'items' => [
                '<b>Ascento</b> (ascento.online) — SaaS de gestión integral para empresas de mantenimiento de ascensores: clientes, edificios, técnicos, órdenes de trabajo, remitos digitales con fotos, portal de clientes, facturación y cobros con Mercado Pago. <i>En producción.</i>',
                '<b>DOMUS</b> — SaaS multiempresa para administraciones de consorcios: edificios, unidades, propietarios, reclamos, proveedores y facturas, con un panel para cada perfil. <i>En producción.</i>',
                '<b>GULA</b> — Plataforma de descubrimiento y gestión para restaurantes: búsqueda por mapa, carta digital, pedidos desde la mesa con QR. <i>En desarrollo.</i>',
                'Investigación con empresas del rubro, diseño del producto, desarrollo full-stack, infraestructura y soporte.',
            ],
        ],
        [
            'title' => 'Desarrollador Web Freelance',
            'subtitle' => 'Software a medida para clientes',
            'period' => '2019 — Actualidad',
            'items' => [
                'Sistemas internos, paneles de gestión e integraciones para negocios que necesitaban ordenar su operación.',
                'Sitios institucionales y landing pages para distintos rubros, incluyendo hotelería, gastronomía y turismo.',
                'Acompañamiento completo: relevamiento del problema, diseño, desarrollo, puesta en producción y mantenimiento.',
            ],
        ],
    ],

    'education' => '<b>Técnico en Informática</b> — Escuela Técnica N.º 32 D.E. 14 "Gral. José de San Martín", Buenos Aires, Argentina. Lógica de programación, arquitectura de sistemas y redes.',

    'languages' => [
        'Español' => 'Nativo',
        'Portugués' => 'Intermedio',
        'Inglés' => 'Intermedio avanzado',
    ],
];
