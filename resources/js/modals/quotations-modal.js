// /resources/js/modals/quotations-modal.js

import { Modal } from '../factories/modal-factory.js';
import { quotationForm } from '../forms/quotation-form.js';
import { fetchCountries } from '../api/countries-api.js';
import { fetchRegions } from '../api/regions-api.js';
import { fetchSkilledTrades } from '../api/skilled-trades-api.js';
import { fetchContractorTypes } from '../api/contractor-types-api.js';
import { fetchUnitTypes } from '../api/unit-types-api.js';
import { fetchHouseTypes } from '../api/house-types-api.js';
import { fetchQuotationTypes } from '../api/quotation-types-api.js';
import { fetchQuotationDestinations } from '../api/quotation-destinations-api.js';
import { handleQuoteFormSubmission } from '../utils/quotations/form-submit.js';
import { enableDynamicRegionLoading } from '../components/regions-component.js';
import { initQuotationFormEvents } from '../utils/quotations/form-events.js';

let quoteModal = null;

/**
 * Initialize form features after the modal opens
 */
function initFormFeatures(formId, mode, modalInstance) {
    const form = document.getElementById(formId);
    if (!form) return;

    const idPrefix = mode === 'add' ? 'quote-add' : 'quote-edit';

    handleQuoteFormSubmission(form, mode, modalInstance);
    enableDynamicRegionLoading(formId);
    initQuotationFormEvents(formId, idPrefix);
}

/**
 * Helper to fetch all dependencies
 */
async function fetchQuotationDependencies(countryId = '') {
    const [
        countries,
        regions,
        trades,
        contractorTypes,
        unitTypes,
        houseTypes,
        quoteTypes,
        destinations
    ] = await Promise.all([
        fetchCountries(),
        fetchRegions(countryId),
        fetchSkilledTrades(),
        fetchContractorTypes(),
        fetchUnitTypes(),
        fetchHouseTypes(),
        fetchQuotationTypes(),
        fetchQuotationDestinations()
    ]);

    return { countries, regions, trades, contractorTypes, unitTypes, houseTypes, quoteTypes, destinations };
}

// --- Add Quotation ---
async function openAddQuoteModal() {
    const deps = await fetchQuotationDependencies('');

    if (quoteModal) quoteModal.destroy();

    // Define empty defaults so quoteForm doesn't crash on undefined properties
    const emptyQuoteData = {
        encodedId: '',
        countryId: window.APP_CONFIG.userDefaults.country_id,
        regionId: window.APP_CONFIG.userDefaults.region_id,
        title: '',
        description: '',
        city: '',
        budget: '',
        startDate: '',
        finishDate: '',
        startTime: '',
        finishTime: '',
        contactPhone: '',
        youtubeUrl: '',
        notify: 'no',
        contractorTypeId: '',
        skilledTradeId: '',
        unitTypeId: '',
        houseTypeId: '',
        quotationTypeId: '',
        quotationDestId: '',
    };

    quoteModal = new Modal({
        id: 'add-quote-modal',
        title: 'Post a New Quotation Request',
        content: quotationForm({
            mode: 'add',
            formId: 'quote-add-form',
            buttonLabel: 'Post Request',
            ...emptyQuoteData, // Spread the defaults here!
            ...deps
        }),
        size: 'lg',
        showFooter: false,
    });

    quoteModal.open();
    initFormFeatures('quote-add-form', 'add', quoteModal);
}

// --- Edit Quotation ---
export async function openEditQuoteModal(trigger) {
    // Mirroring Ad logic: Ensure we have the right trigger
    const btn = trigger.closest('.edit-quote-btn') || trigger.closest('#view-quote-edit-btn') || trigger;
    if (!btn?.dataset) return;

    // If coming from the View Modal, hide it first
    const viewModal = document.getElementById('view-quotation-modal');
    if (viewModal) {
        viewModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    const data = btn.dataset;
    const countryId = data.countryId || '';
    const deps = await fetchQuotationDependencies(countryId);

    if (quoteModal) quoteModal.destroy();

    quoteModal = new Modal({
        id: 'edit-quote-modal',
        title: 'Edit Quotation Request',
        content: quotationForm({
            mode: 'edit',
            formId: 'quote-edit-form',
            buttonLabel: 'Update Request',
            ...data,
            ...deps,
            countryId: countryId,
            regionId: data.regionId
        }),
        size: 'lg',
        showFooter: false,
    });

    quoteModal.open();
    initFormFeatures('quote-edit-form', 'edit', quoteModal);

    // MANUALLY TRIGGER REGION SELECTION FOR EDIT MODE
    const editForm = document.getElementById('quote-edit-form');
    const countrySelect = editForm.querySelector('select[name="countryId"]');

    if (countrySelect && countrySelect.value) {
        const event = new CustomEvent('change', { 
            detail: { preSelectedRegionId: data.regionId } 
        });
        countrySelect.dispatchEvent(event);
    }
}

let listenersAttached = false;
export function initQuotationsModal() {
    if (listenersAttached) return;

    document.addEventListener('click', (e) => {
        // 1. ADD TRIGGER
        const addBtn = e.target.closest('#create-new-quote-btn') || e.target.closest('.create-quote-trigger');
        if (addBtn) {
            e.preventDefault();
            openAddQuoteModal();
            return;
        }

        // 2. EDIT TRIGGER (From Card OR View Modal)
        const editBtn = e.target.closest('.edit-quote-btn') || e.target.closest('#view-quote-edit-btn');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation(); 
            openEditQuoteModal(editBtn);
        }
    });

    listenersAttached = true;
}