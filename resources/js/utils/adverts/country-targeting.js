// /resources/js/utils/adverts/country-targeting.js

/**
 * Handles the "Legacy Style" Country Targeting logic.
 * Features: Padded select, "All" checkbox toggle, Badge Cloud, and Validation.
 */
export function initCountryTargeting({ idPrefix, initialSelection }) {
    const selector = document.getElementById(`${idPrefix}-country-selector`);
    const allCheckbox = document.getElementById(`${idPrefix}-all-countries`);
    const bucket = document.getElementById(`${idPrefix}-selected-bucket`);
    const hiddenInput = document.getElementById(`${idPrefix}-countries-hidden-json`);
    const errorMsg = document.getElementById(`${idPrefix}-country-error`);

    if (!selector || !allCheckbox || !bucket || !hiddenInput) {
        console.warn("Country targeting elements missing for prefix:", idPrefix);
        return;
    }

    let selected = Array.isArray(initialSelection) ? initialSelection.map(String) : [];

    const render = () => {
        // --- Validation Check ---
        const isValid = allCheckbox.checked || selected.length > 0;
        
        if (isValid) {
            errorMsg?.classList.add('hidden');
            bucket.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/10');
        }

        if (allCheckbox.checked) {
            bucket.innerHTML = `
                <div class="flex items-center gap-2 px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-orange-200 dark:border-orange-800 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V9a2 2 0 002 2h.5a.5.5 0 01.5.5v.5a.5.5 0 01-.5.5h-2a.5.5 0 01-.5-.5V11a2 2 0 00-2-2 2 2 0 00-2 2v1c0 1.1.9 2 2 2h2a2 2 0 002-2v-.5a.5.5 0 01.5-.5h.5a1.5 1.5 0 001.5-1.5V9a1.5 1.5 0 00-1.5-1.5h-.5A.5.5 0 0113 7V6.5a1.5 1.5 0 00-1.5-1.5h-.5a.5.5 0 01-.5-.5v-.134A6.002 6.002 0 0110 4c1.657 0 3.156.672 4.243 1.757A1.5 1.5 0 1116.5 8.5h-.5a.5.5 0 00-.5.5v.5a.5.5 0 00.5.5h.5a6.002 6.002 0 01-11.668-1.973z" clip-rule="evenodd" />
                    </svg>
                    All Countries Selected
                </div>`;
            hiddenInput.value = JSON.stringify([]); 
            selector.disabled = true;
            return;
        }

        selector.disabled = false;
        if (selected.length === 0) {
            bucket.innerHTML = '<span class="text-gray-400 text-[11px] font-bold uppercase italic p-2 tracking-tight">No countries selected</span>';
            hiddenInput.value = JSON.stringify([]);
            return;
        }

        bucket.innerHTML = selected.map(id => {
            const option = selector.querySelector(`option[value="${id}"]`);
            const name = option ? option.dataset.name : `ID: ${id}`;
            return `
            <div class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm transition-all group">
                <span class="text-[11px] font-black text-navy-900 dark:text-gray-200 uppercase tracking-tighter">${name.trim()}</span>
                <button type="button" class="remove-country-btn text-red-400 hover:text-red-600 transition-colors flex items-center justify-center p-0.5" data-id="${id}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 pointer-events-none">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </button>
            </div>`;
        }).join('');

        hiddenInput.value = JSON.stringify(selected);
    };

    /**
     * Public validation method attached to window for the Form Submitter
     */
    window.validateCountryTargeting = () => {
        const isValid = allCheckbox.checked || selected.length > 0;
        if (!isValid) {
            errorMsg?.classList.remove('hidden');
            bucket.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/10');
            bucket.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return isValid;
    };

    // --- Events ---

    selector.addEventListener('change', (e) => {
        const val = e.target.value;
        if (val && !selected.includes(val)) {
            selected.push(val);
            render();
        }
        e.target.value = ""; 
    });

    bucket.addEventListener('click', (e) => {
        const btn = e.target.closest('.remove-country-btn'); 
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            const idToRemove = btn.getAttribute('data-id'); 
            selected = selected.filter(id => id !== idToRemove);
            render(); 
        }
    });

    allCheckbox.addEventListener('change', () => {
        if (allCheckbox.checked) selected = []; 
        render();
    });

    render();
}