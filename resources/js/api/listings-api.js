// /resources/js/api/listings-api.js

const getBaseUrl = () => window.APP_CONFIG?.baseUrl || '/';

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

/** 1. Property Categories (e.g., Residential, Commercial) */
export const fetchListingCategories = () => fetchData('listing-categories');

/** 2. Category Types (e.g., Apartment, Office Space) */
export const fetchListingCategoryTypes = (categoryId = null) => {
    const query = categoryId ? `?category_id=${categoryId}` : '';
    return fetchData(`listing-category-types${query}`);
};

/** 3. Unit Types (e.g., Room, Full House, Studio) */
export const fetchUnitTypes = () => fetchData('unit-types');

/** 4. House Styles (e.g., Bungalow, Duplex) - only for Unit Type 'House' */
export const fetchHouseTypes = () => fetchData('house-types');

/** 5. Bedrooms & Bathrooms */
export const fetchBedrooms = () => fetchData('bedrooms');
export const fetchBathrooms = () => fetchData('bathrooms');

/** 6. Agreement Types (e.g., For Rent, For Sale, Lease) */
export const fetchAgreementTypes = () => fetchData('agreement-types');

/** 7. Amenities (e.g., Laundry, Dishwasher etc) */
export const fetchAmenities = () => fetchData('amenities');