import Alpine from 'alpinejs';
import { initSpotlight, initTerminals } from './terminal';

/**
 * Mobile navigation: closes on Escape, on link click and when resizing to desktop.
 */
Alpine.data('siteNav', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
    init() {
        window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => {
            if (event.matches) this.close();
        });
    },
}));

/**
 * Status filter for the projects grid. Without JS every project is visible.
 */
Alpine.data('projectFilter', () => ({
    status: 'all',
    matches(projectStatus) {
        return this.status === 'all' || this.status === projectStatus;
    },
}));

/**
 * Copy-to-clipboard with a short confirmation state.
 */
Alpine.data('copyText', (text) => ({
    copied: false,
    async copy() {
        try {
            await navigator.clipboard.writeText(text);
            this.copied = true;
            setTimeout(() => (this.copied = false), 2000);
        } catch {
            window.location.href = `mailto:${text}`;
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();

initTerminals();
initSpotlight();

// Stop the loading shimmer once each image has loaded (or failed).
document.querySelectorAll('img[data-media]').forEach((img) => {
    const done = () => img.parentElement?.classList.remove('media-placeholder');
    if (img.complete) done();
    else {
        img.addEventListener('load', done, { once: true });
        img.addEventListener('error', done, { once: true });
    }
});
