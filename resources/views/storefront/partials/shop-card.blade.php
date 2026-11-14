{{-- Madhuban shop-listing card. Expects $product with reviews_avg / reviews_count (ProductCatalogService::search)
     and the shared $wishlistIds. Weight pills switch the variant that "কার্টে যোগ" submits. --}}
@php
    use App\Modules\Core\Support\Numerals as N;
    $variants = $product->variants->where('is_active', true)->values();
    $def = $product->defaultVariant();
    $avail = fn ($v) => (int) ($v?->inventoryItem?->available() ?? 0);
    $inStock = $variants->contains(fn ($v) => $avail($v) > 0);
    $first = ($def && $avail($def) > 0) ? $def : ($variants->first(fn ($v) => $avail($v) > 0) ?? $def);
    $disc = fn ($v) => ($v && $v->sale_price && $v->effectivePrice() < $v->price) ? (int) round((1 - $v->effectivePrice() / $v->price) * 100) : 0;
    $fmt = fn ($paisa) => '৳'.number_format($paisa / 100, 0);
    $label = fn ($v) => $v->weight_grams ? ($v->weight_grams >= 1000 ? N::bn(rtrim(rtrim(number_format($v->weight_grams / 1000, 1), '0'), '.')).' কেজি' : N::bn($v->weight_grams).' গ্রাম') : $v->sku;
    $liked = in_array($product->id, $wishlistIds ?? []);
    $c = $product->content ?? [];
    $tag = $c['origin_tag'] ?? ($product->category?->name['bn'] ?? null);
    $short = $product->short_desc['bn'] ?? null;
    $reviews = (int) ($product->reviews_count ?? 0);
    $avg = (float) ($product->reviews_avg ?? 0);
@endphp
<div class="shop-card group flex flex-col bg-surface-container-lowest rounded-xl p-2.5 sm:p-space-sm shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-1 relative {{ $inStock ? '' : 'opacity-90' }}">
<div class="card-media relative w-full aspect-square rounded-lg overflow-hidden bg-surface-container-high mb-space-sm">
<a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full" aria-label="{{ $product->name['bn'] ?? '' }}">
@if ($image = $product->primaryImageUrl())
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $inStock ? '' : 'grayscale-[30%]' }}" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $image }}" loading="lazy">
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-5xl">🍯</div>
@endif
</a>
@if (! $inStock)
<div class="absolute inset-0 bg-on-surface/40 backdrop-blur-[2px] flex items-center justify-center pointer-events-none"><span class="bg-surface-container-lowest text-on-surface px-3 py-1 rounded-full font-label-md text-label-md font-bold shadow-md">স্টক আউট</span></div>
@elseif ($reviews >= 5)
<span class="absolute top-2 left-2 bg-primary text-on-primary px-2 py-0.5 rounded-full font-label-sm text-[10px] uppercase font-bold tracking-wider shadow-sm">বেস্ট সেলার</span>
@elseif ($disc($first))
<span class="absolute top-2 left-2 bg-secondary text-on-secondary px-2 py-0.5 rounded-full font-label-sm text-[10px] uppercase font-bold tracking-wider shadow-sm">{{ N::bn($disc($first)) }}% ছাড়</span>
@endif
<form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-2 right-2">
@csrf
<button type="submit" aria-label="উইশলিস্টে যুক্ত করুন" class="w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur hover:bg-surface-container-lowest transition-colors flex items-center justify-center shadow-sm {{ $liked ? 'text-error' : 'text-on-surface hover:text-error' }}">
<span class="material-symbols-outlined text-[18px]" style="{{ $liked ? "font-variation-settings: 'FILL' 1;" : '' }}">favorite</span>
</button>
</form>
@if ($reviews > 0)
<div class="absolute bottom-2 left-2 bg-surface-container-lowest/90 backdrop-blur-sm px-2 py-0.5 rounded-full font-label-sm text-[10px] text-on-surface flex items-center gap-1 pointer-events-none">
<span class="material-symbols-outlined text-primary text-[12px]" style="font-variation-settings: 'FILL' 1;">star</span><span class="font-bold">{{ N::bn(number_format($avg, 1)) }}</span><span class="text-on-surface-variant">({{ N::bn($reviews) }})</span>
</div>
@endif
</div>
<div class="flex flex-col flex-1 justify-between gap-space-xs">
<div>
@if ($tag)<span class="font-label-sm text-[11px] sm:text-label-sm text-primary font-semibold">{{ $tag }}</span>@endif
<h3 class="font-headline-sm text-[15px] sm:text-title-lg text-on-surface group-hover:text-primary transition-colors line-clamp-1"><a href="{{ route('products.show', $product->slug) }}">{{ $product->name['bn'] ?? $product->name['en'] ?? '' }}</a></h3>
@if ($short)<p class="hidden sm:block font-body-sm text-body-sm text-on-surface-variant line-clamp-1">{{ $short }}</p>@endif
</div>
<form action="{{ route('cart.items.store') }}" method="POST" class="flex flex-col gap-1">
@csrf
<input type="hidden" name="variant_id" value="{{ $first?->id }}" class="shop-variant-input">
<input type="hidden" name="qty" value="1">
@if ($variants->count() > 1)
<div class="flex items-center flex-wrap gap-1.5 my-1">
@foreach ($variants as $v)
@php($ok = $avail($v) > 0)
<button type="button" {{ $ok ? '' : 'disabled' }} data-id="{{ $v->id }}" data-price="{{ $fmt($v->effectivePrice()) }}" data-original="{{ $fmt($v->price) }}" data-discount="{{ $disc($v) }}"
  class="shop-pill px-2.5 py-0.5 rounded-full font-label-sm text-[11px] transition-colors {{ $first && $v->id === $first->id ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface hover:bg-surface-variant' }} {{ $ok ? '' : 'opacity-40 line-through cursor-not-allowed' }}">{{ $label($v) }}</button>
