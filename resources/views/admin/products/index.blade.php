@extends('admin.app')

@section('title', 'Products')

@section('header-actions')
    <a href="/admin/products/create" class="btn-primary">+ New product</a>
@endsection

@section('content')
<div class="mb-4 flex gap-2">
    <input id="q" placeholder="Search by name…" class="field-input max-w-xs">
    <select id="status" class="field-input max-w-[160px]">
        <option value="">All statuses</option>
        <option value="draft">Draft</option>
        <option value="active">Active</option>
        <option value="archived">Archived</option>
    </select>
</div>

<div class="overflow-hidden rounded-lg border border-line bg-white">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-stone-50/60 text-left text-stone-500">
            <tr>
                <th class="px-5 py-3 font-medium">Name</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Variants</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody id="rows"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    const statusPill = {
        active: 'bg-status-sage-soft text-status-sage',
        draft: 'bg-stone-100 text-stone-500',
        archived: 'bg-status-rust-soft text-status-rust',
    };

    async function load() {
        const params = new URLSearchParams();
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);
        if (document.getElementById('status').value) params.set('status', document.getElementById('status').value);

        const res = await AdminApi.call('/products?' + params.toString());

        document.getElementById('rows').innerHTML = res.data.map((p) => `
            <tr class="border-b border-line last:border-0">
                <td class="px-5 py-3">${p.name.bn}${p.name.en ? ` <span class="text-stone-400">(${p.name.en})</span>` : ''}</td>
                <td class="px-5 py-3"><span class="status-pill ${statusPill[p.status] ?? ''}">${p.status}</span></td>
                <td class="px-5 py-3 tabular-nums text-stone-500">${p.variants.length}</td>
                <td class="px-5 py-3 text-right"><a href="/admin/products/${p.id}/edit" class="text-gold hover:underline">Edit</a></td>
            </tr>
        `).join('') || '<tr><td colspan="4" class="px-5 py-8 text-center text-stone-400">No products yet.</td></tr>';
    }

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });
    document.getElementById('status').addEventListener('change', load);

    load();
</script>
@endsection
