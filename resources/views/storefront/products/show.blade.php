@extends('storefront.layout')

@section('title', ($product->name['bn'] ?? '') . ' — মধুবন')

@section('content')
<!-- ================= DESKTOP (lg and up) — ported verbatim from product-details-code.html ================= -->
<div class="hidden lg:block">
<div class="flex flex-col w-full">
<!-- Top Secondary Breadcrumb Hierarchy Anchor -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pt-space-xs pb-space-sm">
<nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-space-xs text-body-sm font-body-sm text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="{{ route('home') }}">হোম</a>
<span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
@if ($product->category)
<a class="hover:text-primary transition-colors" href="{{ route('products.index', ['filter' => ['category' => $product->category->id]]) }}">{{ $product->category->name['bn'] ?? $product->category->name['en'] ?? '' }}</a>
<span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
@endif
<span class="text-on-surface font-label-md text-label-md bg-secondary-fixed/50 px-2 py-0.5 rounded-full">{{ $product->name['bn'] ?? $product->name['en'] ?? '' }}</span>
</nav>
</section>
@include('storefront.products.partials.hero')
@include('storefront.products.partials.highlights')
@include('storefront.products.partials.tabs')
@include('storefront.products.partials.story')
@include('storefront.products.partials.reviews')
<!-- 6. Cross-sell Festive Gift Pack Banner -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-md">
<div class="relative rounded-2xl overflow-hidden shadow-md bg-secondary text-on-secondary">
<div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-overlay" alt="Luxurious holiday celebration arrangement of pure raw honey, organic premium cashews, almonds, and dried figs inside an engraved wooden artisan gift box with golden silk ribbon." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC52ovsg6_AYHXmmX3C9wGRL5yTqiLtWB-1s6C9K6Xlc518yLYUKG8j2X5iMmfPGcKKUOhw0iMRB6hI_Ff1Se9OqN_cdrtpVy0E9opOMSIy2ngATmT_RVHUq6qTohFw-ixL0ccUiW85nI4M2dUJ5CzYeUOy0qy2CcU2tLpr-tZe6VZ4GmIscjA9hFVtYqYDpXkO6VosQ4fBh-aotgu-Y9KJKqauaBRSSwNaLYh5Z2a4')"></div>
<div class="relative z-10 p-space-lg lg:p-space-xl flex flex-col md:flex-row items-center justify-between gap-space-lg">
<div class="flex flex-col gap-2 max-w-xl text-center md:text-left">
<span class="inline-flex items-center gap-1.5 self-center md:self-start bg-primary-container text-on-primary-container px-3 py-1 rounded-full font-label-sm text-label-sm font-bold">
<span class="material-symbols-outlined text-[14px]">featured_seasonal_and_gifts</span>
            উৎসব ও উপহার স্পেশাল
          </span>
<h3 class="font-headline-md text-headline-md font-semibold text-on-secondary">প্রিয়জনের সুস্বাস্থ্যের জন্য প্রাকৃতিক উপহার বক্স</h3>
<p class="font-body-md text-body-md text-secondary-fixed-dim">
            সুন্দরবনের খলিশা মধু, প্রিমিয়াম কাজুবাদাম, কাঠবাদাম ও খোরমা খেজুর সমন্বয়ে সাজানো আমাদের রয়্যাল গিফট বক্স। যেকোনো পারিবারিক অনুষ্ঠান বা কর্পোরেট উপহারের সেরা সঙ্গী।
          </p>
</div>
<div class="flex items-center gap-space-sm shrink-0">
<a class="py-3 px-space-lg rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md font-bold shadow-md hover:bg-surface-bright transition-all hover:scale-105" data-path="combos-gift-boxes" href="#">
            গিফট কালেকশন দেখুন
          </a>
