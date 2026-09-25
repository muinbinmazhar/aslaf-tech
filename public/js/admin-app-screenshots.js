const upload = document.querySelector('[data-screenshot-upload]');
if (upload) {
    const input = upload.querySelector('input[type="file"]');
    const count = upload.querySelector('[data-selection-count]');
    input.addEventListener('change', () => {
        count.textContent = `${input.files.length} image${input.files.length === 1 ? '' : 's'} selected`;
    });
    upload.querySelector('[data-clear-selection]').addEventListener('click', () => {
        input.value = '';
        count.textContent = 'No images selected';
        input.focus();
    });
    upload.addEventListener('submit', () => {
        upload.querySelector('button[type="submit"]').disabled = true;
    });
}

const order = document.querySelector('[data-screenshot-order]');
document.querySelectorAll('[data-move]').forEach((button) => {
    button.addEventListener('click', () => {
        const inputs = [...order.querySelectorAll('input[name="screenshot_ids[]"]')];
        const position = inputs.findIndex((input) => input.value === button.dataset.id);
        const neighbor = position + Number(button.dataset.move);
        if (position < 0 || neighbor < 0 || neighbor >= inputs.length) return;
        [inputs[position].value, inputs[neighbor].value] = [inputs[neighbor].value, inputs[position].value];
        document.querySelectorAll('[data-move]').forEach((control) => { control.disabled = true; });
        order.requestSubmit();
    });
});

document.querySelectorAll('[data-confirm-screenshot-delete]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm('Delete this screenshot? This action cannot be undone.')) event.preventDefault();
    });
});
