// /resources/js/forms/quotation-connect-form.js

/**
 * Quotation Proposal Form Renderer - Gonachi Edition 💎
 */
export function quotationConnectForm({
    quoteTitle = 'Project',
    quoteId = null,
    ownerId,
    budget = 'N/A',
    formId = 'quotation-connect-form',
    buttonLabel = 'Submit Proposal'
}) {
    const inputClasses = `block w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder:text-gray-400 focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-all duration-200 py-3 px-4`.replace(/\s+/g, ' ').trim();
    const labelClasses = "block text-[11px] font-black uppercase tracking-widest text-secondary-900 dark:text-gray-400 mb-2 ml-1";
    const sectionHeading = "text-xs font-black text-primary-400 uppercase tracking-[0.3em] flex items-center gap-2 mb-6";
    const sectionLine = "w-8 h-[2px] bg-primary-400";

    return `
    <form id="${formId}" class="w-full space-y-8 p-1 font-sans" novalidate>
        <input type="hidden" name="quotation_id" value="${quoteId}">
        <input type="hidden" name="receiver_id" value="${ownerId}">

        <div class="bg-primary-50/50 dark:bg-primary-400/5 p-6 rounded-[2rem] border border-primary-100 dark:border-primary-400/10">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> Project Bid</h3>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-400 flex items-center justify-center text-white shadow-lg shadow-primary-400/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold text-secondary-900 dark:text-white truncate max-w-[500px]">Bidding on: ${quoteTitle}</p>
                        <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black tracking-tighter border border-emerald-200 dark:border-emerald-800/30">
                            ${budget}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Submit your professional proposal. Be clear about your timeline and experience. High-quality bids are more likely to be awarded.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-2">
            <label for="quote-message" class="${labelClasses}">Proposal Details *</label>
            <textarea 
                id="quote-message" 
                name="message" 
                rows="6" 
                required
                placeholder="I can help with '${quoteTitle}'. I have 10 years experience in this trade and can start as soon as..." 
                class="${inputClasses} resize-none"
            ></textarea>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-secondary-800">
            <div class="text-[13px] text-gray-400 font-medium max-w-[400px] flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg> 
                Bids are binding once accepted.
            </div>
            <button type="submit" 
                class="inline-flex items-center justify-center rounded-2xl bg-primary-400 px-10 py-4 text-sm font-black text-white shadow-xl shadow-primary-400/30 hover:bg-primary-500 transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
                <i class="bi bi-file-earmark-post-fill ml-2"></i>
            </button>
        </div>
    </form>
    `;
}