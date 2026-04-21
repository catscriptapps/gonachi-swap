// /resources/js/forms/quotation-form.js

/**
 * Premium Quotation Request Form Renderer - Gonachi Edition 💎
 */
export function quotationForm({
    mode = 'add',
    title = '',
    description = '',
    city = '',
    budget = '',
    startDate = '',
    finishDate = '',
    startTime = '',
    finishTime = '',
    contactPhone = '',
    youtubeUrl = '',
    notify = 'no',
    
    // IDs for selections - Matching User Form logic
    countryId = '',
    regionId = '',
    contractorTypeId = '',
    skilledTradeId = '',
    unitTypeId = '',
    houseTypeId = '',
    quotationTypeId = '',
    quotationDestId = '',

    // Data Dependencies
    countries = [],
    regions = [], // Added to match user-form signature
    trades = [],
    contractorTypes = [],
    unitTypes = [],
    houseTypes = [],
    quoteTypes = [],
    destinations = [],

    buttonLabel = 'Submit Request',
    formId = 'quote-form',
    encodedId = null
}) {
    const idPrefix = mode === 'edit' ? 'quote-edit' : 'quote-add';
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
    const labelClassesForFinancials = "block text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-1";
    const sectionHeading = "text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6";
    const sectionLine = "w-8 h-[2px] bg-primary-400";

    return `
    <form id="${formId}" 
        class="w-full space-y-10 p-1 font-sans" 
        novalidate 
        ${dataEncodedIdAttr} 
        data-country-id="${countryId}">
        
        <div class="bg-gray-50/50 dark:bg-secondary-900/10 p-6 rounded-[2rem] border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Project Location</h3>
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
                    <label for="${idPrefix}-city" class="${labelClasses}">City</label>
                    <input type="text" id="${idPrefix}-city" name="city" placeholder="e.g. Toronto" value="${city}" class="${inputClasses}" />
                </div>
            </div>
        </div>

        <div class="px-2">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Work Description</h3>
            <div class="space-y-5">
                <div>
                    <label for="${idPrefix}-title" class="${labelClasses}">Quotation Title *</label>
                    <input type="text" required id="${idPrefix}-title" name="quotation_title"
                        placeholder="e.g. Full Kitchen Renovation" value="${title}" class="${inputClasses}" />
                </div>
                <div>
                    <label for="${idPrefix}-description" class="${labelClasses}">Description of Work *</label>
                    <textarea id="${idPrefix}-description" name="description_of_work_to_be_done" rows="4" required
                        placeholder="Detailed breakdown of the tasks..." class="${inputClasses} resize-none">${description}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary-950 p-6 rounded-[2rem] shadow-xl border border-gray-100 dark:border-secondary-800">
            <h3 class="${sectionHeading} text-secondary-900 dark:text-white"><span class="${sectionLine} bg-secondary-900"></span> Project Classification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="${idPrefix}-contractor-type" class="${labelClasses}">Contractor Type *</label>
                    <select id="${idPrefix}-contractor-type" name="contractor_type_id" required class="${inputClasses}">
                        <option value="">Select Type</option>
                        ${contractorTypes.map(t => `<option value="${t.contractor_type_id}" ${t.contractor_type_id == contractorTypeId ? 'selected' : ''}>${t.contractor_type}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-skilled-trade" class="${labelClasses}">Skilled Trade *</label>
                    <select id="${idPrefix}-skilled-trade" name="skilled_trade_id" required class="${inputClasses}">
                        <option value="">Select Trade</option>
                        ${trades.map(t => `<option value="${t.skilled_trade_id}" ${t.skilled_trade_id == skilledTradeId ? 'selected' : ''}>${t.skilled_trade}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label for="${idPrefix}-unit-type" class="${labelClasses}">Unit Type *</label>
                    <select id="${idPrefix}-unit-type" name="unit_type_id" required class="${inputClasses} unit-type-trigger">
                        <option value="">Select Unit</option>
                        ${unitTypes.map(u => `<option value="${u.unit_type_id}" ${u.unit_type_id == unitTypeId ? 'selected' : ''}>${u.unit_type}</option>`).join('')}
                    </select>
                </div>
                <div id="${idPrefix}-house-type-container" class="${unitTypeId == 5 ? '' : 'hidden'}">
                    <label for="${idPrefix}-house-type" class="${labelClasses}">House Type *</label>
                    <select id="${idPrefix}-house-type" name="house_type_id" class="${inputClasses}">
                        <option value="">Select House Style</option>
                        ${houseTypes.map(h => `<option value="${h.house_type_id}" ${h.house_type_id == houseTypeId ? 'selected' : ''}>${h.house_type}</option>`).join('')}
                    </select>
                </div>
            </div>
        </div>

        <div class="px-2">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Timeline & Schedule</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label for="${idPrefix}-start-date" class="${labelClasses}">Start Date</label>
                    <input type="date" 
                        id="${idPrefix}-start-date" 
                        name="start_date" 
                        value="${startDate}" 
                        class="${inputClasses}" required />
                </div>
                <div>
                    <label for="${idPrefix}-finish-date" class="${labelClasses}">Finish Date</label>
                    <input type="date" 
                        id="${idPrefix}-finish-date" 
                        name="finish_date" 
                        value="${finishDate}" 
                        class="${inputClasses}" required />
                </div>

                <div>
                    <label for="${idPrefix}-start-time" class="${labelClasses}">Start Time</label>
                    <input type="time" 
                        id="${idPrefix}-start-time" 
                        name="start_time" 
                        value="${startTime}" 
                        step="900"
                        class="${inputClasses}" required />
                </div>
                <div>
                    <label for="${idPrefix}-finish-time" class="${labelClasses}">Finish Time</label>
                    <input type="time" 
                        id="${idPrefix}-finish-time" 
                        name="finish_time" 
                        value="${finishTime}" 
                        step="900"
                        class="${inputClasses}" required />
                </div>
            </div>
        </div>

        <div class="bg-secondary-900 dark:bg-black p-8 rounded-[2rem] text-white">
            <h3 class="text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6">
                <span class="w-8 h-[2px] bg-primary-400"></span> Financials & Type
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="${idPrefix}-quotation-type" class="${labelClassesForFinancials} text-gray-400">Quotation Type *</label>
                    <select id="${idPrefix}-quotation-type" name="quotation_type_id" required class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white">
                        <option value="">Select Type</option>
                        ${quoteTypes.map(q => `<option value="${q.quotation_type_id}" ${q.quotation_type_id == quotationTypeId ? 'selected' : ''}>${q.quotation_type}</option>`).join('')}
                    </select>
                </div>
                
                <div>
                    <label class="${labelClassesForFinancials} text-gray-400">Budget (Optional)</label>
                    <input type="text" name="quotation_budget" placeholder="e.g. $5,000 - $10,000" value="${budget}" class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white" />
                </div>
                <div>
                    <label for="${idPrefix}-quotation-destination" class="${labelClassesForFinancials} text-gray-400">Target Audience *</label>
                    <select id="${idPrefix}-quotation-destination" name="quotation_dest_id" required class="${inputClasses} !bg-secondary-800 !border-secondary-700 !text-white">
                        <option value="">Select Destination</option>
                        ${destinations.map(d => `<option value="${d.quotation_dest_id}" ${d.quotation_dest_id == quotationDestId ? 'selected' : ''}>${d.quotation_dest}</option>`).join('')}
                    </select>
                </div>
            </div>
        </div>

        <div class="px-2 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label for="${idPrefix}-youtube" class="${labelClasses}">YouTube URL (Walkthrough)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-red-600">
                        <i class="bi bi-youtube"></i>
                    </span>
                    <input type="url" id="${idPrefix}-youtube" name="youtube_url" placeholder="https://youtube.com/..." value="${youtubeUrl}" class="${inputClasses} pl-10" />
                </div>
            </div>
            <div>
                <label for="${idPrefix}-phone" class="${labelClasses}">Contact Phone</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-primary-400">
                        <i class="bi bi-telephone-fill"></i>
                    </span>
                    <input type="tel" id="${idPrefix}-phone" name="contact_phone" placeholder="+1..." value="${contactPhone}" class="${inputClasses} pl-10" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-secondary-800">
            <div class="text-[10px] text-gray-400 font-medium max-w-[250px]">
                <i class="bi bi-shield-check mr-1 text-primary-400"></i> All requests are timestamped and verified for contractor safety.
            </div>
            <button type="submit" id="${idPrefix}-submit"
                class="inline-flex items-center justify-center rounded-2xl bg-primary-400 px-12 py-4 text-sm font-black text-white shadow-xl shadow-primary-400/30 hover:bg-primary-500 transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
            </button>
        </div>
    </form>
    `;
}