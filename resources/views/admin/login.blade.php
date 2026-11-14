@extends('admin.layout')

@section('title', 'Login')

@section('content')
<div class="flex min-h-full items-center justify-center px-4 py-16">
    <div class="w-full max-w-sm">
        <h1 class="mb-1 text-2xl font-semibold tracking-tight text-gray-900">Admin Login</h1>
        <p class="mb-8 text-sm text-gray-500">Bazardev Ecom — staff access only</p>

        <form id="login-form" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" required autofocus
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
            </div>

            <p id="error" class="hidden rounded-md bg-red-50 px-3 py-2 text-sm text-red-700"></p>

            <button type="submit"
                class="w-full rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                Sign in
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const errorEl = document.getElementById('error');
        errorEl.classList.add('hidden');

        try {
            const res = await AdminApi.call('/auth/login', {
                method: 'POST',
                body: {
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                },
            });
            AdminApi.setToken(res.data.token);
            window.location.href = '/admin';
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        }
    });

    if (AdminApi.isLoggedIn()) window.location.href = '/admin';
</script>
@endsection
