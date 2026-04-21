// /resources/js/api/advert-ctas-api.js

export async function fetchAdvertCtas() {
    try {
        const baseUrl = window.APP_CONFIG?.baseUrl || '/';
        const response = await fetch(`${baseUrl}api/advert-ctas`);
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (err) {
        console.error('Failed to fetch CTAs:', err);
        return [];
    }
}