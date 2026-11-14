@extends('admin.layout')

@section('title', 'Order')

@section('content')
<div class="min-h-full">
    @include('admin.partials.nav')

    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <a href="/admin/orders" class="text-sm text-gray-500 hover:underline">&larr; Back to orders</a>

        <div class="mt-2 flex items-center justify-between">
            <h1 id="order-title" class="text-xl font-semibold text-gray-900">Order</h1>
            <div id="transition-buttons" class="flex gap-2"></div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div class="sm:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <h2 class="font-medium text-gray-900">Items</h2>
                    <table class="mt-3 w-full text-sm">
                        <tbody id="items"></tbody>
                    </table>
                    <div class="mt-3 border-t border-gray-100 pt-3 text-sm" id="totals"></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <h2 class="font-medium text-gray-900">Status history</h2>
                    <ul id="history" class="mt-3 space-y-1 text-sm text-gray-600"></ul>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <h2 class="font-medium text-gray-900">Shipping address</h2>
                    <div id="address" class="mt-2 text-sm text-gray-600"></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <h2 class="font-medium text-gray-900">Shipment</h2>
                    <button id="book-shipment-btn" class="mt-2 w-full rounded-md border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">Book courier</button>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    const orderId = @json($orderId);

    // Mirrors App\Modules\Orders\Services\OrderStateMachine — the backend
    // is the real authority; this just decides which buttons to show.
    const TRANSITIONS = {
        pending: ['confirmed', 'on_hold', 'cancelled'],
        confirmed: ['processing', 'on_hold', 'cancelled'],
        on_hold: ['confirmed', 'cancelled'],
        processing: ['packed', 'cancelled'],
        packed: ['shipped', 'cancelled'],
        shipped: ['delivered', 'returned'],
        delivered: ['returned'],
    };

    async function load() {
        const res = await AdminApi.call(`/orders/${orderId}`);
        const o = res.data;

        document.getElementById('order-title').textContent = `Order ${o.order_no} — ${o.status}`;

        document.getElementById('items').innerHTML = o.items.map((i) => `
            <tr class="border-b border-gray-50">
                <td class="py-1">${i.name.bn ?? i.name}</td>
                <td class="py-1 text-gray-500">${i.sku}</td>
                <td class="py-1 text-right">×${i.qty}</td>
                <td class="py-1 text-right">৳${(i.line_total / 100).toFixed(2)}</td>
            </tr>
        `).join('');

        document.getElementById('totals').innerHTML = `
            <div class="flex justify-between"><span>Subtotal</span><span>৳${(o.subtotal / 100).toFixed(2)}</span></div>
            <div class="flex justify-between"><span>Delivery</span><span>৳${(o.delivery_fee / 100).toFixed(2)}</span></div>
            <div class="flex justify-between"><span>Discount</span><span>-৳${(o.discount_total / 100).toFixed(2)}</span></div>
            <div class="flex justify-between font-medium"><span>Grand total</span><span>৳${(o.grand_total / 100).toFixed(2)}</span></div>
            <div class="flex justify-between text-gray-500"><span>Payment (${o.payment_method})</span><span>${o.payment_status}</span></div>
        `;

        document.getElementById('address').innerHTML = `
            ${o.shipping_address.name}<br>${o.shipping_address.phone}<br>
            ${o.shipping_address.address_line}, ${o.shipping_address.area ?? ''}<br>${o.shipping_address.district}
        `;

        document.getElementById('history').innerHTML = o.status_history.map((h) => `
            <li>${h.from ?? 'created'} → <strong>${h.to}</strong> <span class="text-gray-400">${new Date(h.created_at).toLocaleString()}</span></li>
        `).join('');

        document.getElementById('transition-buttons').innerHTML = (TRANSITIONS[o.status] ?? []).map((to) =>
            `<button data-to="${to}" class="transition-btn rounded-md border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">→ ${to}</button>`
        ).join('');
    }

    document.getElementById('transition-buttons').addEventListener('click', async (e) => {
        const to = e.target.dataset.to;
        if (!to) return;
        try {
            await AdminApi.call(`/orders/${orderId}/transition`, { method: 'POST', body: { to } });
            load();
        } catch (err) {
            alert(err.message);
        }
    });

    document.getElementById('book-shipment-btn').addEventListener('click', async () => {
        try {
            const res = await AdminApi.call(`/orders/${orderId}/shipments`, { method: 'POST' });
            alert(`Booked. Tracking: ${res.data.tracking_code}`);
        } catch (err) {
            alert(err.message);
        }
    });

    load();
</script>
@endsection
