// /resources/js/forms/listing-connect-form.js

/**
 * Listing Inquiry Form Renderer - Gonachi Edition 💎
 */
export function listingConnectForm({
    listingTitle = 'Listing',
    listingId = null,
    ownerId,
    price = 'N/A',
    formId = 'listing-connect-form',
    buttonLabel = 'Send Inquiry'
}) {
    const inputClasses = `block w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-all duration-200 py-3 px-4`.replace(/\s+/g, ' ').trim();
    const labelClasses = "block text-[11px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-400 mb-2 ml-1";
    const sectionHeading = "text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6";
    const sectionLine = "w-8 h-[2px] bg-primary-400";

    return `
    <form id="${formId}" class="w-full space-y-8 p-1 font-sans" novalidate>
        <input type="hidden" name="listing_id" value="${listingId}">
        <input type="hidden" name="receiver_id" value="${ownerId}">

        <div class="bg-primary-50/50 dark:bg-primary-400/5 p-6 rounded-[2rem] border border-primary-100 dark:border-primary-400/10">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Direct Inquiry</h3>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-400 flex items-center justify-center text-white shadow-lg shadow-primary-400/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold text-secondary-900 dark:text-white truncate max-w-[500px]">${listingTitle}</p>
                        <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black tracking-tighter border border-emerald-200 dark:border-emerald-800/30">
                            ${price}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Message the poster directly regarding this listing. Please include any specific questions or preferred viewing times.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-2">
            <label for="listing-message" class="${labelClasses}">Your Message *</label>
            <textarea 
                id="listing-message" 
                name="message" 
                rows="6" 
                required
                placeholder="Hi, I'm interested in '${listingTitle}'. Is it still available?" 
                class="${inputClasses} resize-none"
            ></textarea>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-secondary-800">
            <div class="text-[14px] text-gray-400 font-medium max-w-[400px] flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg> 
                Reply speed: Usually under 24h.
            </div>
            <button type="submit" 
                class="inline-flex items-center justify-center rounded-2xl bg-primary-400 px-10 py-4 text-sm font-black text-white shadow-xl shadow-primary-400/30 hover:bg-primary-500 transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
                <i class="bi bi-chat-dots-fill ml-2"></i>
            </button>
        </div>
    </form>
    `;
}