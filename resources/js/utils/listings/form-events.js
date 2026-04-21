// /resources/js/utils/listings/form-events.js

import { unitTypeToggleHouseLogic, loadDefaultRegions } from "../helpers.js";

/**
 * Handles UI-specific events for the Listing Form
 */
export function initListingFormEvents(formId, idPrefix) {
    const form = document.getElementById(formId);
    if (!form) return;

    // --- 1. Unit Type Toggle (House logic) ---
    unitTypeToggleHouseLogic(form, idPrefix);

    // --- 2. Category & Type Logic ---
    const categorySelect = form.querySelector(`select[name="category_id"]`);
    const typeSelect = form.querySelector(`select[name="category_type_id"]`);
    
    // Parse the full list of types from the data attribute in your renderer
    const allTypes = JSON.parse(categorySelect?.dataset.allTypes || '[]');

    // Containers for visibility toggling
    const categoryTypeContainer = typeSelect?.closest('div');
    // Targets the "Property Specs" and "Amenities" wrapper
    const specsAndAmenitiesContainer = form.querySelector('.lg\\:grid-cols-12.gap-6');
    
    // Targets for bottom section (Financials/Logistics)
    const priceInput = form.querySelector(`input[name="price"]`);
    const agreementSelect = form.querySelector(`select[name="agreement_type_id"]`);
    const moveInInput = form.querySelector(`input[name="move_in_date"]`);
    const youtubeContainer = form.querySelector(`input[name="youtube_url"]`)?.closest('[class*="col-span-"]');

    /**
     * Rebuilds the Type dropdown based on Category
     */
    const updateTypeOptions = (categoryId) => {
        if (!typeSelect) return;

        const currentVal = typeSelect.value;
        typeSelect.innerHTML = '<option value="">Select</option>';

        const filtered = allTypes.filter(t => t.category_id == categoryId);
        
        filtered.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.category_type_id;
            opt.textContent = t.category_type;
            // Maintain selection if it still exists in the new list
            if (t.category_type_id == currentVal) {
                opt.selected = true;
            }
            typeSelect.appendChild(opt);
        });
    };

    /**
     * Handles visibility and required-attribute toggling
     */
    const handleCategoryChange = (isInitialLoad = false) => {
        const val = categorySelect.value;
        const isService = (val == "2" || val == "3");
        
        // A. Filter the Type list (Skip rebuilding on init to respect SSR selection)
        if (!isInitialLoad) {
            updateTypeOptions(val);
        }

        // B. Toggle Category Type visibility (Hide if Category is 3)
        if (categoryTypeContainer) {
            val == "3" ? categoryTypeContainer.classList.add('hidden') : categoryTypeContainer.classList.remove('hidden');
        }
        
        // C. Toggle Main Specs & Amenities
        if (specsAndAmenitiesContainer) {
            isService ? specsAndAmenitiesContainer.classList.add('hidden') : specsAndAmenitiesContainer.classList.remove('hidden');
        }

        // D. Toggle Financials & YouTube Layout
        [priceInput, agreementSelect, moveInInput].forEach(el => {
            if (!el) return;
            const container = el.closest('div');
            if (isService) {
                container.classList.add('hidden');
                el.removeAttribute('required'); // Prevent ghost validation
            } else {
                container.classList.remove('hidden');
                // Only restore required if it's supposed to be (Price and Agreement)
                if (el.name !== 'move_in_date') el.setAttribute('required', 'required');
            }
        });

        // E. Adjust YouTube Width
        if (youtubeContainer) {
            if (isService) {
                youtubeContainer.classList.replace('md:col-span-4', 'md:col-span-4'); // Ensure it stays 4
                youtubeContainer.classList.remove('col-span-2');
                youtubeContainer.classList.add('col-span-2'); // Mobile col-span
            } else {
                // Restore original col spans if needed
            }
        }
    };

    if (categorySelect) {
        categorySelect.addEventListener('change', () => handleCategoryChange(false));
        handleCategoryChange(true); 
    }

    // --- 3. Move-in Date Constraints ---
    if (moveInInput) {
        moveInInput.min = new Date().toISOString().split("T")[0];
    }

    // --- 4. Amenities Selection Feedback ---
    const amenities = form.querySelectorAll('input[name="amenities[]"]');
    amenities.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const label = checkbox.closest('label');
            if (label) {
                checkbox.checked ? label.classList.add('text-primary-400') : label.classList.remove('text-primary-400');
            }
        });
    });

    loadDefaultRegions(idPrefix, form, 'listing-add');
}