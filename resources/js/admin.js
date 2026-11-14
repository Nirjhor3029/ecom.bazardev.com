const TOKEN_KEY = 'ecom_admin_token';

function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

function setToken(token) {
    localStorage.setItem(TOKEN_KEY, token);
}

function clearToken() {
    localStorage.removeItem(TOKEN_KEY);
}

/**
 * Thin client for the Admin API (/api/v1/admin/*). Every admin screen
 * should go through this — never call price/stock/order logic directly
 * from a view, the API is the single source of truth (PRD API-7).
 *
 * Exposed as `window.AdminApi` (not an ES import) on purpose: admin pages
 * are plain Blade + <script> tags, no bundler-aware page scripts, so
 * anyone editing a view can just use `AdminApi.call(...)` directly.
 */
async function call(path, { method = 'GET', body = null } = {}) {
    const headers = {
        Accept: 'application/json',
        Authorization: `Bearer ${getToken() ?? ''}`,
    };

    if (body) headers['Content-Type'] = 'application/json';

    const res = await fetch(`/api/v1/admin${path}`, {
        method,
        headers,
        body: body ? JSON.stringify(body) : null,
    });

    if (res.status === 401) {
        clearToken();
        window.location.href = '/admin/login';
        return null;
    }

    const data = res.status === 204 ? null : await res.json();

    if (!res.ok) {
        const err = new Error(data?.error?.message ?? 'Request failed');
        err.code = data?.error?.code;
        err.fields = data?.error?.fields;
        throw err;
    }

    return data;
}

window.AdminApi = { call, getToken, setToken, clearToken, isLoggedIn: () => !!getToken() };
