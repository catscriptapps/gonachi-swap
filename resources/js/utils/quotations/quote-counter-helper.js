// /resources/js/utils/quotations/quote-counter-helper.js

export const QuoteCounter = {
    update: (manualCount = null) => {
        const countSpan = document.getElementById('quotes-counter-number');
        const grid = document.getElementById('quotes-grid');

        if (!countSpan) return;

        // Use the manual count from the server (0 for gibberish)
        const finalCount = (manualCount !== null) 
            ? parseInt(manualCount) 
            : (grid ? grid.querySelectorAll('.quotation-card-wrapper').length : 0);

        countSpan.textContent = finalCount;
        
        // Match the Ads logic for the paragraph
        const parentP = countSpan.closest('p');
        // ALSO get the icon box next to it
        const iconBox = document.querySelector('.bg-secondary-400');

        if (finalCount > 0) {
            if (parentP) parentP.classList.remove('hidden');
            if (iconBox) iconBox.classList.remove('hidden');
        } else {
            if (parentP) parentP.classList.add('hidden');
            if (iconBox) iconBox.classList.add('hidden');
        }
    }
};