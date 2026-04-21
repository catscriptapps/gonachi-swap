/**
 * Compressed Property Listing Form Renderer - Gonachi Edition 💎
 */
export function listingForm({
    mode = 'add',
    listing_title = '',
    listing_description = '',
    city = '',
    address = '',
    price = '',
    property_size = '',
    move_in_date = '',
    youtube_url = '',
    contact_phone = '',
    is_ac = 0,
    is_furnished = 0,
    parking = 0,
    pets_allowed = 0,
    countryId = '',
    regionId = '',
    category_id = '',
    category_type_id = '',
    unit_type_id = '',
    house_type_id = '',
    bedroom_id = '',
    bathroom_id = '',
    agreement_type_id = '',
    countries = [],
    regions = [],
    categories = [],
    categoryTypes = [],
    unitTypes = [],
    houseTypes = [],
    bedrooms = [],
    bathrooms = [],
    agreements = [],
    amenitiesList = [],
    selectedAmenities = [],
    buttonLabel = 'Post Listing',
    formId = 'listing-form',
    encodedId = null
}) {
    const idPrefix = mode === 'edit' ? 'listing-edit' : 'listing-add';
    const dataEncodedIdAttr = encodedId ? `data-encoded-id="${encodedId}"` : '';
    const inputClasses = `block w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary-500 focus:ring-primary-500 text-xs transition-all duration-200 py-2.5 px-3`.replace(/\s+/g, ' ').trim();
    const labelClasses = "block text-[10px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-400 mb-1.5 ml-1";
    const sectionHeading = "text-[10px] font-black text-primary-400 uppercase tracking-[0.2em] flex items-center gap-2 mb-4";
    const sectionLine = "w-6 h-[1.5px] bg-primary-400";

    const booleanFields = { is_ac, is_furnished, parking, pets_allowed };
    const filteredTypes = categoryTypes.filter(t => t.category_id == category_id);

    return `
    <form id="${formId}" class="w-full space-y-6 p-1 font-sans" novalidate ${dataEncodedIdAttr} data-country-id="${countryId}">
        
        <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-5 rounded-[1.5rem] border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Location</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-9 gap-4">
                <div class="col-span-1 md:col-span-1 lg:col-span-3">
                    <label for="${idPrefix}-country" class="${labelClasses}">Country *</label>
                    <select id="${idPrefix}-country" name="countryId" required class="${inputClasses}">
                        <option value="">Select</option>
                        ${countries.map(c => `<option value="${c.id}" ${c.id == countryId ? 'selected' : ''}>${c.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-span-1 md:col-span-1 lg:col-span-3">
                    <label for="${idPrefix}-region" class="${labelClasses}">Region *</label>
                    <select id="${idPrefix}-region" name="regionId" required class="${inputClasses}">
                        <option value="">Select</option>
                        ${regions.map(r => `<option value="${r.id}" ${r.id == regionId ? 'selected' : ''}>${r.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-span-2 md:col-span-4 lg:col-span-3">
                    <label for="${idPrefix}-city" class="${labelClasses}">City *</label>
                    <input id="${idPrefix}-city" type="text" name="city" value="${city}" placeholder="City" class="${inputClasses}" required />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary-950 p-5 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading} text-secondary-900 dark:text-white"><span class="${sectionLine} bg-secondary-900"></span> Classification & Listing Details</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 md:grid-cols-6 lg:grid-cols-12 gap-4">
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <label for="${idPrefix}-category" class="${labelClasses}">Category *</label>
                        <select id="${idPrefix}-category" name="category_id" required class="${inputClasses}" data-all-types='${JSON.stringify(categoryTypes)}'>
                            <option value="">Select</option>
                            ${categories.map(c => `<option value="${c.category_id}" ${c.category_id == category_id ? 'selected' : ''}>${c.category}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <label for="${idPrefix}-category-type" class="${labelClasses}">Type *</label>
                        <select id="${idPrefix}-category-type" name="category_type_id" required class="${inputClasses}">
                            <option value="">Select</option>
                            ${filteredTypes.map(t => `<option value="${t.category_type_id}" ${t.category_type_id == category_type_id ? 'selected' : ''}>${t.category_type}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-4 lg:col-span-6">
                        <label for="${idPrefix}-listing-title" class="${labelClasses}">Listing Title *</label>
                        <input id="${idPrefix}-listing-title" type="text" name="listing_title" value="${listing_title}" required class="${inputClasses}" />
                    </div>
                </div>
                <div>
                    <label for="${idPrefix}-description" class="${labelClasses}">Listing Description *</label>
                    <textarea id="${idPrefix}-description" name="listing_description" rows="6" required class="${inputClasses} resize-none leading-relaxed">${listing_description}</textarea>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-5 bg-white dark:bg-secondary-950 p-5 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-secondary-800">
                <h3 class="${sectionHeading} text-secondary-900 dark:text-white mb-4"><span class="${sectionLine} bg-secondary-900"></span> Property Specs</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label for="${idPrefix}-unit-type" class="${labelClasses}">Unit *</label>
                        <select id="${idPrefix}-unit-type" name="unit_type_id" required class="${inputClasses} unit-type-trigger">
                            <option value="">Select</option>
                            ${unitTypes.map(u => `<option value="${u.unit_type_id}" ${u.unit_type_id == unit_type_id ? 'selected' : ''}>${u.unit_type}</option>`).join('')}
                        </select>
                    </div>
                    <div id="${idPrefix}-house-type-container" class="${unit_type_id == 5 ? '' : 'hidden'}">
                        <label for="${idPrefix}-house-type" class="${labelClasses}">Style *</label>
                        <select id="${idPrefix}-house-type" name="house_type_id" class="${inputClasses}">
                            <option value="">Select</option>
                            ${houseTypes.map(h => `<option value="${h.house_type_id}" ${h.house_type_id == house_type_id ? 'selected' : ''}>${h.house_type}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label for="${idPrefix}-size" class="${labelClasses}">Sq Ft</label>
                        <input id="${idPrefix}-size" type="text" name="property_size" value="${property_size}" placeholder="Size" class="${inputClasses}" />
                    </div>
                    <div>
                        <label for="${idPrefix}-bedroom" class="${labelClasses}">Beds</label>
                        <select id="${idPrefix}-bedroom" name="bedroom_id" class="${inputClasses}">
                            <option value="">0</option>
                            ${bedrooms.map(b => `<option value="${b.bedroom_id}" ${b.bedroom_id == bedroom_id ? 'selected' : ''}>${b.bedroom}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label for="${idPrefix}-bathroom" class="${labelClasses}">Baths</label>
                        <select id="${idPrefix}-bathroom" name="bathroom_id" class="${inputClasses}">
                            <option value="">0</option>
                            ${bathrooms.map(b => `<option value="${b.bathroom_id}" ${b.bathroom_id == bathroom_id ? 'selected' : ''}>${b.bathroom}</option>`).join('')}
                    </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 py-3 border-t border-gray-50 dark:border-gray-800">
                    ${['is_ac', 'is_furnished', 'parking', 'pets_allowed'].map(field => `
                        <label for="${idPrefix}-${field}" class="flex items-center gap-2 cursor-pointer group">
                            <input id="${idPrefix}-${field}" type="checkbox" name="${field}" value="1" ${booleanFields[field] ? 'checked' : ''} class="w-4 h-4 rounded border-gray-300 text-primary-400 focus:ring-primary-400" />
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-500 group-hover:text-primary-400 transition-colors">${field.replace('is_', '').replace('_', ' ')}</span>
                        </label>
                    `).join('')}
                </div>
            </div>

            <div class="lg:col-span-7 bg-gray-50/30 dark:bg-secondary-900/5 p-4 rounded-2xl border border-dashed border-gray-200 dark:border-secondary-800">
                <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Amenities</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-2 mb-6">
                    ${amenitiesList.flatMap(cat => (cat.amenities || []).map(amenity => `
                        <label for="${idPrefix}-amenity-${amenity.amenity_id}" class="flex items-center gap-2 cursor-pointer group">
                            <input id="${idPrefix}-amenity-${amenity.amenity_id}" type="checkbox" name="amenities[]" value="${amenity.amenity_id}" 
                                ${selectedAmenities.includes(String(amenity.amenity_id)) || selectedAmenities.includes(Number(amenity.amenity_id)) ? 'checked' : ''} 
                                class="w-3.5 h-3.5 rounded border-gray-300 text-primary-400 focus:ring-primary-400" />
                            <span class="text-[10px] font-medium text-gray-600 dark:text-gray-400 group-hover:text-primary-400 truncate">${amenity.name}</span>
                        </label>
                    `)).join('')}
                </div>
                <div class="pt-4 border-t border-dashed border-gray-300 dark:border-secondary-700">
                    <label for="${idPrefix}-address" class="${labelClasses}">Street Address</label>
                    <input id="${idPrefix}-address" type="text" name="address" value="${address}" placeholder="Street address" class="${inputClasses}" />
                </div>
                <div class="pt-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="${idPrefix}-price" class="${labelClasses}">Price *</label>
                            <input id="${idPrefix}-price" type="text" name="price" value="${price}" required placeholder="Price" class="${inputClasses}" />
                        </div>
                        <div>
                            <label for="${idPrefix}-agreement-type" class="${labelClasses}">Agreement *</label>
                            <select id="${idPrefix}-agreement-type" name="agreement_type_id" required class="${inputClasses}">
                                <option value="">Select</option>
                                ${agreements.map(a => `<option value="${a.agreement_type_id}" ${a.agreement_type_id == agreement_type_id ? 'selected' : ''}>${a.agreement_type}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label for="${idPrefix}-move-in-date" class="${labelClasses}">Move-in</label>
                            <input id="${idPrefix}-move-in-date" type="date" name="move_in_date" value="${move_in_date}" class="${inputClasses}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-secondary-900 p-5 rounded-[1.5rem] text-white">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="col-span-2 md:col-span-4">
                    <label for="${idPrefix}-youtube-url" class="${labelClasses} !text-gray-400">YouTube</label>
                    <input id="${idPrefix}-youtube-url" type="url" name="youtube_url" value="${youtube_url}" placeholder="URL" class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white" />
                </div>
                
                <div class="col-span-2 md:col-span-1">
                    <label for="${idPrefix}-contact-phone" class="${labelClasses} !text-gray-400">Phone</label>
                    <input id="${idPrefix}-contact-phone" type="tel" name="contact_phone" value="${contact_phone}" placeholder="Phone" class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-[9px] text-gray-500 dark:text-gray-400 font-medium">
                All listings are timestamped and verified for safety.
            </p>
            <button type="submit" class="rounded-xl bg-primary-400 px-8 py-3.5 text-xs font-black text-white shadow-xl hover:bg-primary-500 transition-all uppercase tracking-[0.2em]">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}