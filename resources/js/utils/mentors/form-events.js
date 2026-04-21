// /resources/js/utils/mentors/form-events.js

import { loadDefaultRegions } from "../helpers.js";
/**
 * Handles UI-specific events for the Mentor Registration Form
 */
export function initMentorFormEvents(formId, idPrefix) {
    const form = document.getElementById(formId);
    if (!form) return;

    // --- 1. Experience Years Validation ---
    const expInput = document.getElementById(`${idPrefix}-exp`);
    if (expInput) {
        expInput.addEventListener('input', (e) => {
            // Mentors can't have negative years, and let's cap it at 60 (clinical safety)
            let val = parseInt(e.target.value);
            if (val < 0) e.target.value = 0;
            if (val > 60) e.target.value = 60;
        });
    }

    // --- 2. Skills Auto-Formatter ---
    const skillsInput = document.getElementById(`${idPrefix}-skills`);
    if (skillsInput) {
        skillsInput.addEventListener('blur', (e) => {
            // Clean up the comma-separated list for a professional look
            const cleaned = e.target.value
                .split(',')
                .map(skill => skill.trim())
                .filter(skill => skill.length > 0)
                .join(', ');
            
            e.target.value = cleaned;
        });
    }

    // --- 3. URL Protocol Enforcer ---
    // Ensures links start with http/https if the user forgets
    const urlInputs = form.querySelectorAll('input[type="url"]');
    urlInputs.forEach(input => {
        input.addEventListener('blur', (e) => {
            let val = e.target.value.trim();
            if (val && !/^https?:\/\//i.test(val)) {
                e.target.value = `https://${val}`;
            }
        });
    });
        
    loadDefaultRegions(idPrefix, form, 'mentor-add');
}