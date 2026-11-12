{{-- Self-contained error page: no Vite, no translations, no routes, so it
     still renders when the cause of the error is the app itself. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $code }} — Franco Acqua</title>
    <style>
        :root { color-scheme: dark; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #090a0c; color: #edeef0; font: 16px/1.6 ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif; }
        main { max-width: 32rem; padding: 2rem 1.25rem; }
        .code { font-family: ui-monospace, Menlo, monospace; color: #ff6a1a; font-size: .875rem; }
        h1 { font-size: 2rem; line-height: 1.15; letter-spacing: -.03em; margin: .75rem 0; }
        p { color: #9ba1ab; margin: 0 0 .5rem; }
        a { display: inline-block; margin-top: 1.5rem; padding: .625rem 1.25rem; border-radius: 999px; background: #edeef0; color: #090a0c; text-decoration: none; font-weight: 500; font-size: .875rem; }
    </style>
</head>
<body>
    <main>
        <p class="code">{{ $code }}</p>
        <h1>{{ $heading }}</h1>
        <p>{{ $text }}</p>
        <a href="/">Franco Acqua</a>
    </main>
</body>
</html>
