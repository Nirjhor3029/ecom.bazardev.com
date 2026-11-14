<header class="border-b border-amber-100 bg-cream-50" style="background-color:#fdf8f0">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="text-xl">🍯</span>
            <span class="font-serif text-lg font-semibold tracking-tight text-amber-900">Bazardev Ecom</span>
        </a>

        <form action="{{ route('products.index') }}" class="hidden flex-1 max-w-md sm:block">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="খুঁজুন — মধু, বাদাম…"
                class="w-full rounded-full border border-amber-200 bg-white px-4 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
        </form>

        <div class="flex items-center gap-4 text-sm font-medium text-amber-900">
            <a href="{{ route('products.index') }}" class="hover:text-amber-600">পণ্য</a>
            <a href="{{ route('orders.track') }}" class="hover:text-amber-600">অর্ডার ট্র্যাক</a>
            <a href="{{ route('cart.show') }}" class="relative flex items-center gap-1 hover:text-amber-600">
                🛒
                @if (($cartItemCount ?? 0) > 0)
                    <span class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-amber-600 text-xs text-white">{{ $cartItemCount }}</span>
                @endif
            </a>
        </div>
    </div>
</header>
