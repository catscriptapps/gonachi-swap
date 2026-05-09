// /resources/js/modals/listings-modal.js

import { Modal } from '../factories/modal-factory.js';
import { listingForm } from '../forms/listing-form.js';
import { fetchCountries } from '../api/countries-api.js';
import { fetchRegions } from '../api/regions-api.js';
import * as ListingAPI from '../api/listings-api.js';

import { handleListingFormSubmission } from '../utils/listings/form-submit.js';
import { enableDynamicRegionLoading } from '../components/regions-component.js';
import { initListingFormEvents } from '../utils/listings/form-events.js';

let listingModal = null;

/**
 * Initialize form features after the modal opens
 */
function initFormFeatures(formId, mode, modalInstance) {
    const form = document.getElementById(formId);
    if (!form) return;

    const idPrefix = mode === 'add' ? 'listing-add' : 'listing-edit';

    handleListingFormSubmission(form, mode, modalInstance);
    enableDynamicRegionLoading(formId);
    initListingFormEvents(formId, idPrefix);
}

/**
 * Helper to fetch all dependencies for the Listing Form
 */
async function fetchListingDependencies(countryId = '') {
    const [
        countries,
        regions,
        categories,
        types,
        conditions
    ] = await Promise.all([
        fetchCountries(),
        fetchRegions(countryId),
        ListingAPI.fetchListingCategories(), // Uses your ListingCategory model
        ListingAPI.fetchListingTypes(),      // 1: Swap, 2: Sale, 3: Gift
        ListingAPI.fetchListingConditions()   // 1: New, 2: Like New, etc.
    ]);

    return { countries, regions, categories, types, conditions };
}

// --- Add Listing ---
async function openAddListingModal() {
    const deps = await fetchListingDependencies('');

    if (listingModal) listingModal.destroy();

    const emptyListingData = {
        listingId: '',
        countryId: window.APP_CONFIG.userDefaults.country_id,
        regionId: window.APP_CONFIG.userDefaults.region_id,
        listingTitle: '',
        listingDescription: '',
        categoryId: '',
        typeId: 1, // Default to Swap
        conditionId: 3, // Default to Used
        price: '',
        tradePref: '',
        city: '',
        youtubeUrl: '',
        contactPhone: '',
        statusId: 1 // Default to Posted
    };

    listingModal = new Modal({
        id: 'add-listing-modal',
        title: 'Post a New Swap',
        content: listingForm({
            mode: 'add',
            formId: 'listing-add-form',
            buttonLabel: 'Post Listing',
            ...emptyListingData,
            ...deps
        }),
        size: 'lg',
        showFooter: false,
    });

    listingModal.open();
    initFormFeatures('listing-add-form', 'add', listingModal);
}

// --- Edit Listing ---
export async function openEditListingModal(trigger) {
    const btn = trigger.closest('.edit-listing-btn') || trigger.closest('#view-listing-edit-btn') || trigger;
    if (!btn?.dataset) return;

    // Close view modal if it exists
    const viewModal = document.getElementById('view-listing-modal');
    if (viewModal) {
        viewModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    const data = btn.dataset;
    const countryId = data.countryId || '';
    const deps = await fetchListingDependencies(countryId);

    if (listingModal) listingModal.destroy();

    listingModal = new Modal({
        id: 'edit-listing-modal',
        title: 'Edit Your Listing',
        content: listingForm({
            mode: 'edit',
            formId: 'listing-edit-form',
            buttonLabel: 'Update Listing',
            ...data,
            ...deps,
            countryId: countryId,
            regionId: data.regionId
        }),
        size: 'lg',
        showFooter: false,
    });

    listingModal.open();
    initFormFeatures('listing-edit-form', 'edit', listingModal);

    // Sync regions for Edit Mode
    const editForm = document.getElementById('listing-edit-form');
    const countrySelect = editForm.querySelector('select[name="countryId"]');

    if (countrySelect && countrySelect.value) {
        const event = new CustomEvent('change', {
            detail: { preSelectedRegionId: data.regionId }
        });
        countrySelect.dispatchEvent(event);
    }
}

let listenersAttached = false;
export function initListingsModal() {
    if (listenersAttached) return;

    document.addEventListener('click', (e) => {
        // 1. ADD TRIGGER
        const addBtn = e.target.closest('#create-new-listing-btn') || e.target.closest('.create-listing-trigger');
        if (addBtn) {
            e.preventDefault();
            openAddListingModal();
            return;
        }

        // 2. EDIT TRIGGER
        const editBtn = e.target.closest('.edit-listing-btn') || e.target.closest('#view-listing-edit-btn');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            openEditListingModal(editBtn);
        }
    });

    listenersAttached = true;
}