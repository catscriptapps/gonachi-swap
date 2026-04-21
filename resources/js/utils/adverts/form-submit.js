// /resources/js/utils/adverts/form-submit.js

import { FormValidator } from '../form-validator.js';
import { buttonSpinner } from '../spinner-utils.js';
import { AnimationEngine } from '../animations.js';
import { AdCounter } from './ad-counter-helper.js'; // 👈 Import the helper

/**
 * Maps form data to an API payload for Adverts
 */
function getPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    const idPrefix = form.id.includes('edit') ? 'ad-edit' : 'ad-add';
    const countryJsonInput = document.getElementById(`${idPrefix}-countries-hidden-json`);
    const isAllCountries = document.getElementById(`${idPrefix}-all-countries`)?.checked;
    
    let selectedCountries = [];
    if (isAllCountries) {
        selectedCountries = ['ALL'];
    } else if (countryJsonInput && countryJsonInput.value) {
        try {
            selectedCountries = JSON.parse(countryJsonInput.value);
        } catch(e) { 
            selectedCountries = []; 
        }
    }

    const selectedUserTypes = formData.getAll('selected_user_types[]');
    
    return {
        encoded_id: form.dataset.encodedId || null,
        title: data.title?.trim(),
        description: data.description?.trim(),
        keywords: data.keywords?.trim(),
        call_to_action_id: parseInt(data.call_to_action_id || 0), 
        landing_page_url: data.landing_page_url?.trim(),
        advert_package: parseInt(data.advert_package || 0),
        selected_countries: selectedCountries,
        selected_user_types: selectedUserTypes.map(id => parseInt(id)),
    };
}

/**
 * Handles Add/Edit Advert form submission
 */
export function handleAdFormSubmission(
    form, 
    mode, 
    modalInstance, 
    gridSelector = '#ads-grid'
) {
    if (form._adFormListenerAttached) return;
    form._adFormListenerAttached = true;

    const validator = new FormValidator(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    let apiMsg = form.querySelector('.api-message');

    if (!apiMsg) {
        apiMsg = document.createElement('div');
        apiMsg.className = 'api-message mt-4 transition-all duration-300';
        form.appendChild(apiMsg);
    }

    const originalLabel = submitBtn.innerHTML;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const countryError = form.querySelector('#country-error-slot');
        const userTypeError = form.querySelector('#user-type-error-slot');
        if (countryError) countryError.innerHTML = '';
        if (userTypeError) userTypeError.innerHTML = '';
        apiMsg.innerHTML = '';

        if (!validator.validateForEmptyFields(e)) return;

        if (typeof window.validateCountryTargeting === 'function') {
            if (!window.validateCountryTargeting()) return;
        }

        const userTypes = form.querySelectorAll('input[name="selected_user_types[]"]');
        const isChecked = Array.from(userTypes).some(cb => cb.checked);

        if (!isChecked) {
            if (userTypeError) {
                userTypeError.innerHTML = `
                <div class="flex items-center gap-2 mt-2 mb-3 p-3 rounded-xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-red-600 dark:text-red-500">
                        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-[10px] font-black text-red-700 dark:text-red-400 uppercase tracking-tight italic">
                        Selection Required: Pick at least one group
                    </span>
                </div>
                `;
            }
            
            const clearError = () => {
                if (userTypeError) userTypeError.innerHTML = '';
                userTypes.forEach(cb => cb.removeEventListener('change', clearError));
            };

            userTypes.forEach(cb => cb.addEventListener('change', clearError));
            userTypes[0]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return; 
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 
        apiMsg.innerHTML = '';

        try {
            const payload = getPayload(form);
            if (mode === 'edit') payload._method = 'PUT';

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/adverts`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success) {
                const grid = document.querySelector(gridSelector);
                
                if (submitBtn) {
                    submitBtn.style.display = 'none'; 
                }

                if (grid) {
                    if (mode === 'edit' && result.cardHtml) {
                        const existingCard = document.querySelector(`.ad-card-wrapper[data-encoded-id="${payload.encoded_id}"]`);
                        
                        if (existingCard) {
                            existingCard.outerHTML = result.cardHtml;
                        } else {
                            const fallbackCard = document.getElementById(`ad-card-${result.data?.advert_id}`);
                            if (fallbackCard) fallbackCard.outerHTML = result.cardHtml;
                        }
                        
                        // 🚀 UPDATE COUNTER (Refresh current count in case of weirdness)
                        AdCounter.update(result.total);

                    } else if (result.cardHtml) {
                        // 1. Correctly target the Gonachi empty state ID
                        const emptyState = document.getElementById('empty-ads-state'); 
                        if (emptyState) emptyState.remove();

                        if (grid) {
                            grid.classList.remove('hidden'); // This brings the grid back to life
                            grid.insertAdjacentHTML('afterbegin', result.cardHtml);
                        }
                                                
                        // 4. Update Counter (Ensure AdCounter uses 'ads-counter-number')
                        if (typeof AdCounter !== 'undefined') {
                            AdCounter.update(result.total);
                        }
                    }
                    
                    if (typeof AnimationEngine !== 'undefined') {
                        AnimationEngine.refresh();
                    }
                }

                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2 animate-bounce-in">
                        <i class="bi bi-check-all text-xl"></i>
                        ${result.messages?.[0] || 'Advert saved successfully.'}
                    </div>
                `;

                setTimeout(() => {
                    if (modalInstance && typeof modalInstance.close === 'function') {
                        modalInstance.close();
                    }
                }, 1500);

            } else {
                apiMsg.innerHTML = (result.messages || ['Error saving advert']).map(msg => `
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">${msg}</div>
                `).join('');
            }

        } catch (err) {
            console.error('Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">Server communication failed.</div>`;
        } finally {
            if (submitBtn && submitBtn.style.display !== 'none') {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalLabel;
            }
        }
    });
}