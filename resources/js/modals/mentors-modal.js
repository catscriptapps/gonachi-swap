// /resources/js/modals/mentors-modal.js

import { Modal } from '../factories/modal-factory.js';
import { mentorForm } from '../forms/mentor-form.js';
import { fetchCountries } from '../api/countries-api.js';
import { fetchRegions } from '../api/regions-api.js';
import { fetchUserTypes } from '../api/user-types-api.js';
import { handleMentorFormSubmission } from '../utils/mentors/form-submit.js';
import { enableDynamicRegionLoading } from '../components/regions-component.js';
import { initMentorFormEvents } from '../utils/mentors/form-events.js';

let mentorModal = null;

/**
 * Initialize form features after the modal opens
 */
function initFormFeatures(formId, mode, modalInstance) {
    const form = document.getElementById(formId);
    if (!form) return;

    const idPrefix = mode === 'add' ? 'mentor-add' : 'mentor-edit';

    handleMentorFormSubmission(form, mode, modalInstance);
    enableDynamicRegionLoading(formId);
    initMentorFormEvents(formId, idPrefix);
}

/**
 * Helper to fetch all dependencies for the mentor form
 */
async function fetchMentorDependencies(countryId = '') {
    const [
        countries,
        regions,
        userTypes
    ] = await Promise.all([
        fetchCountries(),
        fetchRegions(countryId),
        fetchUserTypes() 
    ]);

    const mentorTypes = (userTypes || []).filter(type => {
        if (!type || !type.name) return false; 
        return type.name.toLowerCase() !== 'admin';
    });

    return { countries, regions, mentorTypes };
}

// --- Add/Register Mentor ---
async function openAddMentorModal() {
    const deps = await fetchMentorDependencies('');

    if (mentorModal) mentorModal.destroy();

    // Define empty defaults so quoteForm doesn't crash on undefined properties
    const emptyMentorData = {
        encodedId: '',
        countryId: window.APP_CONFIG.userDefaults.country_id,
        regionId: window.APP_CONFIG.userDefaults.region_id,
        headline: '',
        bio: '',
        experienceYears: '',
        skills: '', // Comma separated
        websiteUrl: '',
        youtubeUrl: '', // Replaced LinkedIn with YouTube 🎥
        city: '',
        
        // IDs for selections
        userTypeId: '', 
    };

    mentorModal = new Modal({
        id: 'mentor-registration-modal',
        title: 'Become a Professional Mentor',
        content: mentorForm({
            mode: 'add',
            formId: 'mentor-add-form',
            buttonLabel: 'Register as Mentor',
            ...emptyMentorData, // Spread the defaults here!
            ...deps
        }),
        size: 'lg',
        showFooter: false,
    });

    mentorModal.open();
    initFormFeatures('mentor-add-form', 'add', mentorModal);
}

// --- Edit Mentor Profile ---
export async function openEditMentorModal(trigger) {
    const btn = trigger.closest('.edit-mentor-btn') || trigger;
    if (!btn?.dataset) return;

    const ds = btn.dataset;
    const countryId = ds.countryId || '';
    
    // Fetch dependencies (especially regions for the specific country)
    const deps = await fetchMentorDependencies(countryId);

    if (mentorModal) mentorModal.destroy();

    mentorModal = new Modal({
        id: 'edit-mentor-modal',
        title: 'Edit Mentor Profile',
        content: mentorForm({
            mode: 'edit',
            formId: 'mentor-edit-form',
            buttonLabel: 'Update Profile',
            
            // Explicitly map Dataset to MentorForm props
            encodedId:      ds.encodedId,
            headline:       ds.headline,
            bio:            ds.bio,
            city:           ds.city,
            experienceYears: ds.experienceYears, // data-experience-years
            youtubeUrl:     ds.youtubeUrl,      // data-youtube-url
            websiteUrl:     ds.websiteUrl,      // data-website-url
            skills:         JSON.parse(ds.skillsJson || '[]').join(', '), // Convert JSON array back to comma string
            
            countryId:      countryId,
            regionId:       ds.regionId,
            userTypeId:     ds.targetTypeId,    // data-target-type-id
            
            ...deps // countries, regions, mentorTypes
        }),
        size: 'lg',
        showFooter: false,
    });

    mentorModal.open();
    initFormFeatures('mentor-edit-form', 'edit', mentorModal);

    // MANUALLY TRIGGER REGION SELECTION FOR EDIT MODE
    const editForm = document.getElementById('mentor-edit-form');
    const countrySelect = editForm.querySelector('select[name="countryId"]');

    if (countrySelect && countrySelect.value) {
        const event = new CustomEvent('change', { 
            detail: { preSelectedRegionId: data.regionId } 
        });
        countrySelect.dispatchEvent(event);
    }
}

let listenersAttached = false;
export function initMentorsModal() {
    if (listenersAttached) return;

    document.addEventListener('click', (e) => {
        // 1. REGISTER/ADD TRIGGER
        const addBtn = e.target.closest('.register-mentor-trigger');
        if (addBtn) {
            e.preventDefault();
            openAddMentorModal();
            return;
        }

        // 2. EDIT TRIGGER
        const editBtn = e.target.closest('.edit-mentor-btn');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            openEditMentorModal(editBtn);
        }
    });

    listenersAttached = true;
}