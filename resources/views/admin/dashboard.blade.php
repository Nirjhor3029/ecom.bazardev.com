@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-full">
    @include('admin.partials.nav')

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Phase 1 core (catalog, cart, checkout, orders) is live. Reports land here next.</p>

        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Role</p>
                <p id="stat-role" class="mt-1 text-lg font-semibold text-gray-900">—</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Permissions</p>
                <p id="stat-permissions" class="mt-1 text-lg font-semibold text-gray-900">—</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Last login</p>
                <p id="stat-login" class="mt-1 text-lg font-semibold text-gray-900">—</p>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <a href="/admin/categories" class="rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-300">
                <p class="font-medium text-gray-900">Categories</p>
                <p class="mt-1 text-sm text-gray-500">Manage the category tree</p>
            </a>
            <a href="/admin/products" class="rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-300">
                <p class="font-medium text-gray-900">Products</p>
                <p class="mt-1 text-sm text-gray-500">Products, variants, stock</p>
            </a>
            <a href="/admin/orders" class="rounded-lg border border-gray-200 bg-white p-5 hover:border-gray-300">
                <p class="font-medium text-gray-900">Orders</p>
                <p class="mt-1 text-sm text-gray-500">Confirm, pack, ship, deliver</p>
            </a>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    AdminApi.call('/auth/me').then((res) => {
        const user = res.data;
        document.getElementById('stat-role').textContent = user.roles.join(', ') || '—';
        document.getElementById('stat-permissions').textContent = user.permissions.length;
        document.getElementById('stat-login').textContent = user.last_login_at
            ? new Date(user.last_login_at).toLocaleString()
            : '—';
    });
</script>
@endsection
