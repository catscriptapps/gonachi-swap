// /resources/js/utils/mentors/form-submit.js

import { FormValidator } from '../../utils/form-validator.js';
import { buttonSpinner } from '../../utils/spinner-utils.js';
import { AnimationEngine } from '../../utils/animations.js';
import { showToast } from '../../ui/toast.js';

function getMentorPayload(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Mapping form names to Mentor.php model fields 💎
    return {
        encoded_id: form.dataset.encodedId || null,
        headline: data.headline?.trim(),          // Changed from mentor_headline
        bio: data.bio?.trim(),                    // Changed from mentor_bio
        skills: data.skills?.trim(),              // Changed from mentor_skills
        years_experience: parseInt(data.years_experience || 0),
        target_user_type_id: parseInt(data.target_user_type_id || 0),
        country_id: parseInt(data.countryId || 0),
        region_id: parseInt(data.regionId || 0),
        city: data.city?.trim(),
        website_url: data.website_url?.trim(),
        youtube_url: data.youtube_url?.trim()     // Replaced LinkedIn with YouTube
    };
}

export function handleMentorFormSubmission(form, mode, modalInstance, gridSelector = '#mentors-grid') {
    if (form._mentorFormListenerAttached) return;
    form._mentorFormListenerAttached = true;

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
        apiMsg.innerHTML = '';

        // Now run the validator
        if (!validator.validateForEmptyFields(e)){
            showToast('* All required fields MUST be provided.', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = buttonSpinner; 

        try {
            const payload = getMentorPayload(form);
            if (mode === 'edit') payload._method = 'PUT';

            const baseUrl = window.APP_CONFIG?.baseUrl || '/';
            const response = await fetch(`${baseUrl}api/mentors`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (result.success && result.cardHtml) {
                const grid = document.querySelector(gridSelector);
                
                if (mode === 'edit') {
                    // Update existing Mentor card
                    const existingCard = document.querySelector(`.mentor-card-wrapper[data-encoded-id="${payload.encoded_id}"]`);
                    if (existingCard) {
                        existingCard.outerHTML = result.cardHtml;
                    }
                } else {
                    const emptyState = document.getElementById('empty-mentors-state');
                    const counter = document.querySelector('.active-mentors-count'); 

                    if (emptyState) emptyState.remove();
                    
                    if (grid) {
                        grid.classList.remove('hidden');
                        grid.insertAdjacentHTML('afterbegin', result.cardHtml);
                    }

                    if (counter) {
                        const currentCount = parseInt(counter.textContent.replace(/\D/g, '')) || 0;
                        counter.textContent = (currentCount + 1).toLocaleString();
                    }
                }

                // Dispatch event so mentors-page.js knows to re-run refreshMentorPageState()
                document.dispatchEvent(new CustomEvent('mentor:updated', { 
                    detail: { mode, encodedId: payload.encoded_id } 
                }));

                if (typeof AnimationEngine !== 'undefined') AnimationEngine.refresh();

                apiMsg.innerHTML = `
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-2xl font-bold text-sm mt-2 flex items-center justify-center gap-2">
                        <i class="bi bi-patch-check-fill text-xl text-primary-500"></i>
                        ${result.messages?.[0] || 'Expert profile saved!'}
                    </div>
                `;

                submitBtn.style.visibility = 'hidden'; 
                
                setTimeout(() => {
                    if (modalInstance) modalInstance.close();
                }, 1000);

            } else {
                throw new Error(result.messages?.[0] || 'Registration failed');
            }

        } catch (err) {
            console.error('Mentor Submission Error:', err);
            apiMsg.innerHTML = `<div class="bg-red-50 text-red-700 px-4 py-2 rounded-xl font-bold text-sm mt-2">${err.message}</div>`;
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalLabel;
        }
    });
}