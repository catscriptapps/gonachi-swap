// /resources/js/forms/listing-form.js

/**
 * Premium Listing Form Renderer - Gonachi Swap Edition 🔄
 * Strictly mapped to App\Models\Listing
 */
export function listingForm({
    mode = 'add',
    listingTitle = '',
    listingDescription = '',
    city = '',
    price = '',
    tradePref = '',
    contactPhone = '',
    youtubeUrl = '',

    // IDs for selections - Matching Model field names
    countryId = '',
    regionId = '',
    categoryId = '',
    typeId = 0, // Default 0: Select one
    conditionId = 0, // Default 0: Select one

    // Data Dependencies
    countries = [],
    regions = [],
    categories = [],
    types = [], // Swap, Sale, Gift
    conditions = [], // New, Like New, etc.

    buttonLabel = 'Post Listing',
    formId = 'listing-form',
    encodedId = null
}) {
    const idPrefix = mode === 'edit' ? 'listing-edit' : 'listing-add';
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
        
        <!-- SECTION: Classification -->
        <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Item Classification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="${idPrefix}-category" class="${labelClasses}">Category *</label>
                    <select id="${idPrefix}-category" name="category_id" required class="${inputClasses}">
                        <option value="">Select Category</option>
                        ${categories.map(cat => `<option value="${cat.category_id}" ${cat.category_id == categoryId ? 'selected' : ''}>${cat.category_name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-condition" class="${labelClasses}">Item Condition *</label>
                    <select id="${idPrefix}-condition" name="condition_id" required class="${inputClasses}">
                        <option value="">Select Condition</option>
                        ${conditions.map(c => `<option value="${c.condition_id}" ${c.condition_id == conditionId ? 'selected' : ''}>${c.condition_name}</option>`).join('')}
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION: Content -->
        <div class="px-2">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Listing Details</h3>
            <div class="space-y-5">
                <div>
                    <label for="${idPrefix}-title" class="${labelClasses}">Listing Title *</label>
                    <input type="text" required id="${idPrefix}-title" name="listing_title"
                        placeholder="e.g. Vintage 1970s Gibson Guitar" value="${listingTitle}" class="${inputClasses}" />
                </div>
                <div>
                    <label for="${idPrefix}-description" class="${labelClasses}">Description *</label>
                    <textarea id="${idPrefix}-description" name="listing_description" rows="4" required
                        placeholder="Describe the item's features, history, and any flaws..." class="${inputClasses} resize-none">${listingDescription}</textarea>
                </div>
            </div>
        </div>

        <!-- SECTION: Location -->
        <div class="bg-white dark:bg-secondary-950 p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading} text-secondary-900 dark:text-white"><span class="${sectionLine} bg-secondary-900"></span> Item Location</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="${idPrefix}-country" class="${labelClasses}">Country *</label>
                    <select id="${idPrefix}-country" name="country_id" required class="${inputClasses}">
                        <option value="">Select Country</option>
                        ${countries.map(c => `<option value="${c.id}" ${c.id == countryId ? 'selected' : ''}>${c.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-region" class="${labelClasses}">Region / State *</label>
                    <select id="${idPrefix}-region" name="region_id" required class="${inputClasses}">
                        <option value="">Select Region</option>
                        ${regions.map(r => `<option value="${r.id}" ${r.id == regionId ? 'selected' : ''}>${r.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-city" class="${labelClasses}">City *</label>
                    <input type="text" id="${idPrefix}-city" name="city" placeholder="e.g. Toronto" value="${city}" class="${inputClasses}" required />
                </div>
            </div>
        </div>

        <!-- SECTION: Transaction Details -->
        <div class="bg-secondary-900 dark:bg-black p-8 rounded-[2rem] text-white">
            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                <span class="w-8 h-[2px] bg-primary-400"></span> Transaction Intent
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="${idPrefix}-type" class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Offer Type *</label>
                    <select id="${idPrefix}-type" name="type_id" required class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white">
                        <option value="">Select Type</option>
                        ${types.map(t => `<option value="${t.type_id}" ${t.type_id == typeId ? 'selected' : ''}>${t.type_name}</option>`).join('')}
                    </select>
                </div>
                
                <div>
                    <label for="${idPrefix}-price" class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Value/Price ($)</label>
                    <input type="number" step="0.01" id="${idPrefix}-price" name="price" placeholder="0.00" value="${price}" class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white" />
                </div>

                <div>
                    <label for="${idPrefix}-trade-pref" class="block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1">Trade Preferences</label>
                    <input type="text" id="${idPrefix}-trade-pref" name="trade_pref" placeholder="e.g. Looking for Laptops" value="${tradePref}" class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white" />
                </div>
            </div>
        </div>

        <!-- SECTION: Contact & Media -->
        <div class="px-2 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="${idPrefix}-youtube" class="${labelClasses}">YouTube Walkthrough URL</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-red-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.301 1.103.33 3.56.33 4.246s-.03 3.143-.33 4.246a2.01 2.01 0 0 1-1.415 1.419c-1.113.303-5.28.333-6.11.333h-.09c-.823 0-4.998-.03-6.11-.333a2.01 2.01 0 0 1-1.415-1.419C.03 11.388 0 8.931 0 8.246s.03-3.143.33-4.246a2.01 2.01 0 0 1 1.415-1.42c1.113-.302 5.282-.332 6.11-.332zM6.5 5.204v5.592L10.748 8 6.5 5.204z"/>
                        </svg>
                    </span>
                    <input type="url" id="${idPrefix}-youtube" name="youtube_url" placeholder="https://youtube.com/..." value="${youtubeUrl}" class="${inputClasses} pl-10" />
                </div>
            </div>
            <div>
                <label for="${idPrefix}-phone" class="${labelClasses}">Contact Phone</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-primary-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </span>
                    <input type="tel" id="${idPrefix}-phone" name="contact_phone" placeholder="+1..." value="${contactPhone}" class="${inputClasses} pl-10" />
                </div>
            </div>
        </div>

        <!-- FOOTER: Info & Submit -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-secondary-800">
            <div class="text-[10px] text-gray-400 font-medium max-w-[250px] flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Listings are verified for community safety.
            </div>
            <button type="submit" id="${idPrefix}-submit"
                class="inline-flex items-center justify-center rounded-2xl bg-primary-500 px-12 py-4 text-sm font-black text-secondary-950 shadow-xl shadow-primary-500/20 hover:bg-secondary-950 hover:text-white transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}