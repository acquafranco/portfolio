/**
 * Hero terminal: types each command, then reveals its output line by line,
 * with a fake deploy progress bar. Runs once when visible; the replay button
 * runs it again. Skipped entirely with prefers-reduced-motion.
 */
const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

export function initTerminals() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.querySelectorAll('[data-terminal]').forEach((terminal) => {
        const lines = [...terminal.querySelectorAll('[data-line]')];
        const replay = terminal.querySelector('[data-terminal-replay]');
        const typed = lines.flatMap((line) => [...line.querySelectorAll('[data-type]')]);
        typed.forEach((el) => (el.dataset.text = el.textContent));

        let run = 0;

        const play = async () => {
            const current = ++run;
            const alive = () => current === run;

            replay?.classList.remove('is-visible');
            lines.forEach((line) => line.classList.add('terminal-hidden'));
            typed.forEach((el) => (el.textContent = ''));

            await sleep(350);

            for (const line of lines) {
                if (!alive()) return;
                line.classList.remove('terminal-hidden');

                if (line.dataset.line === 'cmd') {
                    const target = line.querySelector('[data-type]');
                    if (target) {
                        target.classList.add('is-typing');
                        for (const char of target.dataset.text) {
                            if (!alive()) return;
                            target.textContent += char;
                            await sleep(38 + Math.random() * 55);
                        }
                        target.classList.remove('is-typing');
                        await sleep(260);
                    }
                } else if (line.dataset.line === 'progress') {
                    const bar = line.querySelector('[data-progress]');
                    const label = line.querySelector('[data-progress-label]');
                    for (let pct = 0; pct <= 100; pct += 4) {
                        if (!alive()) return;
                        bar.style.width = `${pct}%`;
                        label.textContent = `${pct}%`;
                        await sleep(pct > 70 && pct < 90 ? 70 : 22);
                    }
                    await sleep(150);
                } else {
                    await sleep(110);
                }
            }

            replay?.classList.add('is-visible');
        };

        replay?.addEventListener('click', play);

        new IntersectionObserver((entries, observer) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                observer.disconnect();
                play();
            }
        }, { threshold: 0.3 }).observe(terminal);
    });
}

/**
 * Soft spotlight on the hero grid that follows the pointer (desktop only).
 */
export function initSpotlight() {
    const hero = document.querySelector('[data-spotlight]');
    if (!hero || !window.matchMedia('(hover: hover) and (prefers-reduced-motion: no-preference)').matches) return;

    let frame = null;
    hero.addEventListener('pointermove', (event) => {
        if (frame) return;
        frame = requestAnimationFrame(() => {
            const rect = hero.getBoundingClientRect();
            hero.style.setProperty('--spot-x', `${event.clientX - rect.left}px`);
            hero.style.setProperty('--spot-y', `${event.clientY - rect.top}px`);
            hero.classList.add('spotlight-on');
            frame = null;
        });
    });
    hero.addEventListener('pointerleave', () => hero.classList.remove('spotlight-on'));
}
