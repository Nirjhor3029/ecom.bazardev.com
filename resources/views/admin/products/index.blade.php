@extends('admin.layout')

@section('title', 'Products')

@section('content')
<div class="min-h-full">
    @include('admin.partials.nav')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Products</h1>
            <a href="/admin/products/create" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">+ New Product</a>
        </div>

        <div class="mt-4 flex gap-2">
            <input id="q" placeholder="Search by name…" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            <select id="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                <option value="">All statuses</option>
                <option value="draft">Draft</option>
                <option value="active">Active</option>
                <option value="archived">Archived</option>
            </select>
        </div>

        <table class="mt-4 w-full border-separate border-spacing-0 overflow-hidden rounded-lg border border-gray-200 bg-white text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Variants</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody id="rows"></tbody>
        </table>
    </main>
</div>
@endsection

@section('scripts')
<script>
    async function load() {
        const params = new URLSearchParams();
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);
        if (document.getElementById('status').value) params.set('status', document.getElementById('status').value);

        const res = await AdminApi.call('/products?' + params.toString());

        document.getElementById('rows').innerHTML = res.data.map((p) => `
            <tr class="border-t border-gray-100">
                <td class="px-4 py-2">${p.name.bn}${p.name.en ? ` <span class="text-gray-400">(${p.name.en})</span>` : ''}</td>
                <td class="px-4 py-2">
                    <span class="rounded-full px-2 py-0.5 text-xs ${p.status === 'active' ? 'bg-green-100 text-green-700' : p.status === 'draft' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-700'}">${p.status}</span>
                </td>
                <td class="px-4 py-2 text-gray-500">${p.variants.length}</td>
                <td class="px-4 py-2 text-right">
                    <a href="/admin/products/${p.id}/edit" class="text-amber-700 hover:underline">Edit</a>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">No products yet.</td></tr>';
    }

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });
    document.getElementById('status').addEventListener('change', load);

    load();
</script>
@endsection
