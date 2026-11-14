@extends('admin.layout')

@section('title', 'Orders')

@section('content')
<div class="min-h-full">
    @include('admin.partials.nav')

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-xl font-semibold text-gray-900">Orders</h1>

        <div class="mt-4 flex gap-2">
            <input id="q" placeholder="Search order no. or phone…" class="w-64 rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
            <select id="status" class="rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm">
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

        <table class="mt-4 w-full border-separate border-spacing-0 overflow-hidden rounded-lg border border-gray-200 bg-white text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-2">Order #</th>
                    <th class="px-4 py-2">Phone</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Payment</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Placed</th>
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
    const statusColor = {
        pending: 'bg-gray-100 text-gray-600', confirmed: 'bg-blue-100 text-blue-700',
        processing: 'bg-amber-100 text-amber-700', packed: 'bg-amber-100 text-amber-700',
        shipped: 'bg-indigo-100 text-indigo-700', delivered: 'bg-green-100 text-green-700',
        cancelled: 'bg-red-100 text-red-700', on_hold: 'bg-red-100 text-red-700',
    };

    async function load() {
        const params = new URLSearchParams();
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);
        if (document.getElementById('status').value) params.set('filter[status]', document.getElementById('status').value);

        const res = await AdminApi.call('/orders?' + params.toString());

        document.getElementById('rows').innerHTML = res.data.map((o) => `
            <tr class="border-t border-gray-100">
                <td class="px-4 py-2 font-medium">${o.order_no}</td>
                <td class="px-4 py-2 text-gray-500">${o.shipping_address?.phone ?? '—'}</td>
                <td class="px-4 py-2"><span class="rounded-full px-2 py-0.5 text-xs ${statusColor[o.status] ?? ''}">${o.status}</span></td>
                <td class="px-4 py-2 text-gray-500">${o.payment_status}</td>
                <td class="px-4 py-2">৳${(o.grand_total / 100).toFixed(2)}</td>
                <td class="px-4 py-2 text-gray-500">${o.placed_at ? new Date(o.placed_at).toLocaleDateString() : '—'}</td>
                <td class="px-4 py-2 text-right"><a href="/admin/orders/${o.id}" class="text-amber-700 hover:underline">View</a></td>
            </tr>
        `).join('') || '<tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">No orders yet.</td></tr>';
    }

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });
    document.getElementById('status').addEventListener('change', load);

    load();
</script>
@endsection
