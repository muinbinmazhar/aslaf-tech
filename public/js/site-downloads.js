(() => {
    const menus = [...document.querySelectorAll('[data-download-menu]')];
    const close = (menu) => {
        menu.querySelector('[data-download-panel]').hidden = true;
        menu.querySelector('[data-download-toggle]').setAttribute('aria-expanded', 'false');
    };

    menus.forEach((menu) => {
        const button = menu.querySelector('[data-download-toggle]');
        const panel = menu.querySelector('[data-download-panel]');
        button.addEventListener('click', () => {
            const open = panel.hidden;
            menus.forEach(close);
            panel.hidden = !open;
            button.setAttribute('aria-expanded', String(open));
            if (open) panel.querySelector('a')?.focus();
        });
        menu.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !panel.hidden) {
                close(menu);
                button.focus();
            }
        });
    });

    document.addEventListener('click', (event) => {
        menus.forEach((menu) => {
            if (!menu.contains(event.target)) close(menu);
        });
    });
})();
