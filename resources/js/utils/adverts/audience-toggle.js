// /resources/js/utils/adverts/audience-toggle.js

/**
 * Handle Audience Toggle with Syncing
 */
export function initAudienceToggle(idPrefix) {
    const allToggle = document.getElementById('all-users-toggle');
    const container = document.getElementById(`${idPrefix}-user-types-container`);
    const individualCbs = container.querySelectorAll('.user-type-checkbox');

    if (!allToggle || !container) return;

    // Master Toggle Logic
    allToggle.addEventListener('change', (e) => {
        const isChecked = e.target.checked;
        individualCbs.forEach(cb => cb.checked = isChecked);
        
        if (isChecked) {
            container.classList.add('hidden');
        } else {
            container.classList.remove('hidden');
        }
    });

    // Individual Sync Logic (Optional but Pro)
    container.addEventListener('change', (e) => {
        if (!e.target.classList.contains('user-type-checkbox')) return;

        const allChecked = Array.from(individualCbs).every(cb => cb.checked);
        
        if (allChecked) {
            allToggle.checked = true;
            container.classList.add('hidden');
        }
    });
}