</div>
</div>
</div>
</section>
<!-- 7. Related & Recommended Products Grid -->
@if ($related->isNotEmpty())
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-xl">
<div class="flex items-center justify-between mb-space-md">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">সম্পর্কিত পণ্য</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-0.5">আপনার আরও ভালো লাগতে পারে</h2>
</div>
@if ($product->category)
<a class="font-label-md text-label-md text-primary hover:underline flex items-center gap-1" href="{{ route('products.index', ['filter' => ['category' => $product->category->id]]) }}">
সব দেখুন <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</a>
@endif
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
@foreach ($related as $relatedProduct)
@php($relRange = $relatedProduct->priceRange())
<div class="bg-surface-container-lowest rounded-xl p-space-sm shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
<a href="{{ route('products.show', $relatedProduct->slug) }}" class="relative aspect-square rounded-lg overflow-hidden bg-surface-container block">
@if ($image = $relatedProduct->primaryImageUrl())
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $relatedProduct->name['bn'] ?? '' }}" src="{{ $image }}"/>
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-5xl">🍯</div>
@endif
</a>
<div class="flex flex-col gap-1 mt-space-sm">
<h3 class="font-title-md text-title-md text-on-surface font-semibold group-hover:text-primary transition-colors"><a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name['bn'] ?? $relatedProduct->name['en'] ?? '' }}</a></h3>
<div class="flex items-baseline gap-2 mt-1">
<span class="font-title-lg text-title-lg font-bold text-primary">৳{{ number_format($relRange['min'] / 100, 0) }}@if ($relRange['max'] > $relRange['min']) – ৳{{ number_format($relRange['max'] / 100, 0) }}@endif</span>
</div>
</div>
<form action="{{ route('cart.items.store') }}" method="POST">
@csrf
<input type="hidden" name="variant_id" value="{{ $relatedProduct->defaultVariant()?->id }}">
<input type="hidden" name="qty" value="1">
<button type="submit" class="w-full mt-space-sm py-2 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-1.5" {{ ($relatedProduct->defaultVariant()?->inventoryItem?->available() ?? 0) <= 0 ? 'disabled' : '' }}>
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
কার্টে যোগ করুন
</button>
</form>
</div>
@endforeach
</div>
</section>
@endif
@include('storefront.products.partials.faq')
<script>
    // Tab Switching Logic
    function switchTab(buttonElement, targetTabId) {
      const headers = document.querySelectorAll('#pdp-tab-headers .tab-btn');
      headers.forEach(btn => {
        btn.classList.remove('bg-primary-container', 'text-on-primary-container', 'font-bold', 'active-tab');
        btn.classList.add('bg-surface-container', 'text-on-surface-variant');
      });
      buttonElement.classList.add('bg-primary-container', 'text-on-primary-container', 'font-bold', 'active-tab');
      buttonElement.classList.remove('bg-surface-container', 'text-on-surface-variant');

      const panes = document.querySelectorAll('.tab-pane');
      panes.forEach(pane => pane.classList.add('hidden'));

      const targetPane = document.getElementById(targetTabId);
      if (targetPane) {
        targetPane.classList.remove('hidden');
      }
    }

    // FAQ Accordion
    function toggleFaq(btn) {
      const content = btn.nextElementSibling;
      const icon = btn.querySelector('.material-symbols-outlined');
      const isExpanded = !content.classList.contains('hidden');

      if (isExpanded) {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
      } else {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
      }
    }
  </script>
</div>
</div>

<!-- ================= MOBILE (below lg) — ported verbatim from product-details-mobile-code.html -->
<!-- Mobile PDP intentionally uses its own compact back-button app-bar (no shared
     banner/search/nav or bottom tab bar) — that is how the source mockup built it,
     a deliberate drill-down pattern distinct from the home page chrome, not an omission. -->
