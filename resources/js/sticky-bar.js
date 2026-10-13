export function setupStickyBar() {
    const bar = document.querySelector('[data-sticky-bar]');
    const form = document.querySelector('[data-booking-form]');
    if (!bar || !form || !('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver(([entry]) => {
        bar.classList.toggle('is-hidden', entry.isIntersecting);
    }, { threshold: 0.15 });
    observer.observe(form);
}
