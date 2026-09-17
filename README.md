# Portfolio de Franco Acqua — Laravel, sin base de datos

## Arquitectura

- **Sin DB, sin modelos Eloquent.** Todo el contenido vive en `config/portfolio.php`
  (datos estructurales: links, stack, proyectos, orden) y en
  `lang/{es,pt,en}/portfolio.php` (textos traducidos). Agregar un proyecto nuevo
  es agregar una entrada en el array de `config/portfolio.php` + su traducción
  en los tres `lang/*/portfolio.php` — no hay que tocar vistas ni rutas.
- **Un solo controller** (`PortfolioController`) que arma los datos ya
  combinados (proyecto + traducción, experiencia + traducción, skills + label
  traducido) y se los pasa a una única vista `portfolio.home` (one-page).
- **Idioma por segmento de URL**: `/es`, `/pt`, `/en`. Un middleware
  (`SetLocale`) valida el segmento contra `config('portfolio.locales')` y
  aplica el locale de Laravel. `/` redirige a `/{default_locale}`.
- **Diseño**: estética "plano técnico" — grafito oscuro, tipografía Space
  Grotesk/Inter, una línea vertical tipo regla de medición ("spec line") como
  recurso estructural, y marcos con esquinas tipo plano de ingeniería para las
  imágenes de proyectos. Nace de tu propio recorrido (ascensores → software),
  no es una plantilla SaaS genérica. Los tokens de color/tipografía están en
  `resources/css/app.css` y `tailwind.config.js`.
- **JS mínimo**: sólo Alpine.js, para el menú mobile. Todo lo demás es CSS.

## Estructura final de archivos

```
app/Http/Controllers/PortfolioController.php
app/Http/Middleware/SetLocale.php
config/portfolio.php
lang/es/portfolio.php
lang/pt/portfolio.php
lang/en/portfolio.php
resources/css/app.css
resources/js/app.js
resources/views/components/layout.blade.php       (layout con <head>, SEO, navbar, footer)
resources/views/components/navbar.blade.php
resources/views/components/language-switcher.blade.php
resources/views/components/section-heading.blade.php
resources/views/components/button.blade.php
resources/views/components/project-card.blade.php
resources/views/components/skill-badge.blade.php
resources/views/components/timeline-item.blade.php
resources/views/components/footer.blade.php
resources/views/portfolio/home.blade.php
resources/views/errors/404.blade.php
routes/web.php
public/favicon.svg
public/robots.txt
public/sitemap.xml
public/images/projects/ascento/   (vacía — falta la imagen real)
public/images/projects/gula/      (vacía — falta la imagen real)
public/cv/                        (vacía — faltan los 3 PDF)
tailwind.config.js
postcss.config.js
vite.config.js
```

## Instrucciones exactas para ejecutarlo

Este ZIP no es un proyecto Laravel completo (no incluye `vendor/`, `artisan`
ni el resto del scaffolding) — es el conjunto de archivos a copiar dentro de
un proyecto Laravel nuevo.

```bash
# 1. Crear el proyecto base
composer create-project laravel/laravel portfolio-franco
cd portfolio-franco

# 2. Copiar todos los archivos de este ZIP dentro del proyecto,
#    respetando las carpetas (sobrescribir routes/web.php es esperado)

# 3. Instalar dependencias de front
npm install
npm install -D tailwindcss@^3 postcss autoprefixer
npm install alpinejs

# 4. Registrar el middleware (Laravel 11 usa bootstrap/app.php).
#    Abrí bootstrap/app.php y agregá el alias dentro de ->withMiddleware():
#
#    ->withMiddleware(function (Illuminate\Foundation\Configuration\Middleware $middleware) {
#        $middleware->alias([
#            'setlocale' => \App\Http\Middleware\SetLocale::class,
#        ]);
#    })

# 5. Levantar todo
npm run dev
php artisan serve
```

Abrí `http://localhost:8000` — te va a redirigir a `/es`.

Para producción: `npm run build` genera los assets, y en el servidor sólo
necesitás PHP + el `public/` con el build ya hecho (no hace falta Node en el
servidor de producción).

## Qué tenés que completar antes de publicar

Todo lo que sigue está marcado con `PLACEHOLDER` en `config/portfolio.php`:

1. **Links reales**: email, WhatsApp (`wa.me/...`), LinkedIn, GitHub.
2. **URL de Ascento** (`config('portfolio.projects.0.links.live')`) — o
   dejala en `null` si todavía no querés linkearla públicamente.
3. **Los 3 PDF de CV** en `public/cv/` (`franco-acqua-cv-{es,pt,en}.pdf`).
4. **Dominio real en `public/sitemap.xml`** (reemplazar
   `PLACEHOLDER-DOMINIO.com`).
5. Revisar si Gula ya tiene GitHub público o link en vivo, y completar
   `config('portfolio.projects.1.links')`.

## Qué imágenes deberías agregar

- `public/images/projects/ascento/cover.jpg` — captura del dashboard o de
  una vista representativa (recomendado 1200×900 o similar 4:3).
- `public/images/projects/gula/cover.jpg` — captura de la carta digital.
- `public/images/og-cover.jpg` — imagen 1200×630 para preview al compartir
  el link (redes, WhatsApp, etc.).
- (Opcional) reemplazar `public/favicon.svg`, que hoy es un placeholder
  simple con el ícono del ascensor.

## Qué deberías personalizar antes de publicar

- Revisar los textos de `lang/*/portfolio.php` — están escritos para sonar
  como vos, pero son un punto de partida: ajustá lo que no te represente.
  El portugués es una traducción natural pensada para Brasil, no literal
  desde el español — igual, dale una lectura si tenés a alguien nativo cerca.
- Los años de experiencia quedaron deliberadamente sin fechas exactas
  ("Actualidad" / "Varios años") porque no las confirmaste — si querés
  fechas concretas, se agregan en `config('portfolio.experience')` y en
  `lang/*/portfolio.php` → `experience.periods`.
- Ninguna métrica (usuarios, facturación, clientes) está inventada — si en
  algún momento tenés números reales para mostrar, hay lugar pensado en la
  sección de cada proyecto (`project-card.blade.php`, bloque `<dl>`) para
  agregarlos sin rehacer el layout.
# portfolio
