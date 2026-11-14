@php($range = $product->priceRange())
<a href="{{ route('products.show', $product->slug) }}" class="group block overflow-hidden rounded-xl border border-amber-100 bg-white transition hover:shadow-md">
    <div class="aspect-square overflow-hidden bg-amber-50">
        @if ($image = $product->primaryImageUrl())
            <img src="{{ $image }}" alt="{{ $product->name['bn'] ?? '' }}" class="h-full w-full object-cover transition group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-4xl">🍯</div>
        @endif
    </div>
    <div class="p-3">
        <p class="truncate text-sm font-medium text-stone-900">{{ $product->name['bn'] ?? $product->name['en'] ?? '' }}</p>
        <p class="mt-1 text-sm font-semibold text-amber-700">
            ৳{{ number_format($range['min'] / 100, 0) }}
            @if ($range['max'] > $range['min'])
                – ৳{{ number_format($range['max'] / 100, 0) }}
            @endif
        </p>
    </div>
</a>
