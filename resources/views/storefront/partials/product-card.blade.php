{{-- Shared Madhuban product card — used by the products listing and the wishlist grid.
     Relies on $wishlistIds being shared globally to every storefront.* view (see the
     View::composer in AppServiceProvider). --}}
@php($range = $product->priceRange())
<div class="group rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between relative">
    <div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container mb-4">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            @if ($image = $product->primaryImageUrl())
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $image }}">
            @else
                <div class="w-full h-full flex items-center justify-center bg-surface-container text-5xl">🍯</div>
            @endif
        </a>
        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-2.5 right-2.5">
            @csrf
            <button type="submit" class="w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center transition-colors {{ in_array($product->id, $wishlistIds) ? 'text-error' : 'text-on-surface-variant hover:text-error' }}">
                <span class="material-symbols-outlined text-[18px]" style="{{ in_array($product->id, $wishlistIds) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
            </button>
        </form>
    </div>
    <div class="flex flex-col flex-1">
        <span class="font-label-sm text-label-sm text-secondary mb-1">@if ($product->defaultVariant()?->weight_grams){{ $product->defaultVariant()->weight_grams }} গ্রাম @endif</span>
        <h3 class="font-title-md text-title-md text-on-surface mb-3 group-hover:text-primary transition-colors"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name['bn'] ?? $product->name['en'] ?? '' }}</a></h3>
        <div class="mt-auto flex items-center justify-between pt-3">
            <span class="font-title-lg text-title-lg text-on-surface font-bold">৳{{ number_format($range['min'] / 100, 0) }}@if ($range['max'] > $range['min']) – ৳{{ number_format($range['max'] / 100, 0) }}@endif</span>
            <form action="{{ route('cart.items.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" value="{{ $product->defaultVariant()?->id }}">
                <input type="hidden" name="qty" value="1">
                <button type="submit" class="px-4 py-2 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors flex items-center gap-1.5 font-label-md text-label-md" {{ ($product->defaultVariant()?->inventoryItem?->available() ?? 0) <= 0 ? 'disabled' : '' }}>
                    <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                    <span>যুক্ত করুন</span>
                </button>
            </form>
        </div>
    </div>
</div>