<div class="lg:hidden">
<div class="flex flex-col w-full pb-28">
<!-- 1. Breadcrumb -->
<nav aria-label="Breadcrumb" class="px-gutter-mobile py-space-sm">
<ol class="flex items-center gap-1.5 font-label-md text-label-md text-on-surface-variant flex-wrap">
<li>
<a class="hover:text-primary transition-colors" href="{{ route('home') }}">হোম</a>
</li>
@if ($product->category)
<li class="flex items-center text-outline-variant">
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
</li>
<li>
<a class="hover:text-primary transition-colors" href="{{ route('products.index', ['filter' => ['category' => $product->category->id]]) }}">{{ $product->category->name['bn'] ?? $product->category->name['en'] ?? '' }}</a>
</li>
@endif
<li class="flex items-center text-outline-variant">
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
</li>
<li aria-current="page" class="text-primary font-semibold truncate max-w-[140px]">
{{ $product->name['bn'] ?? $product->name['en'] ?? '' }}
</li>
</ol>
</nav>
<!-- 2. Product Media Gallery -->
<section class="relative w-full px-gutter-mobile pt-space-xs">
<div class="relative w-full aspect-[4/4] rounded-xl overflow-hidden bg-surface-container shadow-sm">
<div class="w-full h-full flex overflow-x-auto snap-x snap-mandatory scroll-smooth" id="gallery-slider">
@forelse ($product->galleryImages() as $media)
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $media->media->url() }}"/>
</div>
@empty
<div class="w-full h-full flex-shrink-0 snap-center relative flex items-center justify-center bg-surface-container text-8xl">🍯</div>
@endforelse
</div>
@if (! empty(($product->content ?? [])['badge']))
<span class="absolute top-3 left-3 inline-flex items-center gap-1 bg-primary-container text-on-primary-container font-label-sm text-label-sm px-2.5 py-1 rounded-full shadow-sm"><span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">eco</span>{{ $product->content['badge'] }}</span>
@endif
@if ($product->galleryImages()->count() > 1)
<span class="absolute bottom-3 left-3 inline-flex items-center gap-1 bg-inverse-surface/70 text-inverse-on-surface font-label-sm text-label-sm px-2 py-0.5 rounded-full backdrop-blur-sm"><span class="material-symbols-outlined text-[13px]">photo_library</span><span id="slide-counter">১</span>/{{ \App\Modules\Core\Support\Numerals::bn($product->galleryImages()->count()) }}</span>
@endif
<!-- Wishlist Heart Button -->
<form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3">
@csrf
<button aria-label="উইশলিস্টে যোগ করুন" type="submit" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md flex items-center justify-center shadow-md active:scale-90 transition-transform {{ in_array($product->id, $wishlistIds) ? 'text-error' : 'text-on-surface' }}">
<span class="material-symbols-outlined text-[20px] transition-colors" style="{{ in_array($product->id, $wishlistIds) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
</button>
</form>
</div>
@if ($product->galleryImages()->count() > 1)
<!-- Gallery Thumbnail Strip -->
<div class="flex items-center gap-2 mt-2.5 overflow-x-auto pb-1">
@foreach ($product->galleryImages() as $media)
<button class="thumb-btn flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-surface-container-highest {{ $loop->first ? '' : 'opacity-70' }} transition-all" type="button" onclick="goToSlide({{ $loop->index }})">
<img class="w-full h-full object-cover" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $media->media->url() }}"/>
</button>
@endforeach
</div>
@endif
</section>
<!-- 3. Title & Trust Metadata -->
<section class="px-gutter-mobile pt-space-md">
@php($mobileDefaultVariant = $product->defaultVariant())
@php($mobileInStock = ($mobileDefaultVariant?->inventoryItem?->available() ?? 0) > 0)
<div class="flex items-center justify-between gap-2 mb-1.5">
<div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">
<span class="material-symbols-outlined text-[13px]">local_florist</span>
<span>মধুবন</span>
</div>
<span class="inline-flex items-center gap-1 font-label-sm text-label-sm px-2.5 py-0.5 rounded-full {{ $mobileInStock ? 'bg-[#e8f5e9] text-[#1b5e20]' : 'bg-surface-dim text-on-surface-variant' }} font-semibold">
<span class="w-1.5 h-1.5 rounded-full {{ $mobileInStock ? 'bg-[#2e7d32] animate-pulse' : 'bg-outline' }}"></span>
{{ $mobileInStock ? 'ইন স্টক' : 'স্টক শেষ' }}
</span>
</div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight tracking-tight">
{{ $product->name['bn'] ?? '' }}
</h2>
<div class="flex items-center gap-2 mt-2 flex-wrap">
@if ($rating['count'] > 0)<a href="#customer-reviews" class="flex items-center gap-1 bg-surface-container-high px-2 py-0.5 rounded-lg font-label-sm text-label-sm text-on-surface"><span class="material-symbols-outlined text-[14px] text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span><strong>{{ \App\Modules\Core\Support\Numerals::bn($rating['avg']) }}</strong><span class="text-on-surface-variant">({{ \App\Modules\Core\Support\Numerals::bn($rating['count']) }}টি রিভিউ)</span></a>@else<a href="#customer-reviews" class="flex items-center gap-1 bg-surface-container-high px-2 py-0.5 rounded-lg font-label-sm text-label-sm text-on-surface-variant">এখনো কোনো রিভিউ নেই</a>@endif
</div>
</section>
<!-- 4. Dynamic Price Display -->
<section class="px-gutter-mobile pt-space-md">
<div class="p-space-md rounded-xl bg-surface-container-low shadow-sm flex items-center justify-between">
<div class="flex items-baseline gap-2.5">
<span class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary" id="current-price">৳{{ number_format(($mobileDefaultVariant?->effectivePrice() ?? 0) / 100, 0) }}</span>
@php($mobileDisc = ($mobileDefaultVariant && $mobileDefaultVariant->sale_price && $mobileDefaultVariant->effectivePrice() < $mobileDefaultVariant->price) ? (int) round((1 - $mobileDefaultVariant->effectivePrice() / $mobileDefaultVariant->price) * 100) : 0)
<span class="font-title-md text-title-md line-through text-outline {{ $mobileDisc ? '' : 'hidden' }}" id="regular-price">৳{{ number_format(($mobileDefaultVariant?->price ?? 0) / 100, 0) }}</span>
<span class="bg-error-container text-on-error-container font-label-sm text-label-sm px-2 py-0.5 rounded-full font-bold {{ $mobileDisc ? '' : 'hidden' }}" id="mobile-discount">{{ \App\Modules\Core\Support\Numerals::bn($mobileDisc) }}% ছাড়</span>
</div>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">ভ্যাট অন্তর্ভুক্ত</span>
</div>
</section>
<form id="pdp-add-to-cart-form" action="{{ route('cart.items.store') }}" method="POST">
@csrf
@php($mobileActiveVariants = $product->variants->where('is_active', true))
@if ($mobileActiveVariants->count() > 1)
<!-- 5. Weight / Variant Selector -->
<section class="px-gutter-mobile pt-space-md">
<div class="flex items-center justify-between mb-space-sm">
<label class="font-title-md text-title-md text-on-surface font-semibold">ওজন নির্বাচন করুন</label>
</div>
<div class="grid grid-cols-2 gap-2.5">
@foreach ($mobileActiveVariants as $variant)
@php($mobileAvailable = ($variant->inventoryItem?->available() ?? 0) > 0)
<label class="variant-pill min-h-[48px] px-3 py-2.5 rounded-xl {{ $mobileAvailable ? 'bg-surface-container-lowest text-on-surface shadow-sm cursor-pointer' : 'bg-surface-dim/60 text-outline cursor-not-allowed opacity-70' }} text-left flex items-center justify-between has-[:checked]:bg-primary has-[:checked]:text-on-primary has-[:checked]:shadow-md transition-all">
<input type="radio" name="variant_id" value="{{ $variant->id }}" class="hidden pdp-variant-radio-mobile" data-price="{{ $variant->effectivePrice() }}" data-original="{{ $variant->price }}" data-discount="{{ ($variant->sale_price && $variant->effectivePrice() < $variant->price) ? (int) round((1 - $variant->effectivePrice() / $variant->price) * 100) : 0 }}" data-label="{{ $variant->weight_grams ? ($variant->weight_grams >= 1000 ? \App\Modules\Core\Support\Numerals::bn(rtrim(rtrim(number_format($variant->weight_grams / 1000, 1), '0'), '.')).' কেজি' : \App\Modules\Core\Support\Numerals::bn($variant->weight_grams).' গ্রাম') : $variant->sku }}" {{ $variant->id === $mobileDefaultVariant?->id ? 'checked' : '' }} {{ $mobileAvailable ? '' : 'disabled' }}>
<div class="flex flex-col">
<span class="font-label-md text-label-md font-semibold">{{ $variant->weight_grams ? ($variant->weight_grams >= 1000 ? \App\Modules\Core\Support\Numerals::bn(rtrim(rtrim(number_format($variant->weight_grams / 1000, 1), '0'), '.')).' কেজি' : \App\Modules\Core\Support\Numerals::bn($variant->weight_grams).' গ্রাম') : ($variant->sku ?? '') }}@if ($variant->options['tag'] ?? null) <span class="font-normal text-[11px] opacity-80">({{ $variant->options['tag'] }})</span>@endif</span>
<span class="font-body-sm text-body-sm">{{ $mobileAvailable ? '৳'.number_format($variant->effectivePrice() / 100, 0) : 'স্টক আউট' }}</span>
</div>
</label>
@endforeach
</div>
</section>
@else
<input type="hidden" name="variant_id" value="{{ $mobileDefaultVariant?->id }}">
@endif
<!-- 6. Quantity Selector -->
<section class="px-gutter-mobile pt-space-md">
<div class="flex items-center justify-between p-space-sm bg-surface-container-low rounded-xl">
<div class="flex flex-col pl-1">
<span class="font-title-md text-title-md text-on-surface">পরিমাণ</span>
<span class="font-label-sm text-label-sm text-on-surface-variant">সর্বোচ্চ ১০টি আইটেম</span>
</div>
<div class="flex items-center bg-surface-container-lowest rounded-full p-1 shadow-sm">
<button aria-label="পরিমাণ কমান" type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface active:scale-90 hover:bg-surface-container transition-colors" onclick="let i=document.getElementById('pdp-qty-mobile');i.value=Math.max(1,parseInt(i.value)-1)">
<span class="material-symbols-outlined text-[20px]">remove</span>
</button>
<input class="w-10 text-center bg-transparent font-title-lg text-title-lg font-bold text-on-surface outline-none" id="pdp-qty-mobile" name="qty" type="number" min="1" max="10" value="1"/>
<button aria-label="পরিমাণ বাড়ান" type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface active:scale-90 hover:bg-surface-container transition-colors" onclick="let i=document.getElementById('pdp-qty-mobile');i.value=Math.min(10,parseInt(i.value)+1)">
<span class="material-symbols-outlined text-[20px]">add</span>
</button>
</div>
</div>
</section>
</form>
@include('storefront.products.partials.mobile-info')
<!-- 10. Related Products Grid -->
@if ($related->isNotEmpty())
<section class="px-gutter-mobile pt-space-lg">
<div class="flex items-center justify-between mb-space-sm">
<div>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">সম্পর্কিত পণ্য</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">একই বিভাগ থেকে সংগৃহীত</p>
</div>
</div>
<div class="grid grid-cols-2 gap-3">
@foreach ($related as $relatedProduct)
@php($mobileRelRange = $relatedProduct->priceRange())
<div class="flex flex-col bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm p-2.5 group">
<a href="{{ route('products.show', $relatedProduct->slug) }}" class="relative w-full aspect-square rounded-lg overflow-hidden bg-surface-container mb-2 block">
@if ($image = $relatedProduct->primaryImageUrl())
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $relatedProduct->name['bn'] ?? '' }}" src="{{ $image }}"/>
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-4xl">🍯</div>
@endif
</a>
<h4 class="font-title-md text-title-md text-on-surface font-semibold truncate mt-0.5"><a href="{{ route('products.show', $relatedProduct->slug) }}">{{ $relatedProduct->name['bn'] ?? $relatedProduct->name['en'] ?? '' }}</a></h4>
<div class="flex items-center gap-1.5 mt-1">
<span class="font-title-md text-title-md text-primary font-bold">৳{{ number_format($mobileRelRange['min'] / 100, 0) }}@if ($mobileRelRange['max'] > $mobileRelRange['min']) – ৳{{ number_format($mobileRelRange['max'] / 100, 0) }}@endif</span>
</div>
<form action="{{ route('cart.items.store') }}" method="POST">
@csrf
<input type="hidden" name="variant_id" value="{{ $relatedProduct->defaultVariant()?->id }}">
<input type="hidden" name="qty" value="1">
<button type="submit" class="mt-2 w-full min-h-[38px] rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-semibold flex items-center justify-center gap-1 hover:bg-primary-container hover:text-on-primary-container active:scale-95 transition-all" {{ ($relatedProduct->defaultVariant()?->inventoryItem?->available() ?? 0) <= 0 ? 'disabled' : '' }}>
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
যোগ করুন
</button>
</form>
</div>
@endforeach
</div>
</section>
@endif
<!-- 11. FAQ (data: content.faq) -->
@php($mobileFaq = ($product->content ?? [])['faq'] ?? [])
@if ($mobileFaq)
<section class="px-gutter-mobile pt-space-lg mb-4">
<div class="flex items-center gap-2 mb-space-sm">
<span class="material-symbols-outlined text-primary text-[22px]">quiz</span>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">সাধারণ জিজ্ঞাসা (FAQ)</h3>
</div>
<div class="flex flex-col gap-2">
@foreach ($mobileFaq as $item)
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden">
<button class="w-full min-h-[48px] py-3 px-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<span class="font-label-md text-label-md text-on-surface font-semibold pr-2">{{ $item['q'] ?? $item[0] }}</span>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-3.5 pb-3 font-body-sm text-body-sm text-on-surface-variant">{{ $item['a'] ?? $item[1] }}</div>
</div>
@endforeach
</div>
</section>
@endif
<!-- 12. STICKY BOTTOM PURCHASE BAR (sits just above the shared mobile bottom-nav) -->
<aside aria-label="Quick Purchase Bar" class="fixed bottom-16 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl shadow-[0_-4px_24px_rgba(56,39,25,0.08)]">
<div class="max-w-[430px] mx-auto px-gutter-mobile py-2.5 flex items-center justify-between gap-3">
<!-- Price & Selected Weight Information -->
<div class="flex flex-col min-w-0 pr-1">
<span class="font-label-sm text-[11px] text-on-surface-variant leading-none">মোট মূল্য</span>
<div class="flex items-baseline gap-1 mt-0.5">
<span class="font-title-lg text-title-lg text-primary font-bold leading-tight" id="bar-price">৳{{ number_format(($mobileDefaultVariant?->effectivePrice() ?? 0) / 100, 0) }}</span>
</div>
<span class="font-label-sm text-[11px] text-outline truncate font-medium" id="bar-variant">@if ($mobileDefaultVariant?->weight_grams){{ $mobileDefaultVariant->weight_grams >= 1000 ? \App\Modules\Core\Support\Numerals::bn(rtrim(rtrim(number_format($mobileDefaultVariant->weight_grams / 1000, 1), '0'), '.')).' কেজি' : \App\Modules\Core\Support\Numerals::bn($mobileDefaultVariant->weight_grams).' গ্রাম' }}@endif</span>
</div>
<!-- Action Buttons -->
<div class="flex items-center gap-2 flex-1 justify-end">
<!-- Add to Cart (Secondary Full Pill) -->
<button form="pdp-add-to-cart-form" type="submit" class="min-h-[46px] px-3.5 py-2 rounded-full bg-surface-container-highest text-on-surface hover:text-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition-all" {{ $mobileInStock ? '' : 'disabled' }}>
<span class="material-symbols-outlined text-[19px]">add_shopping_cart</span>
<span class="whitespace-nowrap">কার্টে যোগ</span>
</button>
<!-- Buy Now: add to cart, then straight to checkout -->
<button form="pdp-add-to-cart-form" type="submit" name="buy_now" value="1" class="min-h-[46px] px-4 py-2 rounded-full bg-gradient-to-r from-primary-container to-primary text-on-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1 shadow-md hover:shadow-lg active:scale-95 transition-all flex-shrink-0" {{ $mobileInStock ? '' : 'disabled' }}>
<span class="material-symbols-outlined text-[18px]">bolt</span>
<span class="whitespace-nowrap">এখনই কিনুন</span>
</button>
</div>
</div>
</aside>
</div>
<script>
  // Bengali Number Conversion
  const bnDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
  function toBengaliNum(num) {
    return num.toString().replace(/[0-9]/g, d => bnDigits[d]);
  }

  // Update the mobile price display + sticky bar whenever the real variant radio or qty input changes
  function updateMobilePdpPrice() {
    const checked = document.querySelector('.pdp-variant-radio-mobile:checked');
    const qtyInput = document.getElementById('pdp-qty-mobile');
    if (!checked || !qtyInput) return;
    const qty = parseInt(qtyInput.value) || 1;
    const unitPrice = parseInt(checked.dataset.price, 10) || 0;
    const formatted = '৳' + toBengaliNum(Math.round(unitPrice * qty / 100));
    const currentPriceEl = document.getElementById('current-price');
    const barPriceEl = document.getElementById('bar-price');
    const orig = document.getElementById('regular-price'), disc = document.getElementById('mobile-discount'), d = parseInt(checked.dataset.discount || '0', 10);
    if (orig && disc) {
      orig.innerText = '৳' + Math.round((parseInt(checked.dataset.original, 10) || 0) * qty / 100);
      disc.innerText = toBengaliNum(d) + '% ছাড়';
      orig.classList.toggle('hidden', !d); disc.classList.toggle('hidden', !d);
    }
    const barVariant = document.getElementById('bar-variant');
    if (barVariant && checked.dataset.label) barVariant.innerText = checked.dataset.label;
    if (currentPriceEl) currentPriceEl.innerText = formatted;
    if (barPriceEl) barPriceEl.innerText = formatted;
  }
  document.querySelectorAll('.pdp-variant-radio-mobile').forEach(r => r.addEventListener('change', updateMobilePdpPrice));
  const mobileQtyInput = document.getElementById('pdp-qty-mobile');
  if (mobileQtyInput) {
    mobileQtyInput.addEventListener('input', updateMobilePdpPrice);
    mobileQtyInput.addEventListener('change', updateMobilePdpPrice);
  }

  // Gallery Navigation
  const slider = document.getElementById('gallery-slider');
  const thumbs = document.querySelectorAll('.thumb-btn');

  function goToSlide(index) {
    if (!slider) return;
    const width = slider.clientWidth;
    slider.scrollTo({ left: width * index, behavior: 'smooth' });
    updateSliderUI(index);
  }

  if (slider) slider.addEventListener('scroll', () => {
    const i = Math.round(slider.scrollLeft / (slider.clientWidth || 1));
    const c = document.getElementById('slide-counter'); if (c) c.innerText = toBengaliNum(i + 1);
    updateSliderUI(i);
  });

  function updateSliderUI(index) {
    thumbs.forEach((th, i) => {
      if (i === index) {
        th.classList.remove('opacity-70');
        th.classList.add('shadow-primary-container/20');
      } else {
        th.classList.add('opacity-70');
        th.classList.remove('shadow-primary-container/20');
      }
    });
  }

  if (slider) {
    slider.addEventListener('scroll', () => {
      const idx = Math.round(slider.scrollLeft / slider.clientWidth);
      updateSliderUI(idx);
    }, { passive: true });
  }

  // Accordion Toggle
  function toggleAccordion(btn) {
    const content = btn.nextElementSibling;
    const arrow = btn.querySelector('.material-symbols-outlined:last-child');
    if (content.classList.contains('hidden')) {
      content.classList.remove('hidden');
      arrow.classList.add('rotate-180');
    } else {
      content.classList.add('hidden');
      arrow.classList.remove('rotate-180');
    }
  }
</script></div>
@endsection
