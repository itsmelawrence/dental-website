export function setupNavigation() {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');
    if (!toggle || !menu) return;

    const close = () => {
        menu.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open menu');
        toggle.focus();
    };

    toggle.addEventListener('click', () => {
        const opening = menu.hidden;
        menu.hidden = !opening;
        toggle.setAttribute('aria-expanded', String(opening));
        toggle.setAttribute('aria-label', opening ? 'Close menu' : 'Open menu');
        if (opening) menu.querySelector('a')?.focus();
    });

    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) close();
    });

    document.addEventListener('keydown', (event) => {
        if (menu.hidden) return;
        if (event.key === 'Escape') {
            close();
            return;
        }
        if (event.key !== 'Tab') return;
        const focusable = [toggle, ...menu.querySelectorAll('a')];
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    matchMedia('(min-width: 1051px)').addEventListener('change', (event) => {
        if (event.matches && !menu.hidden) close();
    });
}
