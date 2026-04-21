// /resources/js/forms/advert-form.js

/**
 * High-Tech Advert Form Renderer - Gonachi Premium Edition 💎
 * Primary:     Set in Tailwind config.
 * Secondary:   Set in Tailwind config.
 */
export function advertForm({
    mode = 'add',
    title = '',
    description = '',
    callToActionId = '', 
    keywords = '',
    landingPageUrl = '',
    advertPackage = 0,
    selectedCountries = [], 
    selectedUserTypes = [], 
    availableRoles = [],    
    countries = [],         
    availableCtas = [],     
    availablePackages = [], // Added: Received from the database
    buttonLabel = 'Submit',
    formId = 'ad-form',
    encodedId = null
}) {
    const idPrefix = mode === 'edit' ? 'ad-edit' : 'ad-add';
    const dataEncodedIdAttr = encodedId ? `data-encoded-id="${encodedId}"` : '';

    const inputClasses = `
        block w-full rounded-xl 
        border border-gray-300 dark:border-gray-700 
        bg-white dark:bg-gray-900 
        text-gray-900 dark:text-white 
        placeholder:text-gray-400 
        focus:border-orange-500 focus:ring-orange-500 
        sm:text-sm transition-all duration-200 py-3 px-4
    `.replace(/\s+/g, ' ').trim();

    const labelClasses = "block text-[11px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-400 mb-2 ml-1";

    const filteredRoles = availableRoles.filter(r => (r.id || r.user_type_id) != 1);
    const allSelected = filteredRoles.length > 0 && filteredRoles.every(r => 
        selectedUserTypes.includes(Number(r.id || r.user_type_id))
    );

    return `
    <form id="${formId}" class="w-full space-y-8 p-1 font-sans" novalidate ${dataEncodedIdAttr}>
        
        <div class="space-y-5">
            <h3 class="text-xs font-black text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2">
                <span class="w-8 h-[2px] bg-orange-600"></span> Ad Content
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label for="${idPrefix}-title" class="${labelClasses}">Campaign Title</label>
                    <input type="text" required id="${idPrefix}-title" name="title"
                        placeholder="e.g. Summer Flash Sale 2026" value="${title}" class="${inputClasses}" />
                </div>
                
                <div>
                    <label for="${idPrefix}-description" class="${labelClasses}">Description / Body Text</label>
                    <textarea id="${idPrefix}-description" name="description" rows="4" required
                        placeholder="What are you promoting?" class="${inputClasses} resize-none">${description}</textarea>
                </div>

                <div>
                    <label for="${idPrefix}-keywords" class="${labelClasses}">Keywords</label>
                    <textarea id="${idPrefix}-keywords" name="keywords" rows="4" required
                        placeholder="e.g. Real Estate, Contracting, Toronto, Training" 
                        class="${inputClasses} resize-none">${keywords || ''}</textarea>
                    <p class="mt-2 text-[9px] text-gray-400 italic uppercase">Separate keywords with commas.</p>
                </div>

                <div>
                    <label for="${idPrefix}-cta" class="${labelClasses}">Call To Action</label>
                    <select id="${idPrefix}-cta" required name="call_to_action_id" class="${inputClasses}">
                        <option value="">Select CTA</option>
                        ${availableCtas.map(cta => `
                            <option value="${cta.call_to_action_id}" ${cta.call_to_action_id == callToActionId ? 'selected' : ''}>
                                ${cta.call_to_action}
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div>
                    <label for="${idPrefix}-url" class="${labelClasses}">Landing Page URL</label>
                    <input type="url" id="${idPrefix}-url" name="landing_page_url"
                        placeholder="https://gonachi.com/promo" value="${landingPageUrl}" class="${inputClasses}" />
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <label class="${labelClasses}">Select Visibility Package</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                ${availablePackages.map(pkg => `
                    <label class="relative flex flex-col items-center p-4 rounded-2xl border-2 cursor-pointer transition-all duration-300 group
                        ${advertPackage == pkg.package_id 
                            ? 'border-orange-500 bg-orange-50/50 dark:bg-orange-500/10' 
                            : 'border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/20 hover:border-orange-200'}">
                        
                        <input type="radio" name="advert_package" value="${pkg.package_id}" ${advertPackage == pkg.package_id ? 'checked' : ''} class="hidden" />
                        
                        <i class="bi ${pkg.package_icon} text-xl mb-2 ${advertPackage == pkg.package_id ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-400'}"></i>
                        <span class="text-[10px] font-black uppercase tracking-tighter text-secondary-900 dark:text-white text-center">${pkg.package_name}</span>
                        <span class="text-[9px] text-gray-500 font-bold">${pkg.package_description}</span>
                        
                        ${advertPackage == pkg.package_id ? '<div class="absolute top-2 right-2 w-2 h-2 bg-orange-600 rounded-full animate-ping"></div>' : ''}
                    </label>
                `).join('')}
            </div>
        </div>

        <div class="p-6 rounded-[2rem] shadow-2xl space-y-2 bg-white dark:bg-secondary-950 border border-gray-100 dark:border-secondary-800">
            <h3 class="text-xs font-black uppercase tracking-[0.3em] flex items-center gap-2 text-secondary-900 dark:text-white">
                <span class="w-8 h-[2px] bg-orange-500"></span> Audience Targeting
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2 block">Targeting</label>
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-grow">
                            <select id="${idPrefix}-country-selector" 
                                class="block w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm font-bold py-3 px-4 shadow-sm focus:ring-2 focus:ring-orange-500 transition-all cursor-pointer">
                                <option value="">Select a country...</option>
                                ${countries.map(c => `<option value="${c.id}" data-name="${c.name}">${c.name}</option>`).join('')}
                            </select>
                        </div>

                        <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 hover:border-orange-500 transition-all cursor-pointer">
                            <input type="checkbox" id="${idPrefix}-all-countries" 
                                class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 cursor-pointer">
                            <label for="${idPrefix}-all-countries" class="text-xs font-black uppercase tracking-wider text-navy-900 dark:text-white cursor-pointer select-none">All</label>
                        </div>
                    </div>

                    <div id="${idPrefix}-selected-bucket" 
                        class="min-h-[100px] p-4 bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl flex flex-wrap gap-2 items-start align-content-start">
                    </div>
                    
                    <div id="${idPrefix}-country-error" class="mt-2 text-[10px] text-red-500 font-bold hidden uppercase tracking-widest">
                        * Please select at least one country or choose 'All'
                    </div>

                    <input type="hidden" name="selected_countries[]" id="${idPrefix}-countries-hidden-json">
                </div>

                <div>
                    <label class="${labelClasses} text-gray-500">Audience Groups</label>
                    <div id="user-type-error-slot" class="mt-2 text-[10px] text-red-500 font-bold"></div>
                    
                    <div class="mt-2 mb-3">
                        <label class="flex items-center px-4 py-3 rounded-xl border cursor-pointer transition-all bg-gray-100 border-gray-300 dark:bg-secondary-700 dark:border-secondary-600 group">
                            <input type="checkbox" id="all-users-toggle" 
                                ${allSelected ? 'checked' : ''} 
                                class="w-4 h-4 text-orange-600 rounded bg-white dark:bg-secondary-950 border-gray-300 dark:border-secondary-600 focus:ring-orange-500" />
                            <span class="ml-2 text-[10px] font-black uppercase tracking-widest text-orange-600 dark:text-orange-400">
                                Target All Users
                            </span>
                        </label>
                    </div>

                    <div id="${idPrefix}-user-types-container" class="grid grid-cols-2 gap-2 mt-2 transition-all duration-300 ${allSelected ? 'hidden' : ''}">
                        ${filteredRoles.map(role => {
                            const rId = role.id || role.user_type_id;
                            const isChecked = selectedUserTypes.includes(Number(rId));
                            return `
                            <label class="audience-group-label flex items-center px-4 py-3 rounded-xl border cursor-pointer transition-all group
                                bg-gray-50 border-gray-200 dark:bg-secondary-800 dark:border-secondary-700">
                                
                                <input type="checkbox" name="selected_user_types[]" value="${rId}"
                                    ${isChecked ? 'checked' : ''}
                                    class="user-type-checkbox w-4 h-4 text-orange-600 rounded bg-white dark:bg-secondary-950 border-gray-300 dark:border-secondary-600 focus:ring-orange-500" />
                                    
                                <span class="ml-2 text-[10px] font-black uppercase tracking-tight text-gray-600 dark:text-gray-300 group-hover:text-orange-500">
                                    ${role.name || role.user_type}
                                </span>
                            </label>
                            `;
                        }).join('')}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800">
            <div class="text-[14px] text-gray-400 font-medium">
                <i class="bi bi-info-circle mr-1"></i> Ad will be reviewed by Gonachi Admins before going live.
                <br>&nbsp;Please monitor your notification system for updates.
            </div>
            <button type="submit" id="${idPrefix}-submit"
                class="inline-flex items-center justify-center rounded-xl bg-orange-600 px-12 py-4 text-sm font-black text-white shadow-lg shadow-orange-600/30 hover:bg-orange-700 transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}