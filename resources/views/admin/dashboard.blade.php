@extends('admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
    <div class="rounded-lg border border-line bg-white p-5">
        <p class="text-sm text-stone-500">Pending orders</p>
        <p id="stat-pending" class="mt-2 text-2xl font-semibold tabular-nums text-ink">—</p>
    </div>
    <div class="rounded-lg border border-line bg-white p-5">
        <p class="text-sm text-stone-500">Orders, total</p>
        <p id="stat-orders" class="mt-2 text-2xl font-semibold tabular-nums text-ink">—</p>
    </div>
    <div class="rounded-lg border border-line bg-white p-5">
        <p class="text-sm text-stone-500">Products</p>
        <p id="stat-products" class="mt-2 text-2xl font-semibold tabular-nums text-ink">—</p>
    </div>
    <div class="rounded-lg border border-line bg-white p-5">
        <p class="text-sm text-stone-500">Your role</p>
        <p id="stat-role" class="mt-2 text-2xl font-semibold text-ink">—</p>
    </div>
</div>

<div class="mt-8 rounded-lg border border-line bg-white">
    <div class="flex items-center justify-between border-b border-line px-5 py-4">
        <h2 class="text-sm font-semibold text-ink">Recent orders</h2>
        <a href="/admin/orders" class="text-sm text-gold hover:underline">View all</a>
    </div>
    <table class="w-full text-sm">
        <tbody id="recent-orders"></tbody>
    </table>
</div>

<div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
    <a href="/admin/categories" class="rounded-lg border border-line bg-white p-5 transition-colors hover:border-gold/40">
        <p class="font-medium text-ink">Manage categories</p>
        <p class="mt-1 text-sm text-stone-500">Organize the category tree customers browse.</p>
    </a>
    <a href="/admin/products" class="rounded-lg border border-line bg-white p-5 transition-colors hover:border-gold/40">
        <p class="font-medium text-ink">Manage products</p>
        <p class="mt-1 text-sm text-stone-500">Add products, set prices and stock.</p>
    </a>
</div>
@endsection

@section('scripts')
<script>
    const statusPill = {
        pending: 'bg-stone-100 text-stone-600', confirmed: 'bg-status-slate-soft text-status-slate',
        processing: 'bg-status-amber-soft text-status-amber', packed: 'bg-status-amber-soft text-status-amber',
        shipped: 'bg-status-slate-soft text-status-slate', delivered: 'bg-status-sage-soft text-status-sage',
        cancelled: 'bg-status-rust-soft text-status-rust', on_hold: 'bg-status-rust-soft text-status-rust',
    };

    AdminApi.call('/auth/me').then((res) => {
        document.getElementById('stat-role').textContent = res.data.roles[0] ?? '—';
    });

    AdminApi.call('/orders?per_page=1').then((res) => {
        document.getElementById('stat-orders').textContent = res.meta.pagination.total;
    });
    AdminApi.call('/orders?filter[status]=pending&per_page=1').then((res) => {
        document.getElementById('stat-pending').textContent = res.meta.pagination.total;
    });
    AdminApi.call('/products?per_page=1').then((res) => {
        document.getElementById('stat-products').textContent = res.meta.pagination.total;
    });

    AdminApi.call('/orders?per_page=6').then((res) => {
        document.getElementById('recent-orders').innerHTML = res.data.map((o) => `
            <tr class="border-b border-line last:border-0">
                <td class="px-5 py-3 font-medium tabular-nums">${o.order_no}</td>
                <td class="px-5 py-3"><span class="status-pill ${statusPill[o.status] ?? ''}">${o.status}</span></td>
                <td class="px-5 py-3 tabular-nums text-right">৳${(o.grand_total / 100).toFixed(2)}</td>
                <td class="px-5 py-3 text-right"><a href="/admin/orders/${o.id}" class="text-gold hover:underline">View</a></td>
            </tr>
        `).join('') || '<tr><td class="px-5 py-6 text-center text-stone-400" colspan="4">No orders yet.</td></tr>';
    });
</script>
@endsection
