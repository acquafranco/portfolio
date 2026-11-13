<?php

/*
| Contenido del CV en PDF. Se genera con: php artisan portfolio:cv
| El HTML (<b>, <i>) está permitido en profile, education y los ítems de experiencia.
*/

return [
    'role' => 'Full-Stack Developer & Product Builder — SaaS e software sob medida (Laravel/PHP)',
    'location' => 'Brasil / Argentina',
    'qr_whatsapp' => 'Fale comigo pelo WhatsApp',
    'qr_portfolio' => 'Ver portfólio',
    'whatsapp_message' => 'Olá Franco! Vi seu currículo e gostaria de conversar sobre um projeto.',

    'headings' => [
        'profile' => 'Perfil',
        'services' => 'O que faço',
        'skills' => 'Habilidades técnicas',
        'experience' => 'Experiência',
        'education' => 'Formação',
        'languages' => 'Idiomas',
    ],

    'profile' => 'Desenvolvedor full-stack com <b>mais de :years anos programando</b>, desde minha formação como Técnico em Informática, e desde 2019 construindo software para negócios reais. Desenho, desenvolvo e coloco em produção produtos completos: entendo como a operação funciona, encontro onde ela quebra e a transformo em um sistema que funciona sozinho. Criei e opero meus próprios produtos SaaS —<b>Ascento</b> e <b>DOMUS</b> em produção, <b>GULA</b> em desenvolvimento— e construo sistemas sob medida para empresas que chegaram ao limite dos papéis, das planilhas e dos grupos de WhatsApp.',

    'services' => [
        'Produtos SaaS multiempresa: usuários, papéis, planos e dados isolados por cliente',
        'Sistemas de gestão e painéis administrativos sob medida para o escritório e a equipe em campo',
        'Automação e integrações: Mercado Pago, WhatsApp Cloud API, faturamento eletrônico, mapas e notificações',
        'Sites e landing pages rápidos e responsivos, pensados para gerar contatos',
        'Colocação em produção, manutenção e suporte contínuo',
    ],

    'skills' => [
        'Backend' => 'PHP 8, Laravel, Filament, Livewire, MySQL, APIs REST',
        'Frontend' => 'Blade, Alpine.js, Tailwind CSS, JavaScript, PWA',
        'Integrações' => 'Mercado Pago, WhatsApp Cloud API, Web Push, mapas e geocodificação, faturamento eletrônico',
        'Infraestrutura' => 'Laravel Forge, Linux, Nginx, Docker, Git / GitHub, Cloudflare',
        'Produto' => 'SaaS multiempresa, papéis e permissões, design de produto, pesquisa com usuários',
    ],

    'jobs' => [
        [
            'title' => 'Criador de produtos SaaS',
            'subtitle' => 'Produtos próprios, de ponta a ponta',
            'period' => '2025 — Atualmente',
            'items' => [
                '<b>Ascento</b> (ascento.online) — SaaS de gestão completa para empresas de manutenção de elevadores: clientes, prédios, técnicos, ordens de serviço, comprovantes digitais com fotos, portal do cliente, faturamento e cobranças com Mercado Pago. <i>Em produção.</i>',
                '<b>DOMUS</b> — SaaS multiempresa para administradoras de condomínios: prédios, unidades, proprietários, chamados, fornecedores e notas fiscais, com um painel para cada perfil. <i>Em produção.</i>',
                '<b>GULA</b> — Plataforma de descoberta e gestão para restaurantes: busca por mapa, cardápio digital, pedidos na mesa com QR. <i>Em desenvolvimento.</i>',
                'Pesquisa com empresas do setor, design do produto, desenvolvimento full-stack, infraestrutura e suporte.',
            ],
        ],
        [
            'title' => 'Desenvolvedor Web Freelancer',
            'subtitle' => 'Software sob medida para clientes',
            'period' => '2019 — Atualmente',
            'items' => [
                'Sistemas internos, painéis de gestão e integrações para negócios que precisavam organizar sua operação.',
                'Sites institucionais e landing pages para diversos ramos, incluindo hotelaria, gastronomia e turismo.',
                'Acompanhamento completo: levantamento do problema, design, desenvolvimento, colocação em produção e manutenção.',
            ],
        ],
    ],

    'education' => '<b>Técnico em Informática</b> — Escola Técnica N.º 32 D.E. 14 "Gral. José de San Martín", Buenos Aires, Argentina. Lógica de programação, arquitetura de sistemas e redes.',

    'languages' => [
        'Espanhol' => 'Nativo',
        'Português' => 'Intermediário',
        'Inglês' => 'Intermediário avançado',
    ],
];
