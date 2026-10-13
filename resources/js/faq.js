export function setupFaqs() {
    document.querySelectorAll('[data-faq-list]').forEach((list) => {
        list.querySelectorAll('[data-faq-button]').forEach((button) => {
            button.addEventListener('click', () => {
                const panel = document.getElementById(button.getAttribute('aria-controls'));
                const expanded = button.getAttribute('aria-expanded') === 'true';
                button.setAttribute('aria-expanded', String(!expanded));
                panel.hidden = expanded;
            });
        });
    });
}
