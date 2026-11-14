<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Bazardev Ecom</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="h-full bg-paper font-sans text-ink antialiased">
    <script>
        // Bounce immediately if there's no token at all — avoids ever
        // painting the authenticated shell for a plain logged-out visitor.
        // A *stale* token still briefly shows the shell until /auth/me
        // below rejects it; AdminApi.call() handles that redirect itself.
        if (!localStorage.getItem('ecom_admin_token')) {
            window.location.href = '/admin/login';
        }
    </script>

    <div class="flex h-full">
        <!-- Sidebar: the one bold, dark anchor in an otherwise quiet, paper-toned UI. -->
        <aside class="flex w-60 flex-shrink-0 flex-col bg-walnut text-stone-200">
            <div class="flex items-center gap-2 px-5 py-5">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gold text-sm">🍯</span>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-white">Bazardev</p>
                    <p class="text-[11px] text-stone-400">Admin</p>
                </div>
            </div>

            <nav class="flex-1 space-y-6 px-3 py-4">
                <div>
                    <a href="/admin" class="nav-link {{ request()->is('admin') ? 'nav-link-active' : '' }}">
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2.5" y="2.5" width="6.5" height="6.5" rx="1.2"/><rect x="11" y="2.5" width="6.5" height="9.5" rx="1.2"/><rect x="2.5" y="11.5" width="6.5" height="6" rx="1.2"/><rect x="11" y="14" width="6.5" height="3.5" rx="1.2"/></svg>
                        Dashboard
                    </a>
                </div>

                <div>
                    <p class="px-3 text-[11px] font-medium text-stone-500">Catalog</p>
                    <div class="mt-1.5 space-y-0.5">
                        <a href="/admin/categories" class="nav-link {{ request()->is('admin/categories*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2.5 5.2A2 2 0 0 1 4.5 3.2h3.4l1.7 1.8h6a2 2 0 0 1 2 2v7.8a2 2 0 0 1-2 2h-11a2 2 0 0 1-2-2V5.2Z"/></svg>
                            Categories
                        </a>
                        <a href="/admin/products" class="nav-link {{ request()->is('admin/products*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2.8 6.2 10 2.5l7.2 3.7v7.6L10 17.5l-7.2-3.7V6.2Z"/><path d="M2.8 6.2 10 9.8l7.2-3.6M10 9.8v7.7"/></svg>
                            Products
                        </a>
                        <a href="/admin/inventory" class="nav-link {{ request()->is('admin/inventory*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6.5 10 3l7 3.5-7 3.5-7-3.5Z"/><path d="M3 6.5v7L10 17l7-3.5v-7"/><path d="M10 10v7"/></svg>
                            Inventory
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[11px] font-medium text-stone-500">Sales</p>
                    <div class="mt-1.5 space-y-0.5">
                        <a href="/admin/orders" class="nav-link {{ request()->is('admin/orders*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4.5 2.5h11v15l-2-1.3-1.8 1.3-1.7-1.3-1.8 1.3-1.7-1.3-1.8 1.3v-15Z"/><path d="M7 7h6M7 10.3h6M7 13.6h3.5"/></svg>
                            Orders
                        </a>
                        <a href="/admin/customers" class="nav-link {{ request()->is('admin/customers*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="10" cy="6.5" r="3"/><path d="M3.5 17c0-3.5 3-5.5 6.5-5.5s6.5 2 6.5 5.5"/></svg>
                            Customers
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[11px] font-medium text-stone-500">Store</p>
                    <div class="mt-1.5 space-y-0.5">
                        <a href="/admin/media" class="nav-link {{ request()->is('admin/media*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2.5" y="3.5" width="15" height="13" rx="1.4"/><circle cx="7" cy="8" r="1.4"/><path d="M3.2 14.5 7.5 10l2.5 2.5 3-3.5 4 4"/></svg>
                            Media
                        </a>
                        <a href="/admin/settings" class="nav-link {{ request()->is('admin/settings*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="10" cy="10" r="2.6"/><path d="M10 2.8v2.3M10 14.9v2.3M17.2 10h-2.3M5.1 10H2.8M15.2 4.8l-1.6 1.6M6.4 13.6l-1.6 1.6M15.2 15.2l-1.6-1.6M6.4 6.4 4.8 4.8"/></svg>
                            Settings
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[11px] font-medium text-stone-500">Admin</p>
                    <div class="mt-1.5 space-y-0.5">
                        <a href="/admin/users" class="nav-link {{ request()->is('admin/users*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="7" cy="6.5" r="2.5"/><path d="M2.5 16c0-2.8 2-4.5 4.5-4.5s4.5 1.7 4.5 4.5"/><circle cx="14.5" cy="6" r="2"/><path d="M12.8 11.8c2-.1 3.7 1.5 3.7 4.2"/></svg>
                            Users
                        </a>
                        <a href="/admin/roles" class="nav-link {{ request()->is('admin/roles*') ? 'nav-link-active' : '' }}">
                            <svg class="h-[18px] w-[18px]" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 2.8 16 5.3v4.9c0 4-2.6 6.3-6 7.4-3.4-1.1-6-3.4-6-7.4V5.3L10 2.8Z"/><path d="M7.3 10 9 11.7l3.7-3.7"/></svg>
                            Roles
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Content -->
        <div class="flex min-w-0 flex-1 flex-col overflow-y-auto">
            <header class="flex items-center justify-between border-b border-line bg-white px-8 py-4">
                <h1 class="text-lg font-semibold text-ink">@yield('title', 'Dashboard')</h1>

                <div class="flex items-center gap-4">
                    @yield('header-actions')

                    <!-- Profile menu -->
                    <div class="relative">
                        <button id="profile-menu-btn" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 hover:bg-stone-100">
                            <span id="profile-avatar" class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-gold-soft text-sm font-semibold text-gold">?</span>
                            <span id="profile-name" class="hidden text-sm font-medium text-ink sm:inline">—</span>
                            <svg class="h-4 w-4 text-stone-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 8l5 5 5-5"/></svg>
                        </button>

                        <div id="profile-menu" class="absolute right-0 top-11 hidden w-48 rounded-lg border border-line bg-white py-1.5 shadow-lg">
                            <p id="profile-menu-email" class="truncate border-b border-line px-3.5 py-2 text-xs text-stone-500"></p>
                            <a href="/admin/profile" class="block px-3.5 py-2 text-sm text-ink hover:bg-stone-50">Your profile</a>
                            <button id="logout-btn" class="block w-full px-3.5 py-2 text-left text-sm text-status-rust hover:bg-status-rust-soft">Log out</button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-8 py-8">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')

    <script>
        AdminApi.call('/auth/me').then((res) => {
            const user = res.data;
            document.getElementById('profile-name').textContent = user.name;
            document.getElementById('profile-menu-email').textContent = user.email;
            if (user.avatar_url) {
                document.getElementById('profile-avatar').innerHTML = `<img src="${user.avatar_url}" class="h-full w-full object-cover">`;
            } else {
                document.getElementById('profile-avatar').textContent = (user.name || '?').charAt(0).toUpperCase();
            }
        });

        const menuBtn = document.getElementById('profile-menu-btn');
        const menu = document.getElementById('profile-menu');
        menuBtn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
        document.addEventListener('click', () => menu.classList.add('hidden'));

        document.getElementById('logout-btn').addEventListener('click', async () => {
            await AdminApi.call('/auth/logout', { method: 'POST' });
            AdminApi.clearToken();
            window.location.href = '/admin/login';
        });
    </script>
</body>
</html>
