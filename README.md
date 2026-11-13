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
| Admin | Filament 5 (Livewire 4) en `/admin` |
| JS | Alpine.js (menú, filtro, copiar email) + `resources/js/terminal.js` (terminal animada del hero) |
| Build | Vite 8 |
| Tests | PHPUnit 12 |

Los proyectos viven en la base de datos y se administran desde `/admin`. El sitio público **no usa sesiones ni cookies**: cada página es cacheable y, si la base no responde, muestra los proyectos de respaldo.

## Organización

```
config/portfolio.php       Perfil: nombre, links, CV, stack, experiencia, SEO
database/
  migrations/              users (+ is_admin), projects (importa los iniciales)
  seeders/projects/*.php   Datos iniciales y respaldo de Ascento, GULA, DOMUS
lang/{es,pt,en}/portfolio.php   Textos de la interfaz
app/
  Models/Project.php       Proyecto (Eloquent): imágenes, publicación, orden
  Filament/Resources/Projects/   Panel: formulario y tabla de proyectos
  Portfolio/
    LocalizedProject.php   Proyecto ya resuelto para un idioma (lo usan las vistas)
    ProjectImageOptimizer.php  Convierte capturas y logos a WebP
    ProjectStatus.php      Enum: production | development | experimental
    ProjectRepository.php  Lee (base o respaldo), valida, ordena y traduce
    Localization.php       URLs por idioma, hreflang, CV por idioma
  Http/
    Controllers/PortfolioController.php   Home, caso de estudio, redirección por idioma
    Controllers/SeoController.php         sitemap.xml y robots.txt
    Middleware/SetLocale.php              Idioma desde /es, /pt, /en
    Middleware/SecurityHeaders.php        Cabeceras de seguridad
  Console/Commands/
    CreatePortfolioAdmin.php   php artisan portfolio:admin
    ListPortfolioProjects.php  php artisan portfolio:projects
resources/views/
  portfolio/home.blade.php, portfolio/project.blade.php
  components/              layout, navbar, footer, project-showcase, project-card, project-media, …
  errors/                  404 (con el diseño del sitio), 500 y 503 (autónomas)
public/images/projects/{slug}/  Capturas y logos de cada proyecto (WebP)
```

## Panel de administración (`/admin`)

Los proyectos se cargan desde **`/admin`** (Filament 5), con usuario administrador. Desde ahí se puede:

- Crear, editar, despublicar y borrar proyectos.
- Subir la **captura** (se recorta a 1200×630 desde arriba y se convierte a WebP 1200 + 720 px) y el **logo** (160×160 WebP; los SVG se guardan tal cual). Al reemplazar o borrar, los archivos viejos se eliminan.
- Armar la **galería**: capturas de escritorio (WebP 1600 + 800 px) y de celular (WebP 600 px), cada una con su descripción por idioma ("Rol · Pantalla", p. ej. *Comensal · Inicio*). Se ordenan arrastrando. La primera de celular aparece también en un marco de teléfono junto a la captura principal.
- Escribir la descripción en español (obligatorio), portugués e inglés (opcionales: lo vacío se muestra en español): categoría, frase, resumen, problema, solución, funcionalidades, para quién es y rol.
- Definir estado (producción / desarrollo / experimental), destacado, año, color de identidad, tecnologías, sitio, repositorio y enlaces extra.
- Ordenar arrastrando las filas.

Los cambios se ven en el sitio al instante (no hace falta deployar).

### Crear el administrador

```bash
php artisan portfolio:admin tu@email.com                       # pide la contraseña sin mostrarla
php artisan portfolio:admin tu@email.com --password='...'      # no interactivo (Forge → Commands)
```

Si el email ya existe, actualiza la contraseña. No hay registro público: solo entran usuarios con `is_admin`.

### Galería

Cada proyecto puede tener capturas de escritorio (1440×900) y de celular (390×844), por rol. En la página del proyecto se muestran en marcos de navegador y de teléfono, con un visor para ampliarlas. Las de Ascento, GULA y DOMUS están en `public/images/projects/{slug}/gallery/` y se definen en `gallery` dentro de `database/seeders/projects/{slug}.php`; la migración `add_gallery_to_projects_table` las carga en los proyectos que todavía no tienen galería.

### Datos iniciales

La migración `create_projects_table` importa Ascento, GULA y DOMUS desde `database/seeders/projects/*.php` y copia sus imágenes al disco `public`. Nunca pisa proyectos existentes. Si la base no está disponible, el sitio público usa esos mismos archivos como respaldo, así nunca se cae.

`php artisan portfolio:projects` lista los proyectos publicados y verifica que estén completos y que sus imágenes existan.

## Otros contenidos

- **Textos de la interfaz:** `lang/{es,pt,en}/portfolio.php`.
- **Links, CV, stack, experiencia:** `config/portfolio.php`.
- **WhatsApp:** el botón abre el chat con un mensaje ya escrito según el idioma (`contact.whatsapp_message` en `lang/*/portfolio.php`). El CV de cada idioma va en `public/cv/`; si un idioma no tiene CV se ofrece el del idioma por defecto.
- **Imagen para compartir (Open Graph):** `public/images/og-cover.png` (1200×630). Cada caso de estudio usa la captura de su proyecto.

## Ejecutar localmente

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # o configurar MySQL en .env
php artisan migrate
php artisan storage:link
php artisan portfolio:admin tu@email.com
npm install
npm run build        # o: npm run dev
php artisan serve    # http://localhost:8000 → redirige a /es, /pt o /en según el navegador
```

Tests:

```bash
php artisan test
```

## Configuración

| Variable | Valor en producción |
| --- | --- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL pública con `https://` |
| `APP_KEY` | Generada con `php artisan key:generate` |
| `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Base MySQL del sitio (Forge → Database) |
| `SESSION_DRIVER` | `database` o `file` (solo lo usa `/admin`) |

## Deploy (Laravel Forge)

Primera vez:

1. Forge → servidor → **Database**: crear la base (p. ej. `portfolio`) y un usuario con permisos solo sobre ella.
2. Forge → sitio → **Environment**: completar las variables `DB_*` con esos datos.
3. Actualizar el **script de deploy** (abajo) y deployar.
4. Forge → sitio → **Commands**: `php artisan portfolio:admin tu@email.com --password='...'`.
5. Entrar a `https://<dominio>/admin`.

Script de deploy:

```bash
cd $FORGE_SITE_PATH
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

npm ci
npm run build

$FORGE_PHP artisan migrate --force      # solo crea tablas nuevas; no borra datos
$FORGE_PHP artisan storage:link || true # capturas subidas desde /admin
$FORGE_PHP artisan optimize
$FORGE_PHP artisan filament:optimize

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock
```

Las capturas subidas viven en `storage/app/public/projects`: incluir `storage/` en los backups del servidor.

Verificación después del deploy:

```bash
curl -I https://<dominio>/es          # 200, sin Set-Cookie
curl https://<dominio>/sitemap.xml    # todas las URLs con el dominio real
curl -I https://<dominio>/admin       # 302 al login
```
