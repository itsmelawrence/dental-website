export function setupGalleries() {
    document.querySelectorAll('[data-gallery]').forEach((gallery) => {
        const slides = [...gallery.querySelectorAll('[data-gallery-slide]')];
        if (slides.length < 2) return;
        const count = gallery.querySelector('[data-gallery-count]');
        let index = 0;
        const show = (next) => {
            index = (next + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => { slide.hidden = slideIndex !== index; });
            count.textContent = `${index + 1} / ${slides.length}`;
        };
        gallery.querySelector('[data-gallery-prev]').addEventListener('click', () => show(index - 1));
        gallery.querySelector('[data-gallery-next]').addEventListener('click', () => show(index + 1));
    });
}
