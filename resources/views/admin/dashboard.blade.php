@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-full">
    <nav class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <span class="text-lg font-semibold text-gray-900">Bazardev Ecom — Admin</span>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span id="user-label"></span>
                <button id="logout-btn" class="rounded-md border border-gray-300 px-3 py-1.5 hover:bg-gray-50">Logout</button>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Phase 0 foundation is live. Catalog, orders and reports land here as those modules are built.</p>

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
    </main>
</div>
@endsection

@section('scripts')
<script>
    if (!AdminApi.isLoggedIn()) window.location.href = '/admin/login';

    AdminApi.call('/auth/me').then((res) => {
        const user = res.data;
        document.getElementById('user-label').textContent = `${user.name} (${user.email})`;
        document.getElementById('stat-role').textContent = user.roles.join(', ') || '—';
        document.getElementById('stat-permissions').textContent = user.permissions.length;
        document.getElementById('stat-login').textContent = user.last_login_at
            ? new Date(user.last_login_at).toLocaleString()
            : '—';
    });

    document.getElementById('logout-btn').addEventListener('click', async () => {
        await AdminApi.call('/auth/logout', { method: 'POST' });
        AdminApi.clearToken();
        window.location.href = '/admin/login';
    });
</script>
@endsection
