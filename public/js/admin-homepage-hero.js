const heroForm = document.querySelector('[data-hero-form]');
const heroRemove = heroForm?.querySelector('[data-media-remove]');
const heroRemoveButton = heroForm?.querySelector('[data-hero-remove]');
const syncHeroRemove = () => {
    if (!heroRemoveButton) return;
    heroRemoveButton.textContent = heroRemove.checked ? 'Undo Remove' : 'Remove Image';
    heroRemoveButton.setAttribute('aria-pressed', String(heroRemove.checked));
};
heroRemoveButton?.addEventListener('click', () => {
    heroRemove.checked = !heroRemove.checked;
    heroRemove.dispatchEvent(new Event('change'));
    syncHeroRemove();
});
heroForm?.querySelector('[data-media-input]')?.addEventListener('change', syncHeroRemove);
heroForm?.addEventListener('submit', (event) => {
    if (heroRemove?.checked
        && !window.confirm('Remove the current hero image when saving? This cannot be undone.')) {
        event.preventDefault();
    }
});
