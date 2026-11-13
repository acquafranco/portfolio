<?php

/*
| Contenido del CV en PDF. Se genera con: php artisan portfolio:cv
| El HTML (<b>, <i>) está permitido en profile, education y los ítems de experiencia.
*/

return [
    'role' => 'Full-Stack Developer — Sitios web, aplicaciones, sistemas a medida y SaaS (Laravel/PHP)',
    'location' => 'Brasil / Argentina',
    'qr_whatsapp' => 'Hablemos por WhatsApp',
    'qr_portfolio' => 'Ver portfolio',
    'whatsapp_message' => 'Hola Franco! Vi tu CV y me gustaría hablar sobre un proyecto.',

    'headings' => [
        'profile' => 'Desarrollador full-stack con <b>más de :years años programando</b>, desde mi formación como Técnico en Informática, y desde 2019 desarrollando proyectos para clientes. Construyo lo que cada negocio necesita: sitios web y landing pages, tiendas online, aplicaciones web, sistemas a medida y plataformas SaaS. Me encargo de todo el proceso —entender el objetivo, diseñar, programar, poner en producción y dar soporte— y además creé y opero mis propios productos: <b>Ascento</b> y <b>DOMUS</b> en producción, <b>GULA</b> en desarrollo.',
        'services' => 'Qué hago',
        'skills' => 'Habilidades técnicas',
        'experience' => 'Experiencia',
        'education' => 'Formación',
        'languages' => 'Idiomas',
    ],

    'profile' => 'Desarrollador full-stack con <b>más de :years años programando</b>, desde mi formación como Técnico en Informática, y desde 2019 construyendo software para negocios reales. Diseño, desarrollo y llevo a producción productos completos: entiendo cómo trabaja la operación, encuentro dónde se rompe y la convierto en un sistema que funciona solo. Creé y opero mis propios productos SaaS —<b>Ascento</b> y <b>DOMUS</b> en producción, <b>GULA</b> en desarrollo— y construyo sistemas a medida para empresas que llegaron al límite de los papeles, las planillas y los grupos de WhatsApp.',

    'services' => [
        'Sitios web y landing pages rápidos y responsive, pensados para convertir visitas en clientes',
        'Tiendas online con catálogo, carrito, cobros con Mercado Pago y gestión de pedidos',
        'Aplicaciones web y PWA: reservas, turnos, portales de clientes',
        'Sistemas a medida y paneles administrativos para la gestión interna',
        'Plataformas SaaS multiempresa con usuarios, roles, planes y suscripciones',
        'Integraciones: Mercado Pago, WhatsApp Cloud API, facturación electrónica, mapas y APIs',
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
            'subtitle' => 'Proyectos para clientes',
            'period' => '2019 — Actualidad',
            'items' => [
                'Sitios institucionales, landing pages y tiendas online para distintos rubros, incluyendo hotelería, gastronomía y turismo.',
                'Aplicaciones y sistemas a medida: paneles de gestión, integraciones de pagos y automatizaciones.',
                'Acompañamiento completo: relevamiento, diseño, desarrollo, puesta en producción, mantenimiento y soporte.',
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
