# Franco Acqua — Portfolio

Sitio personal de Franco Acqua, full-stack developer y creador de productos SaaS. Presenta sus productos —**Ascento**, **GULA** y **DOMUS**— con una página de caso de estudio por proyecto, en español, portugués e inglés.

- Una página principal (`/{locale}`) con hero, productos, perfil, stack, proceso, experiencia y contacto.
- Un caso de estudio por proyecto (`/{locale}/projects/{slug}`).
- `sitemap.xml` y `robots.txt` generados a partir de los proyectos y del dominio real.

## Tecnologías

| Capa | Herramienta |
| --- | --- |
| Backend | PHP 8.3+ · Laravel 13 |
| Vistas | Blade (componentes en `resources/views/components`) |
| Estilos | Tailwind CSS 3 (tokens en `resources/css/app.css` y `tailwind.config.js`) |
| JS | Alpine.js (menú móvil, filtro por estado, copiar email) |
| Build | Vite 8 |
| Tests | PHPUnit 12 |

El sitio **no usa base de datos, sesiones ni cookies**: todo el contenido vive en archivos de configuración y de idioma. Cada página es pública, cacheable y no depende de MySQL.

## Organización

```
config/
  portfolio.php            Perfil: nombre, links, CV, stack, experiencia, SEO
  projects/                Un archivo por proyecto (el nombre del archivo es el slug)
    ascento.php
    gula.php
    domus.php
lang/{es,pt,en}/portfolio.php   Textos de la interfaz
app/
  Portfolio/
    Project.php            Value object de un proyecto ya resuelto para un idioma
    ProjectStatus.php      Enum: production | development | experimental
    ProjectRepository.php  Lee, valida, ordena y traduce los proyectos
    Localization.php       URLs por idioma, hreflang, CV por idioma
  Http/
    Controllers/PortfolioController.php   Home, caso de estudio, redirección por idioma
    Controllers/SeoController.php         sitemap.xml y robots.txt
    Middleware/SetLocale.php              Idioma desde /es, /pt, /en
    Middleware/SecurityHeaders.php        Cabeceras de seguridad
  Console/Commands/
    MakePortfolioProject.php   php artisan portfolio:make-project
    ListPortfolioProjects.php  php artisan portfolio:projects
resources/views/
  portfolio/home.blade.php, portfolio/project.blade.php
  components/              layout, navbar, footer, project-showcase, project-card, project-media, …
  errors/                  404 (con el diseño del sitio), 500 y 503 (autónomas)
public/images/projects/{slug}/  Capturas y logos de cada proyecto (WebP)
```

## Agregar un proyecto

Un proyecto = **un archivo**. No hay que tocar vistas, rutas, traducciones ni el sitemap.

```bash
php artisan portfolio:make-project mi-producto --name="Mi Producto" --status=development --url=https://mi-producto.com --featured
```

Esto crea `config/projects/mi-producto.php` con todos los campos y la carpeta `public/images/projects/mi-producto/`. Completá los textos y validá:

```bash
php artisan portfolio:projects   # lista los proyectos y valida todos los archivos (exit 1 si hay errores)
```

### Campos

| Campo | Descripción |
| --- | --- |
| `name` | Nombre público. |
| `order` | Orden de aparición (menor primero). |
| `featured` | `true`: tarjeta grande destacada. `false`: tarjeta compacta en la grilla. |
| `status` | `production`, `development` o `experimental` (badge y filtro de la home). |
| `year` | Año de inicio (opcional). |
| `url` | Dominio en producción (opcional). |
| `repository` | Repositorio público de GitHub (opcional). |
| `links` | Enlaces extra: `[['label' => ['es' => '…', 'en' => '…'], 'url' => 'https://…']]`. |
| `accent` | Color de identidad del proyecto (`#RRGGBB`): brillo, bordes y detalles. |
| `logo` | Ícono cuadrado (ruta dentro de `public/`), opcional. |
| `cover` / `cover_small` | Captura 1200×630 y 720×378 en WebP. Sin captura se muestra la inicial sobre el color del proyecto. |
| `stack` | Tecnologías. |
| `content.{es,pt,en}` | `category`, `tagline`, `summary`, `problem`, `solution`, `features` (lista), `audience`, `role`. Solo el idioma por defecto (`es`) es obligatorio: lo que falte en otro idioma se muestra en español. |

Un archivo inválido nunca rompe el sitio: el proyecto se omite y se registra un warning en el log. `portfolio:projects` y los tests lo reportan como error.

### Capturas

```bash
cwebp -q 80 -resize 1200 630 captura.png -o public/images/projects/mi-producto/cover.webp
cwebp -q 78 -resize 720 378  captura.png -o public/images/projects/mi-producto/cover-720.webp
```

## Otros contenidos

- **Textos de la interfaz:** `lang/{es,pt,en}/portfolio.php`.
- **Links, CV, stack, experiencia:** `config/portfolio.php`. El CV de cada idioma va en `public/cv/`; si un idioma no tiene CV se ofrece el del idioma por defecto.
- **Imagen para compartir (Open Graph):** `public/images/og-cover.png` (1200×630). Cada caso de estudio usa la captura de su proyecto.

## Ejecutar localmente

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build        # o: npm run dev
php artisan serve    # http://localhost:8000 → redirige a /es, /pt o /en según el navegador
```

Tests:

```bash
php artisan test
```

## Configuración

Variables de entorno relevantes:

| Variable | Valor en producción |
| --- | --- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL pública con `https://` (fuerza URLs https en canonical, Open Graph y sitemap) |
| `APP_KEY` | Generada con `php artisan key:generate` |

No hace falta base de datos: el sitio no usa sesiones ni modelos. Las variables `DB_*`, `SESSION_*` y `CACHE_*` pueden quedar como estén.

## Deploy (Laravel Forge)

`public/build` no se versiona, así que el servidor compila los assets. Script de deploy recomendado:

```bash
cd $FORGE_SITE_PATH
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

npm ci
npm run build

$FORGE_PHP artisan portfolio:projects   # aborta el deploy si un proyecto es inválido
$FORGE_PHP artisan optimize             # config, rutas, vistas y eventos en caché

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock
```

No hay migraciones que correr. Después de cambiar un archivo de `config/projects/` hay que volver a deployar (o correr `php artisan optimize`) porque la configuración queda en caché.

Verificación después del deploy:

```bash
curl -I https://<dominio>/es          # 200, sin Set-Cookie
curl https://<dominio>/sitemap.xml    # todas las URLs con el dominio real
curl https://<dominio>/up             # health check de Laravel
```
