// /resources/js/api/advert-packages.js

/**
 * Fetch all available advert packages from the database
 * Returns an array of package objects for the UI components
 */
export async function fetchAdvertPackages() {
    try {
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';
        const response = await fetch(`${baseUrl}api/advert-packages`);
        const result = await response.json();
        
        // We sort by package_order to ensure Free (0) comes first in the UI
        if (result.success && Array.isArray(result.data)) {
            return result.data.sort((a, b) => a.package_order - b.package_order);
        }
        
        return [];
    } catch (err) {
        console.error('Failed to fetch Advert Packages:', err);
        return [];
    }
}