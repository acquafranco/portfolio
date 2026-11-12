/** @type {import('tailwindcss').Config} */
export default {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './config/projects/*.php'],
    theme: {
        extend: {
            colors: {
                ink: 'rgb(var(--color-ink) / <alpha-value>)',
                surface: 'rgb(var(--color-surface) / <alpha-value>)',
                raised: 'rgb(var(--color-raised) / <alpha-value>)',
                line: 'rgb(var(--color-line) / <alpha-value>)',
                fg: 'rgb(var(--color-fg) / <alpha-value>)',
                muted: 'rgb(var(--color-muted) / <alpha-value>)',
                subtle: 'rgb(var(--color-subtle) / <alpha-value>)',
                amber: 'rgb(var(--color-amber) / <alpha-value>)',
                live: 'rgb(var(--color-live) / <alpha-value>)',
            },
            fontFamily: {
                sans: ['Geist', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'sans-serif'],
                mono: ['"Geist Mono"', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
            },
            maxWidth: {
                measure: '64ch',
                site: '72rem',
            },
            letterSpacing: {
                tightest: '-0.04em',
            },
        },
    },
    plugins: [],
};
