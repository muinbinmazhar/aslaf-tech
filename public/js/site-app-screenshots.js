const gallery = document.querySelector('[data-app-gallery]');
if (gallery) {
    const items = [...gallery.querySelectorAll('[data-gallery-item]')];
    const dialog = gallery.querySelector('[data-gallery-dialog]');
    const image = gallery.querySelector('[data-gallery-image]');
    const position = gallery.querySelector('[data-gallery-position]');
    const previous = gallery.querySelector('[data-gallery-previous]');
    const next = gallery.querySelector('[data-gallery-next]');
    const close = gallery.querySelector('[data-gallery-close]');
    let current = 0;
    let opener = null;
    let touchStart = null;

    const show = (index) => {
        current = Math.max(0, Math.min(index, items.length - 1));
        image.src = items[current].dataset.src;
        image.alt = items[current].dataset.alt;
        position.textContent = `${current + 1} / ${items.length}`;
        previous.disabled = current === 0;
        next.disabled = current === items.length - 1;
    };

    items.forEach((item, index) => item.addEventListener('click', () => {
        opener = item;
        show(index);
        dialog.showModal();
        document.body.classList.add('app-gallery-open');
        close.focus();
    }));
    previous.addEventListener('click', () => show(current - 1));
    next.addEventListener('click', () => show(current + 1));
    close.addEventListener('click', () => dialog.close());
    dialog.addEventListener('close', () => {
        document.body.classList.remove('app-gallery-open');
        image.removeAttribute('src');
        opener?.focus();
    });
    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') { event.preventDefault(); show(current - 1); }
        if (event.key === 'ArrowRight') { event.preventDefault(); show(current + 1); }
    });
    dialog.addEventListener('touchstart', (event) => { touchStart = event.touches[0]?.clientX ?? null; }, { passive: true });
    dialog.addEventListener('touchend', (event) => {
        if (touchStart === null) return;
        const distance = (event.changedTouches[0]?.clientX ?? touchStart) - touchStart;
        if (Math.abs(distance) > 55) show(current + (distance < 0 ? 1 : -1));
        touchStart = null;
    }, { passive: true });
}
