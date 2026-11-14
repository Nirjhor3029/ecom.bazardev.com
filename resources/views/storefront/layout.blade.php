<!doctype html>
<html lang="bn" class="h-full bg-stone-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bazardev Ecom')</title>
    <meta name="description" content="@yield('description', 'খাঁটি মধু ও বাদাম, ঘরে বসে অর্ডার করুন — ক্যাশ অন ডেলিভারি।')">
    @vite(['resources/css/app.css'])
</head>
<body class="flex h-full flex-col font-sans text-stone-800">
    @include('storefront.partials.header')

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

    @include('storefront.partials.footer')

    @yield('scripts')
</body>
</html>
