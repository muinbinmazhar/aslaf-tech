(() => {
    const input = document.querySelector('[data-thumbnail-input]');
    const image = document.querySelector('[data-thumbnail-image]');
    const placeholder = document.querySelector('[data-thumbnail-placeholder]');
    const removeCheckbox = document.querySelector('[data-remove-thumbnail]');
    if (!input || !image || !placeholder) return;

    let previewUrl;
    const originalSrc = image.getAttribute('src');

    const showOriginal = () => {
        if (originalSrc && !removeCheckbox?.checked) {
            image.src = originalSrc;
            image.hidden = false;
            placeholder.hidden = true;
        } else {
            image.hidden = true;
            image.removeAttribute('src');
            placeholder.hidden = false;
        }
    };

    input.addEventListener('change', () => {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = undefined;
        const file = input.files?.[0];
        if (!file || !['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            showOriginal();
            return;
        }

        if (removeCheckbox) removeCheckbox.checked = false;

        previewUrl = URL.createObjectURL(file);
        image.src = previewUrl;
        image.hidden = false;
        placeholder.hidden = true;
    });

    removeCheckbox?.addEventListener('change', () => {
        if (removeCheckbox.checked) {
            input.value = '';
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = undefined;
        }
        showOriginal();
    });

    if (removeCheckbox?.checked) showOriginal();

    window.addEventListener('pagehide', () => {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
    });
})();
