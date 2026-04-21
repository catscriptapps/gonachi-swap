// /resources/js/forms/mentor-form.js

/**
 * Professional Mentor Registration Form Renderer - Gonachi Edition 💎
 */
export function mentorForm({
    mode = 'add',
    headline = '',
    bio = '',
    experienceYears = '',
    skills = '', // Comma separated
    websiteUrl = '',
    youtubeUrl = '', // Replaced LinkedIn with YouTube 🎥
    city = '',
    
    // IDs for selections
    countryId = '',
    regionId = '',
    userTypeId = '', 

    // Data Dependencies
    countries = [],
    regions = [],
    mentorTypes = [], 

    buttonLabel = 'Register as Mentor',
    formId = 'mentor-form',
    encodedId = null
}) {
    const idPrefix = mode === 'edit' ? 'mentor-edit' : 'mentor-add';
    const dataEncodedIdAttr = encodedId ? `data-encoded-id="${encodedId}"` : '';

    const inputClasses = `
        block w-full rounded-xl 
        border border-gray-300 dark:border-gray-700 
        bg-white dark:bg-gray-900 
        text-gray-900 dark:text-white 
        placeholder:text-gray-400 
        focus:border-primary-500 focus:ring-primary-500 
        sm:text-sm transition-all duration-200 py-3 px-4
    `.replace(/\s+/g, ' ').trim();

    const labelClasses = "block text-[11px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-400 mb-2 ml-1";
    const sectionHeading = "text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6";
    const sectionLine = "w-8 h-[2px] bg-primary-400";

    return `
    <form id="${formId}" 
        class="w-full space-y-10 p-1 font-sans" 
        novalidate 
        ${dataEncodedIdAttr} 
        data-country-id="${countryId}">
        
        <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Base Location</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="${idPrefix}-country" class="${labelClasses}">Country *</label>
                    <select id="${idPrefix}-country" name="countryId" required class="${inputClasses}">
                        <option value="">Select Country</option>
                        ${countries.map(c => `<option value="${c.id}" ${c.id == countryId ? 'selected' : ''}>${c.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-region" class="${labelClasses}">Region / State *</label>
                    <select id="${idPrefix}-region" name="regionId" required class="${inputClasses}">
                        <option value="">Select Region</option>
                        ${regions.map(r => `<option value="${r.id}" ${r.id == regionId ? 'selected' : ''}>${r.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-city" class="${labelClasses}">City *</label>
                    <input type="text" id="${idPrefix}-city" name="city" required placeholder="e.g. Toronto" value="${city}" class="${inputClasses}" />
                </div>
            </div>
        </div>

        <div class="px-2">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Professional Identity</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                <div>
                    <label for="${idPrefix}-type" class="${labelClasses}">Mentor Category *</label>
                    <select id="${idPrefix}-type" name="target_user_type_id" required class="${inputClasses}">
                        <option value="">I am a...</option>
                        ${mentorTypes.map(t => `<option value="${t.id}" ${t.id == userTypeId ? 'selected' : ''}>${t.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-exp" class="${labelClasses}">Years of Experience *</label>
                    <input type="number" required id="${idPrefix}-exp" name="years_experience"
                        placeholder="e.g. 15" value="${experienceYears}" class="${inputClasses}" />
                </div>
            </div>
            <div class="space-y-5">
                <div>
                    <label for="${idPrefix}-headline" class="${labelClasses}">Professional Headline *</label>
                    <input type="text" required id="${idPrefix}-headline" name="headline"
                        placeholder="e.g. Master Electrician & Real Estate Investor" value="${headline}" class="${inputClasses}" />
                </div>
                <div>
                    <label for="${idPrefix}-bio" class="${labelClasses}">Expert Bio *</label>
                    <textarea id="${idPrefix}-bio" name="bio" rows="4" required
                        placeholder="Tell prospective mentees about your journey..." class="${inputClasses} resize-none">${bio}</textarea>
                </div>
                <div>
                    <label for="${idPrefix}-skills" class="${labelClasses}">Core Skills (Comma Separated) *</label>
                    <input type="text" required id="${idPrefix}-skills" name="skills"
                        placeholder="e.g. Project Management, Plumbing" value="${skills}" class="${inputClasses}" />
                </div>
            </div>
        </div>

        <div class="bg-secondary-900 dark:bg-black p-8 rounded-[2rem] text-white">
            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                <span class="w-8 h-[2px] bg-primary-400"></span> Digital Presence
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="${idPrefix}-youtube" class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">YouTube Channel / Video</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-red-500">
                            <i class="bi bi-youtube"></i>
                        </span>
                        <input type="url" id="${idPrefix}-youtube" name="youtube_url" placeholder="https://youtube.com/..." value="${youtubeUrl}" 
                            class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white pl-10" />
                    </div>
                </div>
                <div>
                    <label for="${idPrefix}-website" class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Portfolio/Website</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-primary-400">
                            <i class="bi bi-globe"></i>
                        </span>
                        <input type="url" id="${idPrefix}-website" name="website_url" placeholder="https://yourwebsite.com" value="${websiteUrl}" 
                            class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white pl-10" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-secondary-800">
            <div class="text-[13px] text-gray-400 font-medium max-w-[250px]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-400 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75m6-3.478v5.086c0 5.177-3.662 9.879-8.727 11.06a1.012 1.012 0 0 1-.546 0C6.662 21.237 3 16.535 3 11.358V6.272c0-.46.312-.867.76-.98l8-2a1.01 1.01 0 0 1 .48 0l8 2c.448.113.76.52.76.98Z" />
                </svg> Mentors are verified for the Gonachi network quality.
            </div>
            <button type="submit" id="${idPrefix}-submit"
                class="inline-flex items-center justify-center rounded-2xl bg-primary-400 px-12 py-4 text-sm font-black text-white shadow-xl shadow-primary-400/30 hover:bg-primary-500 transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}