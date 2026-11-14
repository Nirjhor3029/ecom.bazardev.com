<!doctype html>
<html lang="bn" class="h-full bg-surface">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'মধুবন — Madhuban Artisanal Provisions')</title>
    <meta name="description" content="@yield('description', 'খাঁটি মধু ও বাদাম, ঘরে বসে অর্ডার করুন — ক্যাশ অন ডেলিভারি।')">

    {{-- Madhuban storefront design system fonts (home/product pages) — see resources/css/app.css --theme tokens --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
{{-- Pages can `@section('minimal', '1')` for the distraction-free checkout chrome (no nav/search/bottom bar). --}}
@php $minimal = View::hasSection('minimal'); @endphp
<body class="flex min-h-full flex-col font-body-md text-on-surface bg-surface">
    @if ($minimal)
        @include('storefront.partials.checkout-header')
    @else
        @include('storefront.partials.header')
    @endif

    <main class="flex-1 w-full {{ $minimal ? 'pt-20' : 'pt-36 pb-28 lg:pb-0' }} bg-surface min-h-screen">
        @if (session('success'))
            <div class="mx-auto w-full max-w-7xl px-6 lg:px-12 pt-4"><div class="rounded-md bg-green-50 px-4 py-2 text-sm text-green-700">{{ session('success') }}</div></div>
        @endif
        @if ($errors->any() && ! $minimal)
            <div class="mx-auto w-full max-w-7xl px-6 lg:px-12 pt-4">
                <div class="rounded-md bg-red-50 px-4 py-2 text-sm text-red-700">
                    @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @if ($minimal)
        @include('storefront.partials.checkout-footer')
    @else
        @include('storefront.partials.footer')
        @include('storefront.partials.search-suggest')
    @endif

    @yield('scripts')
</body>
</html>
