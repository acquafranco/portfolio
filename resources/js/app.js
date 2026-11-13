import Alpine from 'alpinejs';
import { initAnalytics } from './analytics';
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

/**
 * Screenshot lightbox for the project gallery: arrows, Escape and click
 * outside close it; focus goes back to where it was.
 */
Alpine.data('screenshotGallery', (shots) => ({
    shots,
    index: 0,
    isOpen: false,
    returnFocus: null,
    get current() {
        return this.shots[this.index] ?? { url: '', caption: null, mobile: false };
    },
    open(index) {
        this.index = index;
        this.returnFocus = document.activeElement;
        this.isOpen = true;
        document.documentElement.classList.add('overflow-hidden');
        this.$nextTick(() => this.$refs.closeButton?.focus());
    },
    close() {
        if (!this.isOpen) return;
        this.isOpen = false;
        document.documentElement.classList.remove('overflow-hidden');
        this.returnFocus?.focus();
    },
    next() {
        this.index = (this.index + 1) % this.shots.length;
    },
    previous() {
        this.index = (this.index - 1 + this.shots.length) % this.shots.length;
    },
}));

window.Alpine = Alpine;
Alpine.start();

initTerminals();
initSpotlight();
initAnalytics();

// Stop the loading shimmer once each image has loaded (or failed).
document.querySelectorAll('img[data-media]').forEach((img) => {
    const done = () => img.parentElement?.classList.remove('media-placeholder');
    if (img.complete) done();
    else {
        img.addEventListener('load', done, { once: true });
        img.addEventListener('error', done, { once: true });
    }
});
