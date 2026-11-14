@extends('admin.app')

@section('title', 'Orders')

@section('content')
<div class="mb-4 flex gap-2">
    <input id="q" placeholder="Search order no. or phone…" class="field-input max-w-xs">
    <select id="status" class="field-input max-w-[160px]">
        <option value="">All statuses</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="processing">Processing</option>
        <option value="packed">Packed</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
        <option value="on_hold">On hold</option>
    </select>
</div>

<div class="overflow-hidden rounded-lg border border-line bg-white">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-stone-50/60 text-left text-stone-500">
            <tr>
                <th class="px-5 py-3 font-medium">Order #</th>
                <th class="px-5 py-3 font-medium">Phone</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Payment</th>
                <th class="px-5 py-3 font-medium text-right">Total</th>
                <th class="px-5 py-3 font-medium">Placed</th>
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
        pending: 'bg-stone-100 text-stone-500', confirmed: 'bg-status-slate-soft text-status-slate',
        processing: 'bg-status-amber-soft text-status-amber', packed: 'bg-status-amber-soft text-status-amber',
        shipped: 'bg-status-slate-soft text-status-slate', delivered: 'bg-status-sage-soft text-status-sage',
        cancelled: 'bg-status-rust-soft text-status-rust', on_hold: 'bg-status-rust-soft text-status-rust',
    };

    async function load() {
        const params = new URLSearchParams();
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);
        if (document.getElementById('status').value) params.set('filter[status]', document.getElementById('status').value);

        const res = await AdminApi.call('/orders?' + params.toString());

        document.getElementById('rows').innerHTML = res.data.map((o) => `
            <tr class="border-b border-line last:border-0">
                <td class="px-5 py-3 font-medium tabular-nums">${o.order_no}</td>
                <td class="px-5 py-3 tabular-nums text-stone-500">${o.shipping_address?.phone ?? '—'}</td>
                <td class="px-5 py-3"><span class="status-pill ${statusPill[o.status] ?? ''}">${o.status}</span></td>
                <td class="px-5 py-3 text-stone-500">${o.payment_status}</td>
                <td class="px-5 py-3 tabular-nums text-right">৳${(o.grand_total / 100).toFixed(2)}</td>
                <td class="px-5 py-3 text-stone-500">${o.placed_at ? new Date(o.placed_at).toLocaleDateString() : '—'}</td>
                <td class="px-5 py-3 text-right"><a href="/admin/orders/${o.id}" class="text-gold hover:underline">View</a></td>
            </tr>
        `).join('') || '<tr><td colspan="7" class="px-5 py-8 text-center text-stone-400">No orders yet.</td></tr>';
    }

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });
    document.getElementById('status').addEventListener('change', load);

    load();
</script>
@endsection
