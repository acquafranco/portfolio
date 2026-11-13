<?php

/*
| Contenido del CV en PDF. Se genera con: php artisan portfolio:cv
| El HTML (<b>, <i>) está permitido en profile, education y los ítems de experiencia.
*/

return [
    'role' => 'Full-Stack Developer & Product Builder — SaaS and custom software (Laravel/PHP)',
    'location' => 'Brazil / Argentina',
    'qr_whatsapp' => 'Message me on WhatsApp',
    'qr_portfolio' => 'View portfolio',
    'whatsapp_message' => 'Hi Franco! I saw your resume and I\'d like to talk about a project.',

    'headings' => [
        'profile' => 'Profile',
        'services' => 'What I do',
        'skills' => 'Technical skills',
        'experience' => 'Experience',
        'education' => 'Education',
        'languages' => 'Languages',
    ],

    'profile' => 'Full-stack developer with <b>more than :years years of programming</b>, since my training as a Computer Technician, and building software for real businesses since 2019. I design, build and ship complete products: I learn how the operation really works, find exactly where it breaks and turn it into a system that runs on its own. I created and run my own SaaS products —<b>Ascento</b> and <b>DOMUS</b> in production, <b>GULA</b> in development— and build custom systems for companies that have outgrown paper, spreadsheets and WhatsApp groups.',

    'services' => [
        'Multi-tenant SaaS products: users, roles, plans and per-client data isolation',
        'Custom management systems and admin panels for the office and the field team',
        'Automation and integrations: Mercado Pago, WhatsApp Cloud API, e-invoicing, maps and notifications',
        'Fast, responsive websites and landing pages built to generate leads',
        'Production deployment, maintenance and ongoing support',
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
            'subtitle' => 'Custom software for clients',
            'period' => '2019 — Present',
            'items' => [
                'Internal systems, management dashboards and integrations for businesses that needed to bring order to their operations.',
                'Corporate websites and landing pages across industries, including hospitality, food service and tourism.',
                'Full ownership: understanding the problem, design, development, production deployment and maintenance.',
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
