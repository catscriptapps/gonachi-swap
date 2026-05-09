// /resources/js/api/listings-api.js

/**
 * Helper to get the base URL from global config
 */
const getBaseUrl = () => window.APP_CONFIG?.baseUrl || '/';

/**
 * Internal helper for standardized API fetching
 * @param {string} endpoint 
 * @returns {Promise<Array>}
 */
async function fetchData(endpoint) {
    try {
        const res = await fetch(`${getBaseUrl()}api/${endpoint}`);
        const json = await res.json();
        return json.success ? json.data : [];
    } catch (err) {
        console.error(`API Error fetching ${endpoint}:`, err);
        return [];
    }
}

/** 
 * 1. Listing Categories 
 * Fetches the main categories (e.g., Electronics, Vehicles, Services)
 */
export const fetchListingCategories = () => fetchData('listing-categories');

/** 
 * 2. Listing Types 
 * Fetches the transaction intent (e.g., 1: Swap, 2: Sale, 3: Gift)
 */
export const fetchListingTypes = () => fetchData('listing-types');

/** 
 * 3. Listing Conditions 
 * Fetches the item state (e.g., 1: New, 2: Like New, 3: Used, 4: Parts)
 */
export const fetchListingConditions = () => fetchData('listing-conditions');