// /resources/js/forms/mentor-connect-form.js

/**
 * Mentor Connection Form Renderer - Gonachi Edition 💎
 */
export function mentorConnectForm({
    mentorName = 'Expert',
    mentorId = null,
    ownerId,
    targetUserType = 'Professional', // 💎 New Parameter
    formId = 'mentor-connect-form',
    buttonLabel = 'Send Connection Request'
}) {
    // Emulating your styling variables
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

    const badgeClasses = "ml-2 px-2 py-0.5 rounded-md bg-primary-400/10 text-primary-500 text-[10px] font-black uppercase tracking-tighter border border-primary-400/20";

    return `
    <form id="${formId}" class="w-full space-y-8 p-1 font-sans" novalidate>
        <input type="hidden" name="mentor_id" value="${mentorId}">
        <input type="hidden" name="receiver_id" value="${ownerId}">

        <div class="bg-primary-50/50 dark:bg-primary-400/5 p-6 rounded-[2rem] border border-primary-100 dark:border-primary-400/10">
            <h3 class="${sectionHeading}"><span class="${sectionLine}"></span> The Handshake</h3>
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-400 flex items-center justify-center text-white shadow-lg shadow-primary-400/20">
                    <svg class="w-4 h-4 relative z-10 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center">
                        <p class="text-sm font-bold text-secondary-900 dark:text-white">Connecting with MENTOR ${mentorName}</p>
                        <span class="${badgeClasses}">${targetUserType}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        Introduce yourself and your goals. Once the mentor accepts your request, a direct messaging line will be opened.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-2">
            <label for="connect-message" class="${labelClasses}">Your Initial Pitch *</label>
            <textarea 
                id="connect-message" 
                name="message" 
                rows="6" 
                required
                placeholder="Hi ${mentorName}, I'm looking for some guidance on my upcoming residential project..." 
                class="${inputClasses} resize-none"
            ></textarea>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-secondary-800">
            <div class="text-[13px] text-gray-400 font-medium max-w-[200px]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary-400 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75m6-3.478v5.086c0 5.177-3.662 9.879-8.727 11.06a1.012 1.012 0 0 1-.546 0C6.662 21.237 3 16.535 3 11.358V6.272c0-.46.312-.867.76-.98l8-2a1.01 1.01 0 0 1 .48 0l8 2c.448.113.76.52.76.98Z" />
                </svg> Quality handshake: Mentors review all requests.
            </div>
            <button type="submit" 
                class="inline-flex items-center justify-center rounded-2xl bg-primary-400 px-10 py-4 text-sm font-black text-white shadow-xl shadow-primary-400/30 hover:bg-primary-500 transition-all active:scale-95 uppercase tracking-widest">
                ${buttonLabel}
                <i class="bi bi-send-fill ml-2"></i>
            </button>
        </div>
    </form>
    `;
}