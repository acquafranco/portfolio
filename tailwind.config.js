/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                graphite: '#101317',
                'graphite-soft': '#171b21',
                paper: '#f5f3ee',
                steel: '#2b313a',
                'steel-soft': '#1d222a',
                amber: '#ff6a1a',
                slate: '#9aa1ac',
            },
            fontFamily: {
                display: ['"Space Grotesk"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                body: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            maxWidth: {
                measure: '62ch',
            },
        },
    },
    plugins: [],
};
