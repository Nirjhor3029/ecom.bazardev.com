<!doctype html>
<html lang="bn" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bazardev Ecom')</title>
    <meta name="description" content="@yield('description', 'খাঁটি মধু ও বাদাম, ঘরে বসে অর্ডার করুন — ক্যাশ অন ডেলিভারি।')">

    {{-- Madhuban storefront design system fonts (home/product pages) — see resources/css/app.css --theme tokens --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="flex h-full flex-col font-sans text-stone-800">
    @include('storefront.partials.header')

    <main class="flex-1 w-full pt-36 pb-28 lg:pb-0 bg-surface min-h-screen">
        @if (session('success'))
            <div class="mx-auto w-full max-w-7xl px-6 lg:px-12 pt-4"><div class="rounded-md bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('success') }}</div></div>
        @endif
        @if ($errors->any())
            <div class="mx-auto w-full max-w-7xl px-6 lg:px-12 pt-4">
                <div class="rounded-md bg-red-50 px-4 py-2 text-sm text-red-700">
                    @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('storefront.partials.footer')

    @yield('scripts')
</body>
</html>
