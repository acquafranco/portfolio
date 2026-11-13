<?php

/*
| Contenido del CV en PDF. Se genera con: php artisan portfolio:cv
| El HTML (<b>, <i>) está permitido en profile, education y los ítems de experiencia.
*/

return [
    'role' => 'Full-Stack Developer — Websites, apps, custom systems and SaaS (Laravel/PHP)',
    'location' => 'Brazil / Argentina',
    'qr_whatsapp' => 'Message me on WhatsApp',
    'qr_portfolio' => 'View portfolio',
    'whatsapp_message' => 'Hi Franco! I saw your resume and I\'d like to talk about a project.',

    'headings' => [
        'profile' => 'Full-stack developer with <b>more than :years years of programming</b>, since my training as a Computer Technician, and building client projects since 2019. I build what each business needs: websites and landing pages, online stores, web apps, custom systems and SaaS platforms. I handle the whole process —understanding the goal, design, development, deployment and support— and I also created and run my own products: <b>Ascento</b> and <b>DOMUS</b> in production, <b>GULA</b> in development.',
        'services' => 'What I do',
        'skills' => 'Technical skills',
        'experience' => 'Experience',
        'education' => 'Education',
        'languages' => 'Languages',
    ],

    'profile' => 'Full-stack developer with <b>more than :years years of programming</b>, since my training as a Computer Technician, and building software for real businesses since 2019. I design, build and ship complete products: I learn how the operation really works, find exactly where it breaks and turn it into a system that runs on its own. I created and run my own SaaS products —<b>Ascento</b> and <b>DOMUS</b> in production, <b>GULA</b> in development— and build custom systems for companies that have outgrown paper, spreadsheets and WhatsApp groups.',

    'services' => [
        'Fast, responsive websites and landing pages built to turn visitors into clients',
        'Online stores with catalog, cart, Mercado Pago payments and order management',
        'Web apps and PWAs: bookings, scheduling, client portals',
        'Custom systems and admin panels for internal operations',
        'Multi-tenant SaaS platforms with users, roles, plans and subscriptions',
        'Integrations: Mercado Pago, WhatsApp Cloud API, e-invoicing, maps and APIs',
    ],

    'skills' => [
        'Backend' => 'PHP 8, Laravel, Filament, Livewire, MySQL, REST APIs',
        'Frontend' => 'Blade, Alpine.js, Tailwind CSS, JavaScript, PWA',
        'Integrations' => 'Mercado Pago, WhatsApp Cloud API, Web Push, maps and geocoding, e-invoicing',
        'Infrastructure' => 'Laravel Forge, Linux, Nginx, Docker, Git / GitHub, Cloudflare',
        'Product' => 'Multi-tenant SaaS, roles and permissions, product design, user research',
    ],

    'jobs' => [
        [
            'title' => 'SaaS product builder',
            'subtitle' => 'Own products, end to end',
            'period' => '2025 — Present',
            'items' => [
                '<b>Ascento</b> (ascento.online) — End-to-end management SaaS for elevator maintenance companies: clients, buildings, technicians, work orders, digital delivery notes with photos, client portal, invoicing and payments via Mercado Pago. <i>In production.</i>',
                '<b>DOMUS</b> — Multi-tenant SaaS for property management firms: buildings, units, owners, maintenance requests, suppliers and invoices, with a dedicated panel for each role. <i>In production.</i>',
                '<b>GULA</b> — Discovery and management platform for restaurants: map-based search, digital menu, table ordering via QR. <i>In development.</i>',
                'Research with companies in each industry, product design, full-stack development, infrastructure and support.',
            ],
        ],
        [
            'title' => 'Freelance Web Developer',
            'subtitle' => 'Client projects',
            'period' => '2019 — Present',
            'items' => [
                'Corporate websites, landing pages and online stores across industries, including hospitality, food service and tourism.',
                'Custom apps and systems: management dashboards, payment integrations and automations.',
                'Full ownership: discovery, design, development, deployment, maintenance and support.',
            ],
        ],
    ],

    'education' => '<b>Computer Technician</b> (technical secondary diploma) — Escuela Técnica N.º 32 D.E. 14 "Gral. José de San Martín", Buenos Aires, Argentina. Programming logic, systems architecture and networking.',

    'languages' => [
        'Spanish' => 'Native',
        'Portuguese' => 'Intermediate',
        'English' => 'Upper intermediate',
    ],
];
