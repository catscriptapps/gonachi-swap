// /resources/js/api/contractor-types-api.js

export async function fetchContractorTypes() {
    const baseUrl = window.APP_CONFIG?.baseUrl || '/';
    const url = `${baseUrl}api/contractor-types`;

    const res = await fetch(url);
    const json = await res.json();

    return json.success ? json.data : [];
}