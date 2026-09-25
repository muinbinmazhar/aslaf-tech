(() => {
    const sidebar = document.querySelector('#admin-sidebar');
    const openButton = document.querySelector('[data-sidebar-open]');
    const closeButton = document.querySelector('[data-sidebar-close]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    if (!sidebar || !openButton || !closeButton || !backdrop) return;

    let previousFocus;
    const close = () => {
        sidebar.classList.remove('is-open');
        backdrop.hidden = true;
        openButton.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-open');
        if (previousFocus && typeof previousFocus.focus === 'function') previousFocus.focus();
    };

    openButton.addEventListener('click', () => {
        previousFocus = document.activeElement;
        sidebar.classList.add('is-open');
        backdrop.hidden = false;
        openButton.setAttribute('aria-expanded', 'true');
        document.body.classList.add('menu-open');
        closeButton.focus();
    });
    closeButton.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', (event) => {
        if (!sidebar.classList.contains('is-open')) return;
        if (event.key === 'Escape') close();
        if (event.key !== 'Tab') return;
        const focusable = [...sidebar.querySelectorAll('a[href], button:not([disabled])')];
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    window.matchMedia('(min-width: 901px)').addEventListener('change', (event) => {
        if (event.matches && sidebar.classList.contains('is-open')) close();
    });
})();
