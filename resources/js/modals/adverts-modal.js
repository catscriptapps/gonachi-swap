// /resources/js/modals/adverts-modal.js

import { Modal } from '../factories/modal-factory.js';
import { advertForm } from '../forms/advert-form.js';
import { fetchCountries } from '../api/countries-api.js';
import { fetchUserTypes } from '../api/user-types-api.js';
import { fetchAdvertCtas } from '../api/advert-ctas-api.js'; 
import { fetchAdvertPackages } from '../api/advert-packages.js'; // Added
import { handleAdFormSubmission } from '../utils/adverts/form-submit.js';
import { initCountryTargeting } from '../utils/adverts/country-targeting.js';
import { initAudienceToggle } from '../utils/adverts/audience-toggle.js';

let adModal = null;

/**
 * Initialize form features after the modal opens
 */
function initFormFeatures(formId, mode, modalInstance, initialData = {}) {
    const form = document.getElementById(formId);
    if (!form) return;

    const idPrefix = mode === 'add' ? 'ad-add' : 'ad-edit';

    // 1. Initialize our shiny new utility
    initCountryTargeting({
        idPrefix: idPrefix,
        initialSelection: initialData.selectedCountries || []
    });

    initAudienceToggle(idPrefix);

    // 2. EXTRA FIX: If "ALL" is in the data, check the "All Countries" box
    if (initialData.selectedCountries && initialData.selectedCountries.includes('ALL')) {
        const allCheckbox = document.getElementById(`${idPrefix}-all-countries`);
        if (allCheckbox) {
            allCheckbox.checked = true;
            allCheckbox.dispatchEvent(new Event('change'));
        }
    }

    handleAdFormSubmission(form, mode, modalInstance);
}

// --- Add Advert ---
async function openAddAdModal() {
    // Fetch all 4 dependencies now including packages
    const [countries, availableRoles, availableCtas, availablePackages] = await Promise.all([
        fetchCountries(),
        fetchUserTypes(),
        fetchAdvertCtas(),
        fetchAdvertPackages() // Added
    ]);

    if (adModal) adModal.destroy();

    adModal = new Modal({
        id: 'add-ad-modal',
        title: 'New Advert Application',
        content: advertForm({
            mode: 'add',
            formId: 'ad-add-form',
            buttonLabel: 'Submit Application',
            countries,
            availableRoles,
            availableCtas,
            availablePackages // Pass to form
        }),
        size: 'lg',
        showFooter: false,
    });

    adModal.open();

    setTimeout(() => {
        initFormFeatures('ad-add-form', 'add', adModal);
    }, 50);
}

// --- Edit Advert ---
export async function openEditAdModal(trigger) {
    const btn = trigger.closest('.edit-ad-btn') || trigger;
    if (!btn?.dataset) return;

    const data = btn.dataset;

    let selectedCountries = [];
    let selectedUserTypes = [];
    try {
        selectedCountries = JSON.parse(data.selectedCountries || '[]');
        selectedUserTypes = JSON.parse(data.selectedUserTypes || '[]').map(id => Number(id));
    } catch (e) {
        console.error("Error parsing ad targeting data:", e);
    }

    // Fetch all 4 dependencies
    const [countries, availableRoles, availableCtas, availablePackages] = await Promise.all([
        fetchCountries(),
        fetchUserTypes(),
        fetchAdvertCtas(),
        fetchAdvertPackages() // Added
    ]);

    if (adModal) adModal.destroy();

    adModal = new Modal({
        id: 'edit-ad-modal',
        title: 'Edit Advert',
        content: advertForm({
            mode: 'edit',
            formId: 'ad-edit-form',
            encodedId: data.encodedId,
            title: data.title,
            description: data.description,
            callToActionId: data.callToActionId,
            keywords: data.keywords,
            landingPageUrl: data.landingPageUrl,
            advertPackage: parseInt(data.advertPackage || 0),
            selectedCountries,
            selectedUserTypes,
            countries,
            availableRoles,
            availableCtas,
            availablePackages, // Pass to form
            buttonLabel: 'Update Advert'
        }),
        size: 'lg',
        showFooter: false,
    });

    adModal.open();

    initFormFeatures('ad-edit-form', 'edit', adModal, {
        selectedCountries: selectedCountries 
    });
}

export function initAdvertsModal() {
    if (window._adModalListenersAttached) return;

    document.addEventListener('click', (e) => {
        // 1. ADD TRIGGER
        const addBtn = e.target.closest('.create-ad-trigger');
        if (addBtn) {
            e.preventDefault();
            openAddAdModal();
            return;
        }

        // 2. EDIT TRIGGER (Handles both Card button AND View Modal button)
        const editBtn = e.target.closest('.edit-ad-btn') || e.target.closest('#view-ad-edit-btn');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            // If it's the button inside the view modal, hide the view modal first
            const viewModal = document.getElementById('view-ad-modal');
            if (viewModal) {
                viewModal.classList.add('hidden');
                document.body.style.overflow = '';
            }

            openEditAdModal(editBtn);
        }
    });

    window._adModalListenersAttached = true;
}