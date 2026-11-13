<?php

/*
|--------------------------------------------------------------------------
| GULA
|--------------------------------------------------------------------------
|
| Ver README.md, sección "Agregar un proyecto", para la referencia de campos.
|
*/

return [
    'name' => 'GULA',
    'order' => 20,
    'featured' => true,
    'status' => 'development',
    'year' => 2025,

    'url' => 'https://gula.on-forge.com',
    'repository' => null,
    'links' => [],

    'accent' => '#EC4A2C',
    'logo' => 'images/projects/gula/logo.webp',
    'cover' => 'images/projects/gula/cover-v2.webp',
    'cover_small' => 'images/projects/gula/cover-v2-720.webp',

    // Capturas de escritorio y de celular, por rol. Ver README.md, "Galería".
    'gallery' => [
        ['device' => 'desktop', 'image' => 'images/projects/gula/gallery/diner-home.webp', 'image_small' => 'images/projects/gula/gallery/diner-home-800.webp', 'caption' => ['es' => 'Comensal · Inicio por antojos', 'pt' => 'Cliente · Início por desejos', 'en' => 'Diner · Home by craving']],
        ['device' => 'desktop', 'image' => 'images/projects/gula/gallery/diner-explore.webp', 'image_small' => 'images/projects/gula/gallery/diner-explore-800.webp', 'caption' => ['es' => 'Comensal · Explorar restaurantes', 'pt' => 'Cliente · Explorar restaurantes', 'en' => 'Diner · Browse restaurants']],
        ['device' => 'desktop', 'image' => 'images/projects/gula/gallery/diner-nearby.webp', 'image_small' => 'images/projects/gula/gallery/diner-nearby-800.webp', 'caption' => ['es' => 'Comensal · Cerca mío en el mapa', 'pt' => 'Cliente · Perto de mim no mapa', 'en' => 'Diner · Near me on the map']],
        ['device' => 'desktop', 'image' => 'images/projects/gula/gallery/restaurant-analytics.webp', 'image_small' => 'images/projects/gula/gallery/restaurant-analytics-800.webp', 'caption' => ['es' => 'Restaurante · Estadísticas', 'pt' => 'Restaurante · Estatísticas', 'en' => 'Restaurant · Analytics']],
        ['device' => 'desktop', 'image' => 'images/projects/gula/gallery/restaurant-menu.webp', 'image_small' => 'images/projects/gula/gallery/restaurant-menu-800.webp', 'caption' => ['es' => 'Restaurante · Gestión de la carta', 'pt' => 'Restaurante · Gestão do cardápio', 'en' => 'Restaurant · Menu management']],
        ['device' => 'desktop', 'image' => 'images/projects/gula/gallery/superadmin-dashboard.webp', 'image_small' => 'images/projects/gula/gallery/superadmin-dashboard-800.webp', 'caption' => ['es' => 'Superadmin · Centro de control', 'pt' => 'Superadmin · Central de controle', 'en' => 'Super admin · Control center']],
        ['device' => 'mobile', 'image' => 'images/projects/gula/gallery/m-diner-home.webp', 'image_small' => null, 'caption' => ['es' => 'Comensal · Inicio', 'pt' => 'Cliente · Início', 'en' => 'Diner · Home']],
        ['device' => 'mobile', 'image' => 'images/projects/gula/gallery/m-diner-nearby.webp', 'image_small' => null, 'caption' => ['es' => 'Comensal · Cerca mío', 'pt' => 'Cliente · Perto de mim', 'en' => 'Diner · Near me']],
        ['device' => 'mobile', 'image' => 'images/projects/gula/gallery/m-diner-restaurant.webp', 'image_small' => null, 'caption' => ['es' => 'Comensal · Ficha del restaurante', 'pt' => 'Cliente · Página do restaurante', 'en' => 'Diner · Restaurant page']],
        ['device' => 'mobile', 'image' => 'images/projects/gula/gallery/m-diner-top.webp', 'image_small' => null, 'caption' => ['es' => 'Comensal · Top por zona', 'pt' => 'Cliente · Top por região', 'en' => 'Diner · Top by area']],
        ['device' => 'mobile', 'image' => 'images/projects/gula/gallery/m-restaurant-analytics.webp', 'image_small' => null, 'caption' => ['es' => 'Restaurante · Estadísticas', 'pt' => 'Restaurante · Estatísticas', 'en' => 'Restaurant · Analytics']],
        ['device' => 'mobile', 'image' => 'images/projects/gula/gallery/m-superadmin-dashboard.webp', 'image_small' => null, 'caption' => ['es' => 'Superadmin · Panel', 'pt' => 'Superadmin · Painel', 'en' => 'Super admin · Dashboard']],
    ],

    'stack' => ['Laravel 13', 'PHP 8', 'Blade', 'Alpine.js', 'Tailwind CSS', 'MySQL', 'MapLibre GL', 'Mercado Pago', 'PWA + Web Push'],

    'content' => [
        'es' => [
            'category' => 'Plataforma · Gastronomía',
            'tagline' => 'Descubrí dónde comer. Y que los restaurantes te encuentren.',
            'summary' => 'Plataforma de descubrimiento y gestión para restaurantes. Los comensales buscan, exploran por cocina y ven en un mapa lo que tienen cerca; los restaurantes digitalizan su carta, sus mesas y la relación con sus clientes.',
            'problem' => 'Elegir dónde comer depende de búsquedas dispersas y recomendaciones sueltas, y la mayoría de los restaurantes chicos no tiene herramientas propias para mostrarse, tomar pedidos o fidelizar a quienes ya los eligieron.',
            'solution' => 'GULA reúne descubrimiento y operación en un mismo producto: una experiencia pensada para encontrar lugares según lo que cada persona busca, y un panel para que el restaurante gestione su carta, sus pedidos en mesa y su club de clientes.',
            'features' => [
                'Búsqueda y exploración por cocina, categoría y características',
                '"Cerca mío": mapa con los restaurantes cercanos',
                'Carta digital, favoritos y calificaciones',
                'Pedidos desde la mesa con QR, llamado al mozo y pedido de cuenta',
                'GULA Club: tarjeta de sellos y fidelización',
                'Panel del restaurante con métricas',
                'Plan GULA Plus con suscripción vía Mercado Pago',
                'Publicidad contextual con impresiones y clics medidos',
            ],
            'audience' => 'Personas que buscan dónde comer y restaurantes que quieren digitalizar su atención sin depender de plataformas de delivery.',
            'role' => 'Producto propio. Concepto, diseño de la experiencia, desarrollo full-stack e infraestructura. Nació como una carta digital y evolucionó hacia una plataforma de descubrimiento.',
        ],
        'pt' => [
            'category' => 'Plataforma · Gastronomia',
            'tagline' => 'Descubra onde comer. E deixe os restaurantes encontrarem você.',
            'summary' => 'Plataforma de descoberta e gestão para restaurantes. Os clientes buscam, exploram por cozinha e veem no mapa o que está por perto; os restaurantes digitalizam o cardápio, as mesas e o relacionamento com seus clientes.',
            'problem' => 'Escolher onde comer depende de buscas dispersas e recomendações soltas, e a maioria dos restaurantes pequenos não tem ferramentas próprias para aparecer, receber pedidos ou fidelizar quem já os escolheu.',
            'solution' => 'O GULA reúne descoberta e operação no mesmo produto: uma experiência pensada para encontrar lugares de acordo com o que cada pessoa procura, e um painel para o restaurante gerenciar o cardápio, os pedidos na mesa e o seu clube de clientes.',
            'features' => [
                'Busca e exploração por cozinha, categoria e características',
                '"Perto de mim": mapa com os restaurantes próximos',
                'Cardápio digital, favoritos e avaliações',
                'Pedidos na mesa por QR, chamar o garçom e pedir a conta',
                'GULA Club: cartão de selos e fidelidade',
                'Painel do restaurante com métricas',
                'Plano GULA Plus com assinatura via Mercado Pago',
                'Publicidade contextual com impressões e cliques medidos',
            ],
            'audience' => 'Pessoas que procuram onde comer e restaurantes que querem digitalizar o atendimento sem depender de apps de delivery.',
            'role' => 'Produto próprio. Conceito, design da experiência, desenvolvimento full-stack e infraestrutura. Começou como um cardápio digital e evoluiu para uma plataforma de descoberta.',
        ],
        'en' => [
            'category' => 'Platform · Food & dining',
            'tagline' => 'Find where to eat. And let restaurants find you.',
            'summary' => 'A discovery and management platform for restaurants. Diners search, browse by cuisine and see what\'s nearby on a map; restaurants digitize their menu, their tables and their relationship with customers.',
            'problem' => 'Choosing where to eat depends on scattered searches and loose recommendations, and most small restaurants have no tools of their own to get discovered, take orders or bring regulars back.',
            'solution' => 'GULA brings discovery and operations into one product: an experience built around finding places that match what each person wants, and a dashboard where restaurants manage their menu, table orders and customer club.',
            'features' => [
                'Search and browse by cuisine, category and features',
                '"Near me": map of nearby restaurants',
                'Digital menu, favorites and ratings',
                'QR table ordering, call-the-waiter and request-the-bill',
                'GULA Club: stamp card and loyalty program',
                'Restaurant dashboard with analytics',
                'GULA Plus subscription plan via Mercado Pago',
                'Contextual ads with measured impressions and clicks',
            ],
            'audience' => 'People looking for a place to eat, and restaurants that want to digitize their service without relying on delivery apps.',
            'role' => 'My own product. Concept, experience design, full-stack development and infrastructure. It started as a digital menu and grew into a discovery platform.',
        ],
    ],
];
