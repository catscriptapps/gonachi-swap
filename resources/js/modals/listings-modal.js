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
 * Helper to fetch all dependencies from unified Listing API
 */
async function fetchListingDependencies(countryId = '') {
    const [
        countries,
        regions,
        unitTypes,
        houseTypes,
        agreements,
        amenitiesList,
        categories,
        categoryTypes,
        bedrooms,
        bathrooms
    ] = await Promise.all([
        fetchCountries(),
        fetchRegions(countryId),
        ListingAPI.fetchUnitTypes(),
        ListingAPI.fetchHouseTypes(),
        ListingAPI.fetchAgreementTypes(), // Updated to match consolidated name
        ListingAPI.fetchAmenities(),
        ListingAPI.fetchListingCategories(),
        ListingAPI.fetchListingCategoryTypes(),
        ListingAPI.fetchBedrooms(),
        ListingAPI.fetchBathrooms()
    ]);

    return { 
        countries, regions, unitTypes, houseTypes, 
        agreements, amenitiesList, categories, categoryTypes,
        bedrooms, bathrooms
    };
}

// --- Add Listing ---
async function openAddListingModal() {
    const deps = await fetchListingDependencies('');

    if (listingModal) listingModal.destroy();

    // Define empty defaults so listingForm doesn't crash on undefined properties
    const emptyListingData = {
        encodedId: '',
        listing_title: '',
        listing_description: '',
        city: '',
        address: '',
        price: '',
        property_size: '',
        move_in_date: '',
        youtube_url: '',
        contact_phone: '',
        is_ac: 0,
        is_furnished: 0,
        parking: 0,
        pets_allowed: 0,
        countryId: window.APP_CONFIG.userDefaults.country_id,
        regionId: window.APP_CONFIG.userDefaults.region_id,
        category_id: '',
        category_type_id: '',
        unit_type_id: '',
        house_type_id: '',
        bedroom_id: '',
        bathroom_id: '',
        agreement_type_id: '',
        selectedAmenities: []
    };

    listingModal = new Modal({
        id: 'add-listing-modal',
        title: 'Post a New Property Listing',
        content: listingForm({
            mode: 'add',
            formId: 'listing-add-form',
            buttonLabel: 'Post Listing',
            ...emptyListingData, // Spread the defaults here!
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

    const viewModal = document.getElementById('view-listing-modal');
    if (viewModal) {
        viewModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    const d = btn.dataset;
    
    // Explicitly mapping dataset to Model fields to ensure no value is lost
    const listingData = {
        encodedId: d?.encodedId || '',
        listing_title: d?.listingTitle || '',
        listing_description: d?.listingDescription || '',
        city: d?.city || '',
        address: d?.address || '',
        price: d?.price || '',
        property_size: d?.propertySize || '',
        move_in_date: d?.moveInDate || d?.availableDate || '',
        youtube_url: d?.youtubeUrl || '',
        contact_phone: d?.contactPhone || '',
        
        // Booleans: Ensure we handle undefined safely
        is_ac: d?.isAc !== undefined ? parseInt(d.isAc) : 0,
        is_furnished: d?.isFurnished !== undefined ? parseInt(d.isFurnished) : 0,
        parking: d?.parking !== undefined ? parseInt(d.parking) : 0,
        pets_allowed: d?.petsAllowed !== undefined ? parseInt(d.petsAllowed) : 0,
        
        // IDs
        countryId: d?.countryId || '', 
        regionId: d?.regionId || '',
        category_id: d?.categoryId || '',
        category_type_id: d?.categoryTypeId || '',
        unit_type_id: d?.unitTypeId || '',
        house_type_id: d?.houseTypeId || '',
        bedroom_id: d?.bedroomId || '',
        bathroom_id: d?.bathroomId || '',
        agreement_type_id: d?.agreementTypeId || '',
        
        // Amenities: Safely parse JSON
        selectedAmenities: (() => {
            try {
                return d?.amenities ? JSON.parse(d.amenities) : [];
            } catch (e) {
                console.warn("Failed to parse amenities JSON", e);
                return [];
            }
        })()
    };

    const deps = await fetchListingDependencies(listingData.country_id);

    if (listingModal) listingModal.destroy();

    listingModal = new Modal({
        id: 'edit-listing-modal',
        title: 'Edit Property Listing',
        content: listingForm({
            mode: 'edit',
            formId: 'listing-edit-form',
            buttonLabel: 'Update Listing',
            ...listingData,
            ...deps
        }),
        size: 'lg',
        showFooter: false,
    });

    listingModal.open();
    initFormFeatures('listing-edit-form', 'edit', listingModal);

    // MANUALLY TRIGGER REGION SELECTION FOR EDIT MODE
    const editForm = document.getElementById('listing-edit-form');
    const countrySelect = editForm.querySelector('select[name="countryId"]');

    if (countrySelect && countrySelect.value) {
        const event = new CustomEvent('change', { 
            detail: { preSelectedRegionId: listingData.regionId } 
        });
        countrySelect.dispatchEvent(event);
    }
}

let listenersAttached = false;
export function initListingsModal() {
    if (listenersAttached) return;

    document.addEventListener('click', (e) => {
        const addBtn = e.target.closest('#create-new-listing-btn') || e.target.closest('.create-listing-trigger');
        if (addBtn) {
            e.preventDefault();
            openAddListingModal();
            return;
        }

        const editBtn = e.target.closest('.edit-listing-btn') || e.target.closest('#view-listing-edit-btn');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation(); 
            openEditListingModal(editBtn);
        }
    });

    listenersAttached = true;
}