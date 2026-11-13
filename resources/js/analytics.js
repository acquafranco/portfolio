/**
 * First-party analytics: page views and clicks on elements marked with
 * data-track="<event>". No cookies, no third parties. The admin panel sets
 * localStorage "fa:no-track" so the owner's own visits are not counted.
 */
const ENDPOINT = '/a/e';

function isOptedOut() {
    try {
        return window.localStorage.getItem('fa:no-track') === '1';
    } catch {
        return false;
    }
}

function send(name) {
    const payload = JSON.stringify({
        n: name,
        p: window.location.pathname,
        r: name === 'pageview' ? document.referrer : '',
        w: window.innerWidth,
    });

    if (navigator.sendBeacon) {
        navigator.sendBeacon(ENDPOINT, new Blob([payload], { type: 'application/json' }));
        return;
    }

    fetch(ENDPOINT, { method: 'POST', body: payload, headers: { 'Content-Type': 'application/json' }, keepalive: true }).catch(() => {});
}

export function initAnalytics() {
    if (isOptedOut() || navigator.webdriver) return;

    send('pageview');

    document.addEventListener(
        'click',
        (event) => {
            const target = event.target instanceof Element ? event.target.closest('[data-track]') : null;
            if (target) send(target.getAttribute('data-track'));
        },
        { capture: true },
    );
}
