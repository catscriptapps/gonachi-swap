import { fetchRegions } from "../api/regions-api.js";

/**
 * Enables dynamic loading of regions when the country select changes.
 */
export function enableDynamicRegionLoading(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    const countrySelect = form.querySelector('select[name="countryId"]');
    const regionSelect = form.querySelector('select[name="regionId"]');

    if (!countrySelect || !regionSelect) return;

    // We add 'event' (e) as a parameter here to catch custom data
    countrySelect.addEventListener('change', async (e) => {
        const countryId = countrySelect.value;
        
        // Grab the pre-selected ID if it was passed via CustomEvent
        const preSelectedId = e.detail?.preSelectedRegionId;

        if (!countryId) {
            regionSelect.innerHTML = '<option value="">Select Region</option>';
            return;
        }

        regionSelect.innerHTML = `<option value="">Loading...</option>`;
        regionSelect.disabled = true;

        try {
            const regions = await fetchRegions(countryId);
            
            // Build the options
            const optionsHtml = regions.map(r => {
                // Check if this region matches the one we want to auto-select
                const isSelected = preSelectedId && String(r.id) === String(preSelectedId);
                return `<option value="${r.id}" ${isSelected ? 'selected' : ''}>${r.name}</option>`;
            }).join('');

            regionSelect.innerHTML = `
                <option value="">Select Region</option>
                ${optionsHtml}
            `;
        } catch (error) {
            console.error('Failed to reload regions:', error);
            regionSelect.innerHTML = `<option value="">Error loading regions</option>`;
        } finally {
            regionSelect.disabled = false;
        }
    });
}