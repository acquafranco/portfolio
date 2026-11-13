<?php

/*
|--------------------------------------------------------------------------
| DOMUS
|--------------------------------------------------------------------------
|
| Las funcionalidades listadas están verificadas contra el código de DOMUS.
| No se mencionan expensas ni cobranzas: DOMUS no las implementa.
| Ver README.md, sección "Agregar un proyecto", para la referencia de campos.
|
*/

return [
    'name' => 'DOMUS',
    'order' => 30,
    'featured' => true,
    'status' => 'production',
    'year' => 2026,

    'url' => 'https://domus.on-forge.com',
    'repository' => null,
    'links' => [],

    'accent' => '#C9A26B',
    'logo' => 'images/projects/domus/logo.svg',
    'cover' => 'images/projects/domus/cover-v2.webp',
    'cover_small' => 'images/projects/domus/cover-v2-720.webp',

    // Capturas de escritorio y de celular, por rol. Ver README.md, "Galería".
    'gallery' => [
        ['device' => 'desktop', 'image' => 'images/projects/domus/gallery/landing.webp', 'image_small' => 'images/projects/domus/gallery/landing-800.webp', 'caption' => ['es' => 'Sitio · Presentación del producto', 'pt' => 'Site · Apresentação do produto', 'en' => 'Website · Product landing']],
        ['device' => 'desktop', 'image' => 'images/projects/domus/gallery/admin-dashboard.webp', 'image_small' => 'images/projects/domus/gallery/admin-dashboard-800.webp', 'caption' => ['es' => 'Administración · Panel principal', 'pt' => 'Administração · Painel principal', 'en' => 'Management office · Dashboard']],
        ['device' => 'desktop', 'image' => 'images/projects/domus/gallery/admin-claims.webp', 'image_small' => 'images/projects/domus/gallery/admin-claims-800.webp', 'caption' => ['es' => 'Administración · Reclamos', 'pt' => 'Administração · Chamados', 'en' => 'Management office · Claims']],
        ['device' => 'desktop', 'image' => 'images/projects/domus/gallery/admin-claim.webp', 'image_small' => 'images/projects/domus/gallery/admin-claim-800.webp', 'caption' => ['es' => 'Administración · Detalle de un reclamo', 'pt' => 'Administração · Detalhe do chamado', 'en' => 'Management office · Claim detail']],
        ['device' => 'desktop', 'image' => 'images/projects/domus/gallery/admin-map.webp', 'image_small' => 'images/projects/domus/gallery/admin-map-800.webp', 'caption' => ['es' => 'Administración · Mapa de edificios', 'pt' => 'Administração · Mapa de prédios', 'en' => 'Management office · Building map']],
        ['device' => 'desktop', 'image' => 'images/projects/domus/gallery/admin-agenda.webp', 'image_small' => 'images/projects/domus/gallery/admin-agenda-800.webp', 'caption' => ['es' => 'Administración · Agenda y vencimientos', 'pt' => 'Administração · Agenda e vencimentos', 'en' => 'Management office · Calendar and expiries']],
        ['device' => 'mobile', 'image' => 'images/projects/domus/gallery/m-landing.webp', 'image_small' => null, 'caption' => ['es' => 'Sitio · Versión móvil', 'pt' => 'Site · Versão mobile', 'en' => 'Website · Mobile']],
        ['device' => 'mobile', 'image' => 'images/projects/domus/gallery/m-admin-dashboard.webp', 'image_small' => null, 'caption' => ['es' => 'Administración · Panel', 'pt' => 'Administração · Painel', 'en' => 'Management office · Dashboard']],
        ['device' => 'mobile', 'image' => 'images/projects/domus/gallery/m-caretaker-home.webp', 'image_small' => null, 'caption' => ['es' => 'Encargado · Inicio', 'pt' => 'Zelador · Início', 'en' => 'Caretaker · Home']],
        ['device' => 'mobile', 'image' => 'images/projects/domus/gallery/m-caretaker-clock.webp', 'image_small' => null, 'caption' => ['es' => 'Encargado · Fichaje', 'pt' => 'Zelador · Registro de ponto', 'en' => 'Caretaker · Clock in']],
        ['device' => 'mobile', 'image' => 'images/projects/domus/gallery/m-owner-portal.webp', 'image_small' => null, 'caption' => ['es' => 'Propietario · Mi portal', 'pt' => 'Proprietário · Meu portal', 'en' => 'Owner · My portal']],
        ['device' => 'mobile', 'image' => 'images/projects/domus/gallery/m-supplier-jobs.webp', 'image_small' => null, 'caption' => ['es' => 'Proveedor · Mis trabajos', 'pt' => 'Fornecedor · Meus trabalhos', 'en' => 'Supplier · My jobs']],
    ],

    'stack' => ['Laravel 13', 'PHP 8.4', 'Filament 5', 'Livewire 4', 'Tailwind CSS 4', 'MySQL 8', 'Laravel Reverb', 'PWA + Web Push', 'WhatsApp Cloud API'],

    'content' => [
        'es' => [
            'category' => 'SaaS · Administración de consorcios',
            'tagline' => 'Administrar consorcios con orden, no con improvisación.',
            'summary' => 'Plataforma SaaS multiempresa para administraciones de consorcios. Reúne edificios, unidades funcionales, propietarios, reclamos, proveedores, facturas y documentación, con un panel para cada perfil: la oficina, el personal del edificio, los proveedores y los propietarios.',
            'problem' => 'Una administración gestiona muchos edificios con reclamos que llegan por teléfono, proveedores coordinados por chat, facturas en papel y vencimientos anotados en una agenda. Cuando algo se pierde, nadie sabe quién era responsable ni qué pasó.',
            'solution' => 'DOMUS le da a cada tema un responsable, un historial y un aviso: los reclamos siguen un circuito de estados, los trabajos de proveedores se firman en pantalla, las facturas pasan por revisión antes del pago y los vencimientos avisan solos.',
            'features' => [
                'Consorcios, edificios, unidades funcionales y propietarios',
                'Reclamos con numeración, estados e historial inmutable',
                'Intervenciones de proveedores con firma y conformidad',
                'Circuito de facturas de proveedores y registro de pagos',
                'Documentación con vencimientos y alertas automáticas',
                'Personal del edificio con fichaje desde el celular',
                'Portal de propietarios y notificaciones por email, push y WhatsApp',
                'Informes, exportación a Excel/PDF y auditoría completa',
            ],
            'audience' => 'Administradores de consorcios y empresas de administración que necesitan centralizar la gestión de muchos edificios.',
            'role' => 'Producto propio. Arquitectura multiempresa, diseño de los cinco paneles por perfil, desarrollo full-stack, seguridad y despliegue.',
        ],
        'pt' => [
            'category' => 'SaaS · Administração de condomínios',
            'tagline' => 'Administrar condomínios com ordem, não com improviso.',
            'summary' => 'Plataforma SaaS multiempresa para administradoras de condomínios. Reúne prédios, unidades, proprietários, chamados, fornecedores, notas fiscais e documentação, com um painel para cada perfil: o escritório, a equipe do prédio, os fornecedores e os proprietários.',
            'problem' => 'Uma administradora cuida de muitos prédios com chamados que chegam por telefone, fornecedores coordenados por chat, notas em papel e vencimentos anotados numa agenda. Quando algo se perde, ninguém sabe quem era o responsável nem o que aconteceu.',
            'solution' => 'O DOMUS dá a cada assunto um responsável, um histórico e um alerta: os chamados seguem um fluxo de status, os serviços dos fornecedores são assinados na tela, as notas passam por revisão antes do pagamento e os vencimentos avisam sozinhos.',
            'features' => [
                'Condomínios, prédios, unidades e proprietários',
                'Chamados com numeração, status e histórico imutável',
                'Serviços de fornecedores com assinatura e aceite',
                'Fluxo de notas de fornecedores e registro de pagamentos',
                'Documentação com vencimentos e alertas automáticos',
                'Equipe do prédio com registro de ponto pelo celular',
                'Portal do proprietário e notificações por e-mail, push e WhatsApp',
                'Relatórios, exportação para Excel/PDF e auditoria completa',
            ],
            'audience' => 'Síndicos profissionais e administradoras que precisam centralizar a gestão de muitos prédios.',
            'role' => 'Produto próprio. Arquitetura multiempresa, design dos cinco painéis por perfil, desenvolvimento full-stack, segurança e deploy.',
        ],
        'en' => [
            'category' => 'SaaS · Property & building management',
            'tagline' => 'Run buildings with order, not improvisation.',
            'summary' => 'A multi-tenant SaaS for building management firms. It brings together buildings, units, owners, maintenance requests, suppliers, invoices and documents, with a dedicated panel for each profile: the office, building staff, suppliers and owners.',
            'problem' => 'A management firm runs many buildings with requests arriving by phone, suppliers coordinated over chat, paper invoices and deadlines written in a notebook. When something slips, nobody knows who was responsible or what happened.',
            'solution' => 'DOMUS gives every issue an owner, a history and an alert: requests follow a status workflow, supplier work is signed on screen, invoices are reviewed before payment and expiring documents raise their own alerts.',
            'features' => [
                'Associations, buildings, units and owners',
                'Numbered requests with statuses and immutable history',
                'Supplier jobs with on-screen signature and sign-off',
                'Supplier invoice workflow and payment records',
                'Documents with expiry dates and automatic alerts',
                'Building staff with phone-based time tracking',
                'Owner portal and email, push and WhatsApp notifications',
                'Reports, Excel/PDF export and a full audit trail',
            ],
            'audience' => 'Property managers and building management firms that need to centralize the operation of many buildings.',
            'role' => 'My own product. Multi-tenant architecture, design of the five role-based panels, full-stack development, security and deployment.',
        ],
    ],
];
