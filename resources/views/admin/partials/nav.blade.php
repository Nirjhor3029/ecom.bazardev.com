<nav class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex items-center gap-8">
            <a href="/admin" class="text-lg font-semibold text-gray-900">Bazardev Ecom</a>
            <div class="hidden gap-6 text-sm font-medium text-gray-600 sm:flex">
                <a href="/admin" class="hover:text-gray-900">Dashboard</a>
                <a href="/admin/categories" class="hover:text-gray-900">Categories</a>
                <a href="/admin/products" class="hover:text-gray-900">Products</a>
                <a href="/admin/orders" class="hover:text-gray-900">Orders</a>
            </div>
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <span id="nav-user-label"></span>
            <button id="nav-logout-btn" class="rounded-md border border-gray-300 px-3 py-1.5 hover:bg-gray-50">Logout</button>
        </div>
    </div>
</nav>

<script>
    if (!AdminApi.isLoggedIn()) window.location.href = '/admin/login';

    AdminApi.call('/auth/me').then((res) => {
        document.getElementById('nav-user-label').textContent = res.data.name;
    });

    document.getElementById('nav-logout-btn').addEventListener('click', async () => {
        await AdminApi.call('/auth/logout', { method: 'POST' });
        AdminApi.clearToken();
        window.location.href = '/admin/login';
    });
</script>
