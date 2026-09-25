document.querySelectorAll('[data-media-group]').forEach((group) => {
    const input = group.querySelector('[data-media-input]');
    const image = group.querySelector('[data-media-image]');
    const placeholder = group.querySelector('[data-media-placeholder]');
    const remove = group.querySelector('[data-media-remove]');
    const original = image.getAttribute('src');
    let previewUrl;

    const showOriginal = () => {
        image.hidden = !original || Boolean(remove?.checked);
        placeholder.hidden = !image.hidden;
        if (image.hidden) image.removeAttribute('src');
        else image.src = original;
    };

    input.addEventListener('change', () => {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = undefined;
        const file = input.files?.[0];
        if (!file || !['image/jpeg', 'image/png', 'image/webp', 'image/x-icon', 'image/vnd.microsoft.icon'].includes(file.type)) {
            showOriginal();
            return;
        }
        if (remove) remove.checked = false;
        previewUrl = URL.createObjectURL(file);
        image.src = previewUrl;
        image.hidden = false;
        placeholder.hidden = true;
    });
    remove?.addEventListener('change', () => {
        if (remove.checked) {
            input.value = '';
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = undefined;
        }
        showOriginal();
    });
    if (remove?.checked) showOriginal();
    window.addEventListener('pagehide', () => { if (previewUrl) URL.revokeObjectURL(previewUrl); });
});
