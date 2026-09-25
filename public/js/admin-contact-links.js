(() => {
    document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Delete this contact? This cannot be undone.')) event.preventDefault();
        });
    });

    document.querySelectorAll('[data-copy-value]').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.copyValue);
                button.textContent = 'Copied';
                window.setTimeout(() => { button.textContent = 'Copy'; }, 1800);
            } catch {
                button.textContent = 'Copy unavailable';
            }
        });
    });

    const list = document.querySelector('[data-contact-list][data-reorder-url]');
    const feedback = document.querySelector('[data-order-feedback]');
    if (list) {
        let drag = null;
        let saving = false;
        const rows = () => [...list.querySelectorAll('[data-contact-id]')];
        const ids = () => rows().map((row) => Number(row.dataset.contactId));
        const restore = (original) => original.forEach((row) => list.append(row));

        list.addEventListener('pointerdown', (event) => {
            const handle = event.target.closest('[data-drag-handle]');
            if (!handle || handle.disabled || event.button !== 0 || saving) return;
            drag = {
                handle,
                row: handle.closest('[data-contact-id]'),
                original: rows(),
                pointerId: event.pointerId,
                startX: event.clientX,
                startY: event.clientY,
                moved: false,
            };
            handle.setPointerCapture(event.pointerId);
        });

        window.addEventListener('pointermove', (event) => {
            if (!drag || event.pointerId !== drag.pointerId) return;
            if (!drag.moved && Math.hypot(event.clientX - drag.startX, event.clientY - drag.startY) < 6) return;
            drag.moved = true;
            event.preventDefault();
            drag.row.classList.add('is-dragging');
            const target = document.elementFromPoint(event.clientX, event.clientY)?.closest('[data-contact-id]');
            if (target && target !== drag.row && target.parentElement === list) {
                const halfway = target.getBoundingClientRect().top + target.offsetHeight / 2;
                list.insertBefore(drag.row, event.clientY < halfway ? target : target.nextSibling);
                if (!drag.handle.hasPointerCapture(event.pointerId)) drag.handle.setPointerCapture(event.pointerId);
            }
            if (event.clientY < 60) window.scrollBy(0, -12);
            if (event.clientY > window.innerHeight - 60) window.scrollBy(0, 12);
        }, { passive: false });

        window.addEventListener('pointerup', async (event) => {
            if (!drag || event.pointerId !== drag.pointerId) return;
            const current = drag;
            drag = null;
            current.row.classList.remove('is-dragging');
            const changed = rows().some((row, index) => row !== current.original[index]);
            if (!current.moved || !changed) return;

            saving = true;
            list.classList.add('is-saving');
            try {
                const response = await fetch(list.dataset.reorderUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': list.dataset.csrf,
                    },
                    body: JSON.stringify({ page: Number(list.dataset.page), ids: ids() }),
                });
                if (!response.ok) throw new Error('The order could not be saved. Reload the page and try again.');
                window.location.reload();
            } catch (error) {
                restore(current.original);
                feedback.textContent = error.message;
                feedback.hidden = false;
                saving = false;
                list.classList.remove('is-saving');
            }
        });

        window.addEventListener('pointercancel', (event) => {
            if (!drag || event.pointerId !== drag.pointerId) return;
            restore(drag.original);
            drag.row.classList.remove('is-dragging');
            drag = null;
        });
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape' || !drag) return;
            restore(drag.original);
            drag.row.classList.remove('is-dragging');
            drag = null;
        });
    }

    const type = document.querySelector('[data-contact-type]');
    const preview = document.querySelector('[data-icon-preview]');
    if (!type || !preview) return;
    const customIcon = document.querySelector('#icon_key');
    const customField = document.querySelector('[data-custom-icon-field]');
    const mapField = document.querySelector('[data-map-field]');
    const update = () => {
        const key = type.value === 'custom' ? customIcon.value : type.selectedOptions[0]?.dataset.icon;
        const sample = document.querySelector(`[data-icon-sample="${key || 'link'}"]`);
        preview.replaceChildren(...(sample ? [sample.firstElementChild.cloneNode(true)] : []));
        const label = document.createElement('span');
        label.textContent = type.selectedOptions[0]?.textContent || 'Select a contact type';
        preview.append(label);
        customField.hidden = type.value !== 'custom';
        mapField.hidden = type.value !== 'address';
    };
    type.addEventListener('change', update);
    customIcon.addEventListener('change', update);
    update();
})();
