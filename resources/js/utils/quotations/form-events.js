// /resources/js/utils/quotations/form-events.js

import { unitTypeToggleHouseLogic, loadDefaultRegions } from "../helpers.js";

/**
 * Handles UI-specific events for the Quotation Form
 */
export function initQuotationFormEvents(formId, idPrefix) {
    const form = document.getElementById(formId);
    if (!form) return;

    // --- 1. Unit Type Toggle (House logic) ---
    unitTypeToggleHouseLogic(form, idPrefix);

    // --- 2. Date & Time Constraints ---
    const startInput = document.getElementById(`${idPrefix}-start-date`);
    const finishInput = document.getElementById(`${idPrefix}-finish-date`);
    
    if (startInput) {
        // Prevent past dates for the project start
        const today = new Date().toISOString().split("T")[0];
        startInput.min = today;

        // Ensure Finish Date cannot be before Start Date
        startInput.addEventListener('change', (e) => {
            if (finishInput) {
                finishInput.min = e.target.value;
            }
        });
    }
    
    loadDefaultRegions(idPrefix, form, 'quote-add');
}