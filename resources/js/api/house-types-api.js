// /resources/js/api/house-types-api.js

export async function fetchHouseTypes() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const url = `${baseUrl}api/house-types`;

    const res = await fetch(url);
    const json = await res.json();

    return json.success ? json.data : [];
}