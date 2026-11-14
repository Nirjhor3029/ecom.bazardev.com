@extends('admin.app')

@section('title', 'Customer')

@section('header-actions')
    <a href="/admin/customers" class="text-sm text-stone-500 hover:text-ink">&larr; Back to customers</a>
@endsection

@section('content')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
    <div class="space-y-6 sm:col-span-2">
        <div class="rounded-lg border border-line bg-white p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-ink">Recent orders</h2>
            </div>
            <table class="mt-3 w-full text-sm">
                <tbody id="orders"></tbody>
            </table>
        </div>

        <div class="rounded-lg border border-line bg-white p-5">
            <h2 class="text-sm font-semibold text-ink">Addresses</h2>
            <div id="addresses" class="mt-3 space-y-3 text-sm text-stone-600"></div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-lg border border-line bg-white p-5">
            <p id="c-name" class="text-lg font-semibold text-ink">—</p>
            <p id="c-phone" class="mt-1 tabular-nums text-stone-500">—</p>
            <p id="c-status" class="mt-3"></p>

            <dl class="mt-4 space-y-1.5 border-t border-line pt-4 text-sm">
                <div class="flex justify-between"><dt class="text-stone-500">Total orders</dt><dd id="c-orders" class="tabular-nums font-medium">—</dd></div>
                <div class="flex justify-between"><dt class="text-stone-500">Risk score</dt><dd id="c-risk" class="tabular-nums font-medium">—</dd></div>
                <div class="flex justify-between"><dt class="text-stone-500">Joined</dt><dd id="c-joined" class="font-medium">—</dd></div>
            </dl>

            <button id="toggle-block-btn" class="btn-secondary mt-5 w-full"></button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const customerId = @json($customerId);
    let customer = null;

    const statusPill = {
        pending: 'bg-stone-100 text-stone-500', confirmed: 'bg-status-slate-soft text-status-slate',
        processing: 'bg-status-amber-soft text-status-amber', packed: 'bg-status-amber-soft text-status-amber',
        shipped: 'bg-status-slate-soft text-status-slate', delivered: 'bg-status-sage-soft text-status-sage',
        cancelled: 'bg-status-rust-soft text-status-rust', on_hold: 'bg-status-rust-soft text-status-rust',
    };

    async function load() {
        const res = await AdminApi.call(`/customers/${customerId}`);
        customer = res.data;

        document.getElementById('c-name').textContent = customer.name || 'Unnamed customer';
        document.getElementById('c-phone').textContent = customer.phone;
        document.getElementById('c-status').innerHTML = customer.is_blocked
            ? '<span class="status-pill bg-status-rust-soft text-status-rust">Blocked</span>'
            : '<span class="status-pill bg-status-sage-soft text-status-sage">Active</span>';
        document.getElementById('c-orders').textContent = customer.orders_count ?? customer.orders.length;
        document.getElementById('c-risk').textContent = customer.risk_score;
        document.getElementById('c-joined').textContent = new Date(customer.created_at).toLocaleDateString();

        const btn = document.getElementById('toggle-block-btn');
        btn.textContent = customer.is_blocked ? 'Unblock customer' : 'Block customer';
        btn.className = customer.is_blocked ? 'btn-primary mt-5 w-full' : 'btn-secondary mt-5 w-full';

        document.getElementById('orders').innerHTML = customer.orders.map((o) => `
            <tr class="border-b border-stone-100 last:border-0">
                <td class="py-2 font-medium tabular-nums">${o.order_no}</td>
                <td class="py-2"><span class="status-pill ${statusPill[o.status] ?? ''}">${o.status}</span></td>
                <td class="py-2 text-right tabular-nums">৳${(o.grand_total / 100).toFixed(2)}</td>
                <td class="py-2 text-right"><a href="/admin/orders/${o.id ?? ''}" class="text-gold hover:underline">View</a></td>
            </tr>
        `).join('') || '<tr><td class="py-4 text-center text-stone-400" colspan="4">No orders yet.</td></tr>';

        document.getElementById('addresses').innerHTML = customer.addresses.map((a) => `
            <div class="rounded-md border border-line p-3">
                <p class="font-medium text-ink">${a.name} · ${a.phone}</p>
                <p class="text-stone-500">${a.address_line}, ${a.area ?? ''}</p>
            </div>
        `).join('') || '<p class="text-stone-400">No saved addresses.</p>';
    }

    document.getElementById('toggle-block-btn').addEventListener('click', async () => {
        const action = customer.is_blocked ? 'unblock' : 'block';
        if (!confirm(`${action === 'block' ? 'Block' : 'Unblock'} this customer?`)) return;
        await AdminApi.call(`/customers/${customerId}/${action}`, { method: 'POST' });
        load();
    });

    load();
</script>
@endsection
