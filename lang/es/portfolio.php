<?php

return [

    'nav' => [
        'home' => 'Inicio',
        'about' => 'Sobre mí',
        'work' => 'Qué hago',
        'projects' => 'Proyectos',
        'experience' => 'Experiencia',
        'contact' => 'Contacto',
    ],

    'hero' => [
        'kicker' => 'Franco Acqua',
        'headline' => 'Construyo productos digitales que convierten problemas reales en software.',
        'subheadline' => 'Full-stack developer enfocado en aplicaciones web y SaaS. Antes de escribir código trabajé varios años como técnico de ascensores, así que sé cómo se ve un problema operativo real antes de intentar resolverlo con software.',
        'cta_projects' => 'Ver proyectos',
        'cta_contact' => 'Hablemos',
        'cta_cv' => 'Descargar CV',
        'location' => 'Actualmente en Brasil',
    ],

    'about' => [
        'heading' => 'Sobre mí',
        'paragraphs' => [
            'Empecé trabajando dentro del mundo de los ascensores, no del software. Instalación, mantenimiento, clientes, técnicos, edificios: problemas concretos que se resuelven en el terreno, no en una pizarra.',
            'En algún momento empecé a automatizar mi propio trabajo con pequeños scripts y planillas, y ahí encontré lo que realmente me interesa: convertir procesos desordenados en sistemas que funcionan solos.',
            'Desde entonces vengo construyendo software para negocios reales. Diseño la solución, escribo el código y también entiendo el problema de negocio detrás, porque lo viví de cerca antes de programarlo.',
            'Hoy vivo en Brasil, desarrollo mis propios productos y trabajo también como freelance, combinando conocimiento técnico con experiencia directa de un sector industrial que la mayoría de los desarrolladores nunca pisó.',
        ],
    ],

    'work' => [
        'heading' => 'Qué hago',
        'items' => [
            'product' => [
                'title' => 'Desarrollo de productos',
                'description' => 'Construyo aplicaciones web completas, desde la idea hasta producción, sin quedarme solo en el prototipo.',
            ],
            'saas' => [
                'title' => 'SaaS',
                'description' => 'Arquitecturas multiempresa, con usuarios, roles y suscripciones, pensadas para crecer sin reescribirse.',
            ],
            'automation' => [
                'title' => 'Automatización',
                'description' => 'Integraciones con APIs y herramientas externas para sacar trabajo manual de en medio.',
            ],
            'interfaces' => [
                'title' => 'Interfaces',
                'description' => 'Diseño de interfaces claras y directas, pensadas para gente que las usa todos los días, no para una demo.',
            ],
            'business' => [
                'title' => 'Soluciones para negocios',
                'description' => 'Software adaptado a un problema específico de una empresa o un profesional, no una plantilla genérica.',
            ],
        ],
    ],

    'skills' => [
        'heading' => 'Stack técnico',
        'groups' => [
            'backend' => 'Backend',
            'frontend' => 'Frontend',
            'integrations' => 'Integraciones',
            'infra' => 'Infraestructura',
            'product' => 'Producto',
        ],
    ],

    'projects' => [
        'heading' => 'Proyectos',
        'intro' => 'Dos productos reales que vengo construyendo. Nada de números inventados: si no tengo una métrica confirmada, no la vas a ver acá.',
        'status' => [
            'active' => 'En producción',
            'in_progress' => 'En desarrollo',
            'exploration' => 'Exploración',
        ],
        'labels' => [
            'problem' => 'El problema',
            'solution' => 'La solución',
            'stack' => 'Tecnologías',
            'visit' => 'Ver proyecto',
            'code' => 'Código',
        ],
        'items' => [
            'ascento' => [
                'category' => 'SaaS · Gestión de mantenimiento',
                'description' => 'Plataforma para empresas de mantenimiento de ascensores: clientes, edificios, técnicos, mantenimientos, inspecciones, remitos y facturación con Mercado Pago, todo en un solo lugar.',
                'problem' => 'Las empresas de mantenimiento de ascensores suelen trabajar con la información dispersa entre WhatsApp, planillas, papeles y un puñado de herramientas sueltas.',
                'solution' => 'Una plataforma centralizada para organizar operaciones, técnicos, clientes, edificios, trabajos y toda la documentación asociada.',
            ],
            'gula' => [
                'category' => 'Producto · Experiencia digital para restaurantes',
                'description' => 'Carta digital para restaurantes, pensada para que el cliente consulte el menú y pida desde su celular con una experiencia moderna, sin la fricción de una carta de papel.',
                'problem' => 'La carta de un restaurante suele ser estática y desconectada del resto de la experiencia digital del negocio.',
                'solution' => 'Una carta digital multirestaurante, con foco en la experiencia del cliente y una arquitectura pensada para crecer.',
                'exploration_note' => 'A futuro estoy explorando realidad aumentada para mostrar los platos antes de pedir — todavía es una idea, no una funcionalidad construida.',
            ],
        ],
    ],

    'experience' => [
        'heading' => 'Experiencia',
        'periods' => [
            'present' => 'Actualidad',
            'years' => 'Varios años',
        ],
        'items' => [
            'products' => [
                'title' => 'Desarrollo de productos digitales',
                'description' => 'Diseño y construcción de SaaS y aplicaciones web para negocios reales, de punta a punta.',
            ],
            'elevators' => [
                'title' => 'Técnico de ascensores',
                'description' => 'Antes de construir software para empresas de mantenimiento, trabajé dentro de ese mundo. Conozco de primera mano cómo trabajan los técnicos, cómo se organizan los mantenimientos y dónde aparecen los problemas del día a día.',
            ],
        ],
    ],

    'process' => [
        'heading' => 'Cómo trabajo',
        'intro' => 'El mismo proceso que uso en mis propios productos lo aplico con clientes.',
        'steps' => [
            'understand' => [
                'title' => 'Entender el problema',
                'description' => 'Antes de tocar código, entiendo qué está pasando realmente y para quién.',
            ],
            'design' => [
                'title' => 'Diseñar una solución',
                'description' => 'Defino la arquitectura y las decisiones clave antes de construir.',
            ],
            'build' => [
                'title' => 'Construir una primera versión',
                'description' => 'Una versión funcional, no una maqueta, lo antes posible.',
            ],
            'test' => [
                'title' => 'Probar con usuarios',
                'description' => 'La usan personas reales, y ahí aparece lo que hay que corregir.',
            ],
            'iterate' => [
                'title' => 'Iterar',
                'description' => 'Ajusto en base a lo que realmente pasa en el uso diario.',
            ],
            'ship' => [
                'title' => 'Llevar a producción',
                'description' => 'El objetivo siempre es que el producto funcione en el mundo real, no en una demo.',
            ],
        ],
    ],

    'contact' => [
        'heading' => 'Trabajemos juntos',
        'intro' => 'Estoy buscando oportunidades laborales y también tomando proyectos freelance. Escribime por el medio que te resulte más cómodo.',
        'cta' => 'Trabajemos juntos',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
        'linkedin' => 'LinkedIn',
        'github' => 'GitHub',
    ],

    'footer' => [
        'text' => 'Diseñado y construido por Franco Acqua.',
    ],

    'cv' => [
        'download' => 'Descargar CV',
    ],

    'seo' => [
        'title' => 'Franco Acqua — Full-Stack Developer & Product Builder',
        'description' => 'Full-stack developer especializado en SaaS y aplicaciones web con Laravel. Construyo productos digitales que resuelven problemas reales de negocio.',
    ],

    'not_found' => [
        'heading' => 'Página no encontrada',
        'text' => 'Esta página no existe o se movió de lugar.',
        'cta' => 'Volver al inicio',
    ],

];
