// /resources/js/utils/listings/form-events.js

/**
 * Handles UI-specific events for the Listing Form - Gonachi Swap Edition 🔄
 */
export function initListingFormEvents(formId, idPrefix) {
    const form = document.getElementById(formId);
    if (!form) return;

    // --- 1. Transaction Type Toggle Logic ---
    // Mapped to Listing models: 1: Swap, 2: Sale, 3: Gift
    const typeSelect = form.querySelector(`select[name="type_id"]`);
    const priceInput = document.getElementById(`${idPrefix}-price`);
    const tradePrefInput = document.getElementById(`${idPrefix}-trade-pref`);

    const updateTypeUI = () => {
        if (!typeSelect) return;
        const val = parseInt(typeSelect.value);

        // Parent div wrappers in the transaction intent grid
        const priceCont = priceInput?.closest('div');
        const tradeCont = tradePrefInput?.closest('div');

        // Grab labels to dynamically update text for Swap vs Sale
        const priceLabel = form.querySelector(`label[for="${idPrefix}-price"]`);

        if (val === 1) { // Swap
            priceCont?.classList.remove('hidden');
            tradeCont?.classList.remove('hidden');
            if (priceLabel) priceLabel.textContent = 'Estimated Value ($)';
        } else if (val === 2) { // Sale
            priceCont?.classList.remove('hidden');
            tradeCont?.classList.add('hidden');
            if (priceLabel) priceLabel.textContent = 'Sale Price ($)';
        } else if (val === 3) { // Gift
            priceCont?.classList.add('hidden');
            tradeCont?.classList.add('hidden');
        }
    };

    if (typeSelect) {
        typeSelect.addEventListener('change', updateTypeUI);
        // Initial run to set visibility based on default or existing data (crucial for Edit mode)
        updateTypeUI();
    }

    // --- 2. Live Character Counter for Description ---
    const description = form.querySelector(`textarea[name="listing_description"]`);
    if (description) {
        let counter = form.querySelector('.description-counter');
        if (!counter) {
            counter = document.createElement('div');
            counter.className = 'description-counter text-[9px] font-black uppercase tracking-widest text-gray-400 mt-2 text-right pr-2';
            description.after(counter);
        }

        const updateCounter = () => {
            const length = description.value.length;
            counter.textContent = `${length} characters`;
            // Gonachi visual cue: Turn primary color when reaching 90% of capacity
            counter.classList.toggle('text-primary-500', length > 1800);
        };

        description.addEventListener('input', updateCounter);
        updateCounter();
    }
}