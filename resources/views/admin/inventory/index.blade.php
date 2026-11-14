@extends('admin.app')

@section('title', 'Inventory')

@section('content')
<div class="mb-4 flex items-center gap-2">
    <input id="q" placeholder="Search by SKU or product name…" class="field-input max-w-xs">
    <label class="flex items-center gap-2 text-sm text-stone-600">
        <input id="low-stock-only" type="checkbox" class="h-4 w-4 rounded border-line text-gold">
        Low stock only
    </label>
</div>

<!-- Adjust stock form, hidden until "Adjust" is clicked on a row -->
<form id="adjust-form" class="mb-6 hidden space-y-4 rounded-lg border border-line bg-white p-5">
    <input type="hidden" id="f-item-id">
    <h2 id="form-title" class="text-sm font-semibold text-ink">Adjust stock — <span id="form-sku"></span></h2>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label">Change (+ to receive, − to remove)</label>
            <input id="f-delta" type="number" required placeholder="e.g. 20 or -5" class="field-input">
        </div>
        <div>
            <label class="field-label">Reason</label>
            <input id="f-reason" required placeholder="e.g. new batch received" class="field-input">
        </div>
    </div>

    <p id="form-error" class="hidden rounded-md bg-status-rust-soft px-3 py-2 text-sm text-status-rust"></p>

    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Save adjustment</button>
        <button type="button" id="cancel-btn" class="btn-secondary">Cancel</button>
    </div>
</form>

<div class="overflow-hidden rounded-lg border border-line bg-white">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-stone-50/60 text-left text-stone-500">
            <tr>
                <th class="px-5 py-3 font-medium">Product</th>
                <th class="px-5 py-3 font-medium">SKU</th>
                <th class="px-5 py-3 font-medium text-right">On hand</th>
                <th class="px-5 py-3 font-medium text-right">Reserved</th>
                <th class="px-5 py-3 font-medium text-right">Available</th>
                <th class="px-5 py-3 font-medium">Status</th>
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
        in_stock: 'bg-status-sage-soft text-status-sage', low_stock: 'bg-status-amber-soft text-status-amber',
        out_of_stock: 'bg-status-rust-soft text-status-rust', preorder: 'bg-status-slate-soft text-status-slate',
    };

    async function load() {
        const params = new URLSearchParams();
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);
        if (document.getElementById('low-stock-only').checked) params.set('low_stock_only', '1');

        const res = await AdminApi.call('/inventory?' + params.toString());

        document.getElementById('rows').innerHTML = res.data.map((i) => `
            <tr class="border-b border-line last:border-0">
                <td class="px-5 py-3">${i.product_name.bn ?? ''} <span class="text-stone-400">${Object.values(i.options || {}).join(', ')}</span></td>
                <td class="px-5 py-3 tabular-nums text-stone-500">${i.sku}</td>
                <td class="px-5 py-3 text-right tabular-nums">${i.on_hand}</td>
                <td class="px-5 py-3 text-right tabular-nums text-stone-500">${i.reserved}</td>
                <td class="px-5 py-3 text-right tabular-nums font-medium">${i.available}</td>
                <td class="px-5 py-3"><span class="status-pill ${statusPill[i.status] ?? ''}">${i.status.replace('_', ' ')}</span></td>
                <td class="px-5 py-3 text-right"><button data-adjust="${i.id}" data-sku="${i.sku}" class="text-gold hover:underline">Adjust</button></td>
            </tr>
        `).join('') || '<tr><td colspan="7" class="px-5 py-8 text-center text-stone-400">No stock items yet.</td></tr>';
    }

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });
    document.getElementById('low-stock-only').addEventListener('change', load);

    document.getElementById('rows').addEventListener('click', (e) => {
        const id = e.target.dataset.adjust;
        if (!id) return;
        document.getElementById('adjust-form').classList.remove('hidden');
        document.getElementById('f-item-id').value = id;
        document.getElementById('form-sku').textContent = e.target.dataset.sku;
        document.getElementById('f-delta').value = '';
        document.getElementById('f-reason').value = '';
        document.getElementById('form-error').classList.add('hidden');
        document.getElementById('adjust-form').scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('cancel-btn').addEventListener('click', () => {
        document.getElementById('adjust-form').classList.add('hidden');
    });

    document.getElementById('adjust-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('f-item-id').value;

        try {
            await AdminApi.call(`/inventory/${id}/adjust`, {
                method: 'POST',
                body: { delta: parseInt(document.getElementById('f-delta').value, 10), reason: document.getElementById('f-reason').value },
            });
            document.getElementById('adjust-form').classList.add('hidden');
            load();
        } catch (err) {
            const errorEl = document.getElementById('form-error');
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        }
    });

    load();
</script>
@endsection
