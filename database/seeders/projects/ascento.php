<?php

/*
|--------------------------------------------------------------------------
| ASCENTO
|--------------------------------------------------------------------------
|
| Cada proyecto del portfolio es un archivo en config/projects/. El nombre
| del archivo es el slug (URL: /{locale}/projects/ascento). Ver README.md,
| sección "Agregar un proyecto", para la referencia completa de campos.
|
*/

return [
    'name' => 'Ascento',
    'order' => 10,
    'featured' => true,
    'status' => 'production',
    'year' => 2025,

    'url' => 'https://ascento.online',
    'repository' => null,
    'links' => [],

    'accent' => '#FF6A1A',
    'logo' => 'images/projects/ascento/logo.webp',
    'cover' => 'images/projects/ascento/cover.webp',
    'cover_small' => 'images/projects/ascento/cover-720.webp',

    'stack' => ['Laravel 13', 'PHP 8', 'Filament 5', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'MySQL', 'Mercado Pago', 'WhatsApp Cloud API'],

    'content' => [
        'es' => [
            'category' => 'SaaS · Mantenimiento de ascensores',
            'tagline' => 'Todo lo que necesita una empresa de ascensores, en un solo lugar.',
            'summary' => 'Software de gestión integral para empresas de mantenimiento de ascensores. Conecta la oficina, los técnicos en la calle, los clientes y los usuarios de cada edificio en una sola plataforma.',
            'problem' => 'Las empresas de mantenimiento trabajan con la información repartida entre papeles, planillas, grupos de WhatsApp y la memoria de cada técnico. Los remitos se pierden, los presupuestos se demoran y el cliente no sabe qué se hizo en su edificio.',
            'solution' => 'Ascento centraliza toda la operación: la oficina organiza desde la computadora, los técnicos registran el trabajo desde el celular con fotos, y los clientes consultan en su propio portal lo que la empresa decide compartirles.',
            'features' => [
                'Clientes, edificios y ascensores con su historial completo',
                'Técnicos, asignaciones y cronograma de guardias',
                'Mantenimientos, inspecciones y pruebas de paracaídas',
                'Órdenes de trabajo y presupuestos',
                'Remitos digitales y reportes con fotografías',
                'Portal de clientes y reclamos desde el edificio',
                'Facturación electrónica y cuentas a cobrar',
                'Stock de repuestos y gestión administrativa',
            ],
            'audience' => 'Empresas conservadoras y de mantenimiento de ascensores: desde equipos chicos hasta operaciones con muchos técnicos y edificios.',
            'role' => 'Producto propio. Investigación con empresas del rubro, diseño del producto, desarrollo full-stack, infraestructura y soporte.',
        ],
        'pt' => [
            'category' => 'SaaS · Manutenção de elevadores',
            'tagline' => 'Tudo o que uma empresa de elevadores precisa, em um só lugar.',
            'summary' => 'Software de gestão completo para empresas de manutenção de elevadores. Conecta o escritório, os técnicos em campo, os clientes e os usuários de cada prédio em uma única plataforma.',
            'problem' => 'As empresas de manutenção trabalham com a informação espalhada entre papéis, planilhas, grupos de WhatsApp e a memória de cada técnico. Os comprovantes se perdem, os orçamentos atrasam e o cliente não sabe o que foi feito no seu prédio.',
            'solution' => 'O Ascento centraliza toda a operação: o escritório organiza pelo computador, os técnicos registram o trabalho pelo celular com fotos e os clientes consultam no próprio portal o que a empresa decide compartilhar.',
            'features' => [
                'Clientes, prédios e elevadores com histórico completo',
                'Técnicos, atribuições e escala de plantão',
                'Manutenções, inspeções e testes de freio de segurança',
                'Ordens de serviço e orçamentos',
                'Comprovantes digitais e relatórios com fotos',
                'Portal do cliente e chamados a partir do prédio',
                'Faturamento eletrônico e contas a receber',
                'Estoque de peças e gestão administrativa',
            ],
            'audience' => 'Empresas de conservação e manutenção de elevadores: de equipes pequenas a operações com muitos técnicos e prédios.',
            'role' => 'Produto próprio. Pesquisa com empresas do setor, design do produto, desenvolvimento full-stack, infraestrutura e suporte.',
        ],
        'en' => [
            'category' => 'SaaS · Elevator maintenance',
            'tagline' => 'Everything an elevator company needs, in one place.',
            'summary' => 'End-to-end management software for elevator maintenance companies. It connects the office, field technicians, clients and the people in each building on a single platform.',
            'problem' => 'Maintenance companies run on information scattered across paper, spreadsheets, WhatsApp groups and each technician\'s memory. Delivery notes get lost, quotes are slow and clients never know what was done in their building.',
            'solution' => 'Ascento centralizes the whole operation: the office plans from the desktop, technicians log their work from their phones with photos, and clients check what the company chooses to share in their own portal.',
            'features' => [
                'Clients, buildings and elevators with full history',
                'Technicians, assignments and on-call schedules',
                'Maintenance visits, inspections and safety-gear tests',
                'Work orders and quotes',
                'Digital delivery notes and photo reports',
                'Client portal and requests from each building',
                'E-invoicing and accounts receivable',
                'Spare-parts stock and back-office management',
            ],
            'audience' => 'Elevator service and maintenance companies, from small crews to operations with many technicians and buildings.',
            'role' => 'My own product. Research with companies in the industry, product design, full-stack development, infrastructure and support.',
        ],
    ],
];
