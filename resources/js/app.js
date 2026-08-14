import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Theme toggle (uses the dark class on <html>, persisted in localStorage)
window.toggleTheme = function () {
    const root = document.documentElement;
    const isDark = root.classList.toggle('dark');
    try {
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    } catch (e) {}
};

Alpine.start();

// Scroll reveal via IntersectionObserver
document.addEventListener('DOMContentLoaded', () => {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('reveal-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    els.forEach((el) => observer.observe(el));
});

// Animated number counters for [data-count] elements
const animateCount = (el) => {
    const target = parseFloat(el.dataset.count);
    const suffix = el.dataset.countSuffix || '';
    const duration = 1400;
    const start = performance.now();

    const tick = (now) => {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(target * eased);
        el.textContent = value.toLocaleString() + suffix;
        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    };

    requestAnimationFrame(tick);
};

const countEls = document.querySelectorAll('[data-count]');
if (countEls.length && 'IntersectionObserver' in window) {
    const countObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    countObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 }
    );
    countEls.forEach((el) => countObserver.observe(el));
} else if (countEls.length) {
    countEls.forEach((el) => {
        el.textContent = parseFloat(el.dataset.count).toLocaleString() + (el.dataset.countSuffix || '');
    });
}