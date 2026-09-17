<?php

return [

    'nav' => [
        'home' => 'Home',
        'about' => 'About',
        'work' => 'What I do',
        'projects' => 'Projects',
        'experience' => 'Experience',
        'contact' => 'Contact',
    ],

    'hero' => [
        'kicker' => 'Franco Acqua',
        'headline' => 'I build digital products that turn real problems into software.',
        'subheadline' => 'Full-stack developer focused on web applications and SaaS. Before writing code, I spent years working in elevator maintenance — so I know what a real operational problem looks like before trying to solve it with software.',
        'cta_projects' => 'View projects',
        'cta_contact' => "Let's talk",
        'cta_cv' => 'Download CV',
        'location' => 'Currently based in Brazil',
    ],

    'about' => [
        'heading' => 'About me',
        'paragraphs' => [
            "I started out in the elevator industry, not software. Installation, maintenance, clients, technicians, buildings: concrete problems solved on site, not on a whiteboard.",
            'At some point I started automating my own work with small scripts and spreadsheets, and that\'s where I found what actually interests me: turning messy processes into systems that run on their own.',
            "Since then I've been building software for real businesses. I design the solution, write the code, and understand the business problem behind it, because I lived it before I ever coded it.",
            'These days I live in Brazil, building my own products and taking on freelance work, combining technical skill with hands-on experience in an industry most developers have never set foot in.',
        ],
    ],

    'work' => [
        'heading' => 'What I do',
        'items' => [
            'product' => [
                'title' => 'Product development',
                'description' => 'Building complete web applications, from idea to production, not stopping at a prototype.',
            ],
            'saas' => [
                'title' => 'SaaS',
                'description' => 'Multi-tenant architectures with users, roles and subscriptions, built to grow without a rewrite.',
            ],
            'automation' => [
                'title' => 'Automation',
                'description' => 'Integrations with APIs and external tools to take manual work out of the way.',
            ],
            'interfaces' => [
                'title' => 'Interfaces',
                'description' => 'Clear, direct interfaces built for people who use them every day, not for a demo.',
            ],
            'business' => [
                'title' => 'Business solutions',
                'description' => 'Software built for a specific problem a company or professional actually has, not a generic template.',
            ],
        ],
    ],

    'skills' => [
        'heading' => 'Technical stack',
        'groups' => [
            'backend' => 'Backend',
            'frontend' => 'Frontend',
            'integrations' => 'Integrations',
            'infra' => 'Infrastructure',
            'product' => 'Product',
        ],
    ],

    'projects' => [
        'heading' => 'Projects',
        'intro' => "Two real products I'm building. No invented numbers: if I don't have a confirmed metric, it won't show up here.",
        'status' => [
            'active' => 'In production',
            'in_progress' => 'In development',
            'exploration' => 'Exploration',
        ],
        'labels' => [
            'problem' => 'The problem',
            'solution' => 'The solution',
            'stack' => 'Technologies',
            'visit' => 'View project',
            'code' => 'Code',
        ],
        'items' => [
            'ascento' => [
                'category' => 'SaaS · Maintenance management',
                'description' => 'A platform for elevator maintenance companies: clients, buildings, technicians, maintenance visits, inspections, delivery notes and billing through Mercado Pago, all in one place.',
                'problem' => 'Elevator maintenance companies usually have their information scattered across WhatsApp, spreadsheets, paper and a handful of disconnected tools.',
                'solution' => 'A centralized platform to organize operations, technicians, clients, buildings, jobs and all the related documentation.',
            ],
            'gula' => [
                'category' => 'Product · Digital experience for restaurants',
                'description' => 'A digital menu for restaurants, built so customers can browse the menu and order from their phone with a modern experience, without the friction of a paper menu.',
                'problem' => "A restaurant's menu is usually static and disconnected from the rest of the business's digital experience.",
                'solution' => 'A multi-restaurant digital menu, focused on the customer experience, with an architecture built to grow.',
                'exploration_note' => "I'm exploring augmented reality to preview dishes before ordering as a future direction — it's still an idea, not a built feature.",
            ],
        ],
    ],

    'experience' => [
        'heading' => 'Experience',
        'periods' => [
            'present' => 'Present',
            'years' => 'Several years',
        ],
        'items' => [
            'products' => [
                'title' => 'Digital product development',
                'description' => 'Designing and building SaaS and web applications for real businesses, end to end.',
            ],
            'elevators' => [
                'title' => 'Elevator maintenance',
                'description' => 'Before building software for maintenance companies, I worked inside that world. I know firsthand how technicians work, how maintenance schedules get organized, and where the everyday problems show up.',
            ],
        ],
    ],

    'process' => [
        'heading' => 'How I work',
        'intro' => 'The same process I use on my own products is the one I bring to client work.',
        'steps' => [
            'understand' => [
                'title' => 'Understand the problem',
                'description' => "Before touching code, I understand what's actually happening, and for whom.",
            ],
            'design' => [
                'title' => 'Design a solution',
                'description' => 'I define the architecture and the key decisions before building anything.',
            ],
            'build' => [
                'title' => 'Build a first version',
                'description' => 'A working version, not a mockup, as early as possible.',
            ],
            'test' => [
                'title' => 'Test with real users',
                'description' => "Real people use it, and that's where what needs fixing shows up.",
            ],
            'iterate' => [
                'title' => 'Iterate',
                'description' => "I adjust based on what actually happens in day-to-day use.",
            ],
            'ship' => [
                'title' => 'Ship to production',
                'description' => 'The goal is always for the product to work in the real world, not in a demo.',
            ],
        ],
    ],

    'contact' => [
        'heading' => "Let's work together",
        'intro' => "I'm looking for job opportunities and also taking on freelance projects. Reach out through whichever channel works best for you.",
        'cta' => "Let's work together",
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
        'linkedin' => 'LinkedIn',
        'github' => 'GitHub',
    ],

    'footer' => [
        'text' => 'Designed and built by Franco Acqua.',
    ],

    'cv' => [
        'download' => 'Download CV',
    ],

    'seo' => [
        'title' => 'Franco Acqua — Full-Stack Developer & Product Builder',
        'description' => 'Full-stack developer specializing in SaaS and web applications with Laravel. I build digital products that solve real business problems.',
    ],

    'not_found' => [
        'heading' => 'Page not found',
        'text' => "This page doesn't exist or has moved.",
        'cta' => 'Back to home',
    ],

];
