@extends('admin.app')

@section('title', 'Order')

@section('header-actions')
    <a href="/admin/orders" class="text-sm text-stone-500 hover:text-ink">&larr; Back to orders</a>
@endsection

@section('content')
<div class="flex items-center justify-between">
    <h2 id="order-title" class="text-xl font-semibold text-ink">—</h2>
    <div id="transition-buttons" class="flex gap-2"></div>
</div>

<!-- Stage tracker: a real sequence (pending -> ... -> delivered), so a step
     indicator is earning its place here rather than decorating. -->
<div id="stepper" class="mt-6 hidden items-center rounded-lg border border-line bg-white px-6 py-5"></div>
<div id="terminal-banner" class="mt-6 hidden rounded-lg px-4 py-3 text-sm font-medium"></div>

<div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
    <div class="space-y-6 sm:col-span-2">
        <div class="rounded-lg border border-line bg-white p-5">
            <h2 class="text-sm font-semibold text-ink">Items</h2>
            <table class="mt-3 w-full text-sm">
                <tbody id="items"></tbody>
            </table>
            <div id="totals" class="mt-3 border-t border-line pt-3 text-sm"></div>
        </div>

        <div class="rounded-lg border border-line bg-white p-5">
            <h2 class="text-sm font-semibold text-ink">Status history</h2>
            <ul id="history" class="mt-3 space-y-1.5 text-sm text-stone-600"></ul>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-lg border border-line bg-white p-5">
            <h2 class="text-sm font-semibold text-ink">Shipping address</h2>
            <div id="address" class="mt-2 text-sm text-stone-600"></div>
        </div>

        <div class="rounded-lg border border-line bg-white p-5">
            <h2 class="text-sm font-semibold text-ink">Shipment</h2>
            <button id="book-shipment-btn" class="btn-secondary mt-3 w-full">Book courier</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const orderId = @json($orderId);

    // Mirrors App\Modules\Orders\Services\OrderStateMachine — the backend
    // is the real authority; this just decides which buttons/steps to show.
    const TRANSITIONS = {
        pending: ['confirmed', 'on_hold', 'cancelled'],
        confirmed: ['processing', 'on_hold', 'cancelled'],
        on_hold: ['confirmed', 'cancelled'],
        processing: ['packed', 'cancelled'],
        packed: ['shipped', 'cancelled'],
        shipped: ['delivered', 'returned'],
        delivered: ['returned'],
    };
    const STAGES = ['pending', 'confirmed', 'processing', 'packed', 'shipped', 'delivered'];
    const TERMINAL = {
        cancelled: 'bg-status-rust-soft text-status-rust',
        on_hold: 'bg-status-amber-soft text-status-amber',
        returned: 'bg-status-rust-soft text-status-rust',
    };

    function renderStepper(status) {
        const stepperEl = document.getElementById('stepper');
        const bannerEl = document.getElementById('terminal-banner');

        if (TERMINAL[status]) {
            stepperEl.classList.add('hidden');
            bannerEl.className = `mt-6 rounded-lg px-4 py-3 text-sm font-medium ${TERMINAL[status]}`;
            bannerEl.textContent = status === 'on_hold' ? 'This order is on hold.' : `This order was ${status}.`;
            return;
        }

        bannerEl.classList.add('hidden');
        stepperEl.classList.remove('hidden');
        stepperEl.classList.add('flex');

        const currentIndex = STAGES.indexOf(status);
        stepperEl.innerHTML = STAGES.map((stage, i) => {
            const done = i <= currentIndex;
            const circle = done
                ? `<span class="flex h-6 w-6 items-center justify-center rounded-full bg-gold text-[11px] font-semibold text-white">${i + 1}</span>`
                : `<span class="flex h-6 w-6 items-center justify-center rounded-full border border-line text-[11px] text-stone-400">${i + 1}</span>`;
            const line = i < STAGES.length - 1
                ? `<span class="mx-2 h-px flex-1 ${i < currentIndex ? 'bg-gold' : 'bg-line'}"></span>` : '';
            return `<div class="flex flex-1 items-center">
                        <div class="flex items-center gap-2">${circle}<span class="text-xs font-medium capitalize ${done ? 'text-ink' : 'text-stone-400'}">${stage}</span></div>
                        ${line}
                    </div>`;
        }).join('');
    }

    async function load() {
        const res = await AdminApi.call(`/orders/${orderId}`);
        const o = res.data;

        document.getElementById('order-title').textContent = `Order ${o.order_no}`;
        renderStepper(o.status);

        document.getElementById('items').innerHTML = o.items.map((i) => `
            <tr class="border-b border-stone-100 last:border-0">
                <td class="py-1.5">${i.name.bn ?? i.name}</td>
                <td class="py-1.5 text-stone-500">${i.sku}</td>
                <td class="py-1.5 text-right tabular-nums">×${i.qty}</td>
                <td class="py-1.5 text-right tabular-nums">৳${(i.line_total / 100).toFixed(2)}</td>
            </tr>
        `).join('');

        document.getElementById('totals').innerHTML = `
            <div class="flex justify-between text-stone-600"><span>Subtotal</span><span class="tabular-nums">৳${(o.subtotal / 100).toFixed(2)}</span></div>
            <div class="flex justify-between text-stone-600"><span>Delivery</span><span class="tabular-nums">৳${(o.delivery_fee / 100).toFixed(2)}</span></div>
            <div class="flex justify-between text-stone-600"><span>Discount</span><span class="tabular-nums">-৳${(o.discount_total / 100).toFixed(2)}</span></div>
            <div class="flex justify-between pt-1 font-semibold text-ink"><span>Grand total</span><span class="tabular-nums">৳${(o.grand_total / 100).toFixed(2)}</span></div>
            <div class="flex justify-between pt-2 text-stone-500"><span>Payment (${o.payment_method})</span><span>${o.payment_status}</span></div>
        `;

        document.getElementById('address').innerHTML = `
            ${o.shipping_address.name}<br>${o.shipping_address.phone}<br>
            ${o.shipping_address.address_line}, ${o.shipping_address.area ?? ''}<br>${o.shipping_address.district}
        `;

        document.getElementById('history').innerHTML = o.status_history.map((h) => `
            <li>${h.from ?? 'created'} → <strong class="text-ink">${h.to}</strong> <span class="text-stone-400">${new Date(h.created_at).toLocaleString()}</span></li>
        `).join('');

        document.getElementById('transition-buttons').innerHTML = (TRANSITIONS[o.status] ?? []).map((to) =>
            `<button data-to="${to}" class="btn-secondary capitalize">${to}</button>`
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
