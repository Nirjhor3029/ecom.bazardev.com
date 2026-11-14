@extends('admin.app')

@section('title', 'Customers')

@section('content')
<div class="mb-4 flex items-center gap-2">
    <input id="q" placeholder="Search by name or phone…" class="field-input max-w-xs">
    <label class="flex items-center gap-2 text-sm text-stone-600">
        <input id="blocked-only" type="checkbox" class="h-4 w-4 rounded border-line text-gold">
        Blocked only
    </label>
</div>

<div class="overflow-hidden rounded-lg border border-line bg-white">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-stone-50/60 text-left text-stone-500">
            <tr>
                <th class="px-5 py-3 font-medium">Name</th>
                <th class="px-5 py-3 font-medium">Phone</th>
                <th class="px-5 py-3 font-medium">Orders</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Joined</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody id="rows"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    async function load() {
        const params = new URLSearchParams();
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);
        if (document.getElementById('blocked-only').checked) params.set('blocked_only', '1');

        const res = await AdminApi.call('/customers?' + params.toString());

        document.getElementById('rows').innerHTML = res.data.map((c) => `
            <tr class="border-b border-line last:border-0">
                <td class="px-5 py-3">${c.name ?? '<span class="text-stone-400">—</span>'}</td>
                <td class="px-5 py-3 tabular-nums">${c.phone}</td>
                <td class="px-5 py-3 tabular-nums text-stone-500">${c.orders_count}</td>
                <td class="px-5 py-3">${c.is_blocked ? '<span class="status-pill bg-status-rust-soft text-status-rust">Blocked</span>' : '<span class="status-pill bg-status-sage-soft text-status-sage">Active</span>'}</td>
                <td class="px-5 py-3 text-stone-500">${new Date(c.created_at).toLocaleDateString()}</td>
                <td class="px-5 py-3 text-right"><a href="/admin/customers/${c.id}" class="text-gold hover:underline">View</a></td>
            </tr>
        `).join('') || '<tr><td colspan="6" class="px-5 py-8 text-center text-stone-400">No customers yet.</td></tr>';
    }

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });
    document.getElementById('blocked-only').addEventListener('change', load);

    load();
</script>
@endsection
