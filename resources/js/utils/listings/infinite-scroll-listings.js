// /resources/js/utils/listings/infinite-scroll-listings.js

export function initListingInfiniteScroll() {
    const grid = document.getElementById('listings-grid');
    if (!grid) return;

    let currentPage = 1;
    let isLoading = false;
    let hasMore = true;
    let throttleTimeout = null;

    const loadMoreListings = async () => {
        if (isLoading || !hasMore) return;

        isLoading = true;

        const sentinel = document.getElementById('listings-load-more-sentinel');
        const spinner = sentinel?.querySelector('.animate-spin');
        if (spinner) {
            spinner.classList.remove('hidden');
        }

        currentPage++;

        const searchInput = document.getElementById('listing-search-input');
        const query = searchInput ? searchInput.value : '';

        try {
            // Using your existing API endpoint with all=true for marketplace
            const response = await fetch(`${window.APP_CONFIG?.baseUrl}api/listings?page=${currentPage}&q=${encodeURIComponent(query)}&all=true`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                const cardsHtml = result.data.map(item => item.cardHtml).join('');

                // Append the cards smoothly
                grid.insertAdjacentHTML('beforeend', cardsHtml);

                // If AOS (Animate On Scroll) is active, refresh it
                if (window.AOS) {
                    window.AOS.refresh();
                }

                hasMore = result.meta.hasMore;
            } else {
                hasMore = false;
            }
        } catch (error) {
            console.error("Listing infinite scroll error:", error);
            currentPage--; // Reset page on failure to allow retry
        } finally {
            isLoading = false;
            if (spinner) {
                spinner.classList.add('hidden');
            }
        }
    };

    const handleScroll = () => {
        if (throttleTimeout) return;

        throttleTimeout = setTimeout(() => {
            throttleTimeout = null;

            // Trigger load when 400px from the bottom
            const scrollBottom = window.innerHeight + window.scrollY;
            const threshold = document.documentElement.scrollHeight - 400;

            if (scrollBottom >= threshold) {
                loadMoreListings();
            }
        }, 200); // 200ms Throttle
    };

    window.addEventListener('listings-search-updated', (e) => {
        currentPage = 1;
        hasMore = e.detail.hasMore !== undefined ? e.detail.hasMore : true;
    });

    window.addEventListener('scroll', handleScroll);
}
