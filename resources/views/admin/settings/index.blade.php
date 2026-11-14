@extends('admin.app')

@section('title', 'Settings')

@section('content')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <h2 class="text-sm font-semibold text-ink">Store settings</h2>
        <p class="mt-0.5 text-sm text-stone-500">Grouped by area — change one and it saves on blur.</p>
        <div id="settings-groups" class="mt-4 space-y-5"></div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-ink">Feature flags</h2>
        <p class="mt-0.5 text-sm text-stone-500">Turning one off removes it entirely — route, menu and storefront display.</p>
        <div id="features" class="mt-4 space-y-2 rounded-lg border border-line bg-white p-4"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function renderValue(v) {
        return typeof v === 'object' && v !== null ? JSON.stringify(v) : v;
    }

    function parseValue(raw, original) {
        if (typeof original === 'boolean') return raw === 'true' || raw === true;
        if (typeof original === 'number') return Number(raw);
        if (typeof original === 'object' && original !== null) {
            try { return JSON.parse(raw); } catch (e) { return original; }
        }
        return raw;
    }

    async function load() {
        const res = await AdminApi.call('/settings');
        const groups = {};
        res.data.settings.forEach((s) => { (groups[s.group] ??= []).push(s); });

        document.getElementById('settings-groups').innerHTML = Object.entries(groups).map(([group, items]) => `
            <div class="rounded-lg border border-line bg-white p-4">
                <p class="text-xs font-medium text-stone-500">${group}</p>
                <div class="mt-2 space-y-2">
                    ${items.map((s) => `
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-sm text-stone-700" for="setting-${s.key}">${s.key}</label>
                            <input id="setting-${s.key}" data-key="${s.key}" data-original='${JSON.stringify(s.value)}'
                                value="${renderValue(s.value) ?? ''}" class="setting-input field-input max-w-[160px] py-1.5 text-sm">
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('') || '<p class="text-sm text-stone-400">No settings yet.</p>';

        document.getElementById('features').innerHTML = res.data.features.map((f) => `
            <label class="flex items-center justify-between gap-3 py-1.5">
                <span class="text-sm text-stone-700">${f.key.replace(/_/g, ' ')}</span>
                <input type="checkbox" data-feature="${f.key}" ${f.enabled ? 'checked' : ''}
                    class="feature-toggle h-5 w-9 cursor-pointer appearance-none rounded-full bg-stone-300 transition-colors checked:bg-gold relative">
            </label>
        `).join('') || '<p class="text-sm text-stone-400">No feature flags yet.</p>';
    }

    document.getElementById('settings-groups').addEventListener('focusout', async (e) => {
        if (!e.target.classList.contains('setting-input')) return;
        const key = e.target.dataset.key;
        const original = JSON.parse(e.target.dataset.original);
        const value = parseValue(e.target.value, original);

        if (JSON.stringify(value) === e.target.dataset.original) return;

        await AdminApi.call(`/settings/${key}`, { method: 'PATCH', body: { value } });
        e.target.dataset.original = JSON.stringify(value);
    });

    document.getElementById('features').addEventListener('change', async (e) => {
        if (!e.target.classList.contains('feature-toggle')) return;
        await AdminApi.call(`/features/${e.target.dataset.feature}`, { method: 'PATCH', body: { enabled: e.target.checked } });
    });

    load();
</script>
@endsection
