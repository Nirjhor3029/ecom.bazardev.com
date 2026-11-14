<!doctype html>
<html lang="bn" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Bazardev Ecom</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="h-full font-sans text-gray-900">
    @yield('content')
    @yield('scripts')
</body>
</html>
