document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(`Delete "${form.dataset.appName}"? This cannot be undone.`)) {
            event.preventDefault();
        }
    });
});
