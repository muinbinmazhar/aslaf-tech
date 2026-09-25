document.querySelectorAll('[data-footer-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        try {
            if (navigator.clipboard?.writeText) {
                await navigator.clipboard.writeText(button.dataset.footerCopy);
            } else {
                const field = document.createElement('textarea');
                field.value = button.dataset.footerCopy;
                field.style.position = 'fixed';
                field.style.opacity = '0';
                document.body.append(field);
                field.select();
                const copied = document.execCommand('copy');
                field.remove();
                if (!copied) throw new Error('Copy failed');
            }
            button.textContent = 'Copied';
            window.setTimeout(() => { button.textContent = 'Copy'; }, 1800);
        } catch {
            button.textContent = 'Copy unavailable';
        }
    });
});
