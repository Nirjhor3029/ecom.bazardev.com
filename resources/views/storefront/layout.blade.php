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
    {{-- home.blade.php / products/show.blade.php bring their own full-chrome header+footer
         (ported verbatim per page from the reference mockups) and set @section('bare-chrome', 'yes')
         to skip the older generic storefront header/footer below. Cart/checkout/orders pages are
         untouched and keep using the original chrome. --}}
    @if (trim($__env->yieldContent('bare-chrome')) !== 'yes')
        @include('storefront.partials.header')
    @endif

    @if (session('success'))
        <div class="mx-auto mt-4 w-full max-w-6xl px-4"><div class="rounded-md bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('success') }}</div></div>
    @endif
    @if ($errors->any())
        <div class="mx-auto mt-4 w-full max-w-6xl px-4">
            <div class="rounded-md bg-red-50 px-4 py-2 text-sm text-red-700">
                @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @if (trim($__env->yieldContent('bare-chrome')) !== 'yes')
        @include('storefront.partials.footer')
    @endif

    @yield('scripts')
</body>
</html>
