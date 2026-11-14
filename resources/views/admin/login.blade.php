<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in — Bazardev Ecom</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="h-full bg-paper font-sans text-ink antialiased">
    <div class="flex h-full">
        <!-- Brand panel: the one bold moment, same walnut as the admin sidebar. -->
        <div class="relative hidden w-[42%] flex-col justify-between overflow-hidden bg-walnut px-12 py-14 text-stone-200 lg:flex">
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold text-base">🍯</span>
                <span class="text-base font-semibold text-white">Bazardev Ecom</span>
            </div>

            <div>
                <p class="text-2xl font-medium leading-snug text-white">
                    সুন্দরবনের খাঁটি মধু,<br>প্রতিটা অর্ডার হাতে হাতে।
                </p>
                <p class="mt-4 max-w-xs text-sm text-stone-400">
                    Catalog, orders আর ডেলিভারি — এক জায়গা থেকে চালান।
                </p>
            </div>

            <p class="text-xs text-stone-500">Staff access only</p>
        </div>

        <!-- Form -->
        <div class="flex flex-1 items-center justify-center px-6 py-16">
            <div class="w-full max-w-sm">
                <div class="mb-8 lg:hidden">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gold text-base">🍯</span>
                </div>

                <h1 class="text-xl font-semibold text-ink">Log in</h1>
                <p class="mt-1 text-sm text-stone-500">Enter your admin credentials to continue.</p>

                <form id="login-form" class="mt-7 space-y-4">
                    <div>
                        <label for="email" class="field-label">Email</label>
                        <input id="email" name="email" type="email" required autofocus class="field-input">
                    </div>
                    <div>
                        <label for="password" class="field-label">Password</label>
                        <input id="password" name="password" type="password" required class="field-input">
                    </div>

                    <p id="error" class="hidden rounded-md bg-status-rust-soft px-3 py-2 text-sm text-status-rust"></p>

                    <button type="submit" class="btn-primary w-full">Log in</button>
                </form>
            </div>
        </div>
    </div>

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
</body>
</html>
