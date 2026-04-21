// /resources/js/api/unit-types-api.js

export async function fetchUnitTypes() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const url = `${baseUrl}api/unit-types`;

    const res = await fetch(url);
    const json = await res.json();

    return json.success ? json.data : [];
}