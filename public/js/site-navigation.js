(() => {
    const header = document.querySelector('.site-header');
    if (header) {
        const syncHeaderHeight = () => {
            document.documentElement.style.setProperty('--site-header-height', `${Math.ceil(header.getBoundingClientRect().height)}px`);
        };
        syncHeaderHeight();
        if ('ResizeObserver' in window) new ResizeObserver(syncHeaderHeight).observe(header);
        else window.addEventListener('resize', syncHeaderHeight);
    }

    const menuButton = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('[data-site-nav]');
    const dropdown = document.querySelector('[data-dropdown]');
    const dropdownButton = dropdown?.querySelector('[data-dropdown-toggle]');
    const dropdownPanel = dropdown?.querySelector('[data-dropdown-panel]');
    const contactDropdown = document.querySelector('[data-contact-dropdown]');
    const contactButton = contactDropdown?.querySelector('[data-contact-toggle]');
    const contactPanel = contactDropdown?.querySelector('[data-contact-panel]');
    if (!menuButton || !nav || !dropdown || !dropdownButton || !dropdownPanel) return;

    const closeDropdown = () => {
        dropdownPanel.hidden = true;
        dropdownButton.setAttribute('aria-expanded', 'false');
    };
    const closeContact = () => {
        if (!contactPanel) return;
        contactPanel.hidden = true;
        contactButton.setAttribute('aria-expanded', 'false');
    };
    const closeMenu = () => {
        nav.classList.remove('is-open');
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Open menu');
        closeDropdown();
        closeContact();
    };

    menuButton.addEventListener('click', () => {
        const open = !nav.classList.contains('is-open');
        nav.classList.toggle('is-open', open);
        menuButton.setAttribute('aria-expanded', String(open));
        menuButton.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        if (!open) closeDropdown();
    });
    dropdownButton.addEventListener('click', () => {
        closeContact();
        const open = dropdownPanel.hidden;
        dropdownPanel.hidden = !open;
        dropdownButton.setAttribute('aria-expanded', String(open));
        if (open) dropdownPanel.querySelector('a')?.focus();
    });
    contactButton?.addEventListener('click', () => {
        const open = contactPanel.hidden;
        closeDropdown();
        contactPanel.hidden = !open;
        contactButton.setAttribute('aria-expanded', String(open));
        if (open) contactPanel.querySelector('a')?.focus();
    });
    document.addEventListener('click', (event) => {
        if (!dropdown.contains(event.target)) closeDropdown();
        if (contactDropdown && !contactDropdown.contains(event.target)) closeContact();
        if (!nav.contains(event.target) && !menuButton.contains(event.target)) closeMenu();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        if (contactPanel && !contactPanel.hidden) {
            closeContact();
            contactButton.focus();
        } else if (!dropdownPanel.hidden) {
            closeDropdown();
            dropdownButton.focus();
        } else if (nav.classList.contains('is-open')) {
            closeMenu();
            menuButton.focus();
        }
    });
    nav.addEventListener('click', (event) => {
        if (event.target.closest('a') && window.matchMedia('(max-width: 900px)').matches) closeMenu();
    });
    window.matchMedia('(min-width: 901px)').addEventListener('change', (event) => {
        if (event.matches) closeMenu();
    });
})();