@endforeach
</div>
@elseif ($first?->weight_grams)
<div class="flex items-center gap-1.5 my-1"><span class="px-2.5 py-0.5 rounded-full font-label-sm text-[11px] bg-surface-container text-on-surface">{{ $label($first) }}</span></div>
@endif
<div class="flex items-center justify-between pt-1 gap-1">
<div class="flex items-baseline gap-1.5 flex-wrap">
<span class="shop-price font-title-lg text-[16px] sm:text-title-lg text-primary font-bold">{{ $fmt($first?->effectivePrice() ?? 0) }}</span>
<span class="shop-original font-body-sm text-[12px] sm:text-body-sm text-outline line-through {{ $disc($first) ? '' : 'hidden' }}">{{ $fmt($first?->price ?? 0) }}</span>
</div>
<span class="shop-discount font-label-sm text-[10px] sm:text-[11px] text-tertiary font-bold bg-tertiary-fixed/40 px-1.5 py-0.5 rounded {{ $disc($first) ? '' : 'hidden' }}">{{ N::bn($disc($first)) }}% ছাড়</span>
</div>
@if ($inStock)
<button type="submit" class="w-full mt-2 py-2 rounded-full bg-primary hover:bg-secondary text-on-primary font-label-md text-label-md flex items-center justify-center gap-1.5 shadow-sm transition-transform hover:-translate-y-0.5">
<span class="material-symbols-outlined text-[18px]">shopping_bag</span><span class="hidden sm:inline">কার্টে যোগ করুন</span><span class="sm:hidden">+ কার্ট</span>
</button>
@else
<button type="button" disabled class="w-full mt-2 py-2 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md flex items-center justify-center gap-1.5 cursor-not-allowed"><span class="material-symbols-outlined text-[18px]">notifications_off</span>স্টক নেই</button>
@endif
</form>
</div>
</div>
