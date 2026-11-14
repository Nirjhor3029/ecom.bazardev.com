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
<!-- 1. Main PDP Hero Two-Column Layout -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pb-space-xl">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg lg:gap-space-xl items-start">
<!-- Left Column: Product Visual Media Hub (7 Cols on desktop) -->
<div class="lg:col-span-7 flex flex-col gap-space-md">
<!-- Main Media Frame -->
<div class="relative bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm aspect-[4/3] flex items-center justify-center group">
@if ($image = $product->primaryImageUrl())
<img class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" id="main-product-display" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $image }}">
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-8xl">🍯</div>
@endif
<div class="absolute top-4 right-4 z-10 flex items-center gap-2">
<form action="{{ route('wishlist.toggle', $product) }}" method="POST">
@csrf
<button aria-label="উইশলিস্টে যুক্ত করুন" type="submit" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md flex items-center justify-center shadow-md hover:bg-surface-container transition-all hover:scale-110 active:scale-95 {{ in_array($product->id, $wishlistIds) ? 'text-error' : 'text-on-surface' }}">
<span class="material-symbols-outlined text-[20px]" style="{{ in_array($product->id, $wishlistIds) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
</button>
</form>
</div>
</div>
<!-- Thumbnails Gallery -->
@if ($product->media->count() > 1)
<div class="grid grid-cols-6 gap-space-xs sm:gap-space-sm" id="pdp-thumbnails">
@foreach ($product->media as $media)
@continue(! $media->media)
<button type="button" aria-label="থাম্বনেইল দেখুন" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-high transition-all shadow-sm opacity-70 hover:opacity-100 {{ $loop->first ? 'active-thumb opacity-100' : '' }}" onclick="document.getElementById('main-product-display').src = this.querySelector('img').src">
<img class="w-full h-full object-cover" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $media->media->url() }}"/>
</button>
@endforeach
</div>
@endif
</div>
<!-- Right Column: Product Purchase Control Center (5 Cols on desktop) -->
<div class="lg:col-span-5 flex flex-col gap-space-md">
<!-- Category Pill & SKU -->
<div class="flex items-center justify-between">
@if ($product->category)
<span class="inline-flex items-center gap-1 font-label-md text-label-md text-primary bg-primary-fixed px-3 py-0.5 rounded-full font-bold">
<span class="material-symbols-outlined text-[14px]">stars</span>
{{ $product->category->name['bn'] ?? $product->category->name['en'] ?? '' }}
</span>
@endif
@if ($sku = $product->defaultVariant()?->sku)
<span class="font-label-sm text-label-sm text-on-surface-variant tracking-wider">SKU: <strong class="text-on-surface font-semibold">{{ $sku }}</strong></span>
@endif
</div>
<!-- Product Title -->
<div class="flex flex-col gap-1">
<h1 class="font-headline-lg text-headline-lg text-on-surface font-semibold tracking-tight">{{ $product->name['bn'] ?? '' }}</h1>
@if ($product->name['en'] ?? null)
<p class="font-title-lg text-title-lg text-secondary italic font-headline-sm">{{ $product->name['en'] }}</p>
@endif
</div>
<!-- Reviews & Stock Status -->
<div class="flex flex-wrap items-center justify-between gap-space-xs py-space-xs border-y-0">
<a class="font-label-sm text-label-sm text-primary hover:underline underline-offset-4" href="#customer-reviews">এখনো কোনো রিভিউ নেই — প্রথম রিভিউ দিন</a>
@php($defaultVariant = $product->defaultVariant())
@php($inStock = ($defaultVariant?->inventoryItem?->available() ?? 0) > 0)
<div class="inline-flex items-center gap-1.5 {{ $inStock ? 'bg-secondary-fixed/70 text-on-secondary-fixed' : 'bg-surface-dim text-on-surface-variant' }} px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold">
<span class="w-2 h-2 rounded-full {{ $inStock ? 'bg-primary animate-pulse' : 'bg-outline' }}"></span>
{{ $inStock ? 'ইন স্টক' : 'স্টক শেষ' }}
</div>
</div>
<!-- Price Display Box -->
<div class="bg-surface-container-low p-space-md rounded-xl flex items-baseline justify-between shadow-sm">
<div class="flex items-baseline gap-space-sm">
<span class="font-display-hero text-headline-lg text-primary font-bold" id="display-price">৳{{ number_format(($defaultVariant?->effectivePrice() ?? 0) / 100, 0) }}</span>
@if ($defaultVariant && $defaultVariant->sale_price)
<span class="font-title-lg text-title-lg text-on-surface-variant line-through" id="display-original-price">৳{{ number_format($defaultVariant->price / 100, 0) }}</span>
@endif
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">ভ্যাট অন্তর্ভুক্ত</span>
</div>
<!-- Description -->
@if ($desc = ($product->short_desc['bn'] ?? $product->description['bn'] ?? null))
<p class="font-body-md text-body-md text-on-surface-variant">{{ $desc }}</p>
@endif
<form action="{{ route('cart.items.store') }}" method="POST" class="flex flex-col gap-space-md">
@csrf
@php($activeVariants = $product->variants->where('is_active', true))
@if ($activeVariants->count() > 1)
<!-- Weight / Variant Selector -->
<div class="flex flex-col gap-space-xs pt-space-xs">
<label class="font-title-md text-title-md text-on-surface font-semibold">ওজন নির্বাচন করুন (Select Weight):</label>
<div class="grid grid-cols-2 gap-space-xs sm:gap-space-sm">
@foreach ($activeVariants as $variant)
@php($available = ($variant->inventoryItem?->available() ?? 0) > 0)
<label class="variant-btn {{ $loop->first ? 'active-variant' : '' }} text-left p-3 rounded-xl {{ $available ? 'bg-surface-container-lowest hover:bg-surface-container cursor-pointer' : 'opacity-50 cursor-not-allowed bg-surface-dim' }} transition-all flex flex-col justify-between shadow-sm has-[:checked]:bg-primary-container/20 has-[:checked]:shadow-sm">
<input type="radio" name="variant_id" value="{{ $variant->id }}" class="hidden pdp-variant-radio" data-price="{{ $variant->effectivePrice() }}" {{ $loop->first ? 'checked' : '' }} {{ $available ? '' : 'disabled' }}>
<div class="flex items-center justify-between w-full">
<span class="font-label-md text-label-md font-bold text-on-surface">{{ $variant->weight_grams ? $variant->weight_grams.' গ্রাম' : ($variant->sku ?? '') }}</span>
@if (! $available)<span class="font-label-sm text-[10px] bg-outline/20 px-1.5 py-0.5 rounded text-on-surface-variant">স্টক শেষ</span>@endif
</div>
<span class="font-body-sm text-body-sm text-primary font-bold mt-1">৳{{ number_format($variant->effectivePrice() / 100, 0) }}</span>
</label>
@endforeach
</div>
</div>
@else
<input type="hidden" name="variant_id" value="{{ $defaultVariant?->id }}">
@endif
<!-- Quantity Stepper & Cart Action CTAs -->
<div class="flex flex-col gap-space-sm pt-space-xs">
<div class="flex items-center gap-space-sm">
<div class="flex items-center bg-surface-container-lowest rounded-full p-1 shadow-sm">
<button aria-label="Decrease quantity" type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors active:scale-90" onclick="let i=this.nextElementSibling;i.value=Math.max(1,parseInt(i.value)-1)">
<span class="material-symbols-outlined text-[20px]">remove</span>
</button>
<input class="w-12 text-center bg-transparent font-title-lg text-title-lg font-bold text-on-surface outline-none" name="qty" max="10" min="1" type="number" value="1"/>
<button aria-label="Increase quantity" type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors active:scale-90" onclick="let i=this.previousElementSibling;i.value=Math.min(10,parseInt(i.value)+1)">
<span class="material-symbols-outlined text-[20px]">add</span>
</button>
</div>
<button type="submit" class="flex-1 py-3.5 px-space-md rounded-full bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center gap-2 shadow-md hover:bg-secondary transition-all hover:-translate-y-0.5 active:translate-y-0" {{ $inStock ? '' : 'disabled' }}>
<span class="material-symbols-outlined text-[20px]">shopping_bag</span>
<span>কার্টে যোগ করুন</span>
</button>
</div>
<div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant px-1">
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-[16px] text-tertiary">inventory</span>
সর্বোচ্চ ১০টি প্রতি অর্ডারে অনুমোদিত
</span>
</div>
</div>
</form>
<!-- Trust Assurance Quadrant (Compact) -->
<div class="grid grid-cols-2 gap-space-sm pt-space-sm">
<div class="bg-surface-container-lowest p-3 rounded-lg flex items-center gap-2.5 shadow-sm">
<div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[18px] text-on-tertiary-fixed">thermostat</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm font-semibold text-on-surface">কোল্ড এক্সট্রাক্টেড</span>
<span class="font-body-sm text-[11px] text-on-surface-variant">তাপহীন প্রাকৃতিক ফিল্টারেশন</span>
</div>
</div>
<div class="bg-surface-container-lowest p-3 rounded-lg flex items-center gap-2.5 shadow-sm">
<div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[18px] text-on-primary-fixed">local_shipping</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm font-semibold text-on-surface">ক্যাশ অন ডেলিভারি</span>
<span class="font-body-sm text-[11px] text-on-surface-variant">সারা দেশে পণ্য দেখে পেমেন্ট</span>
</div>
</div>
<div class="bg-surface-container-lowest p-3 rounded-lg flex items-center gap-2.5 shadow-sm">
<div class="w-8 h-8 rounded-full bg-secondary-fixed flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[18px] text-on-secondary-fixed">bolt</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm font-semibold text-on-surface">দ্রুততম ডেলিভারি</span>
<span class="font-body-sm text-[11px] text-on-surface-variant">ঢাকায় ২৪-৪৮ ও বাইরে ৭২ ঘণ্টা</span>
</div>
</div>
<div class="bg-surface-container-lowest p-3 rounded-lg flex items-center gap-2.5 shadow-sm">
<div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[18px] text-primary">published_with_changes</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm font-semibold text-on-surface">নিরাপদ রিটার্ন</span>
<span class="font-body-sm text-[11px] text-on-surface-variant">৩ দিনে শতভাগ নিশ্চিত রিফান্ড</span>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<script>
document.querySelectorAll('.pdp-variant-radio').forEach(function (radio) {
  radio.addEventListener('change', function () {
    var priceEl = document.getElementById('display-price');
    if (priceEl && radio.dataset.price) {
      priceEl.textContent = '৳' + Math.round(radio.dataset.price / 100).toLocaleString('bn-BD');
    }
    radio.closest('.grid').querySelectorAll('.variant-btn').forEach(function (b) { b.classList.remove('active-variant'); });
    radio.closest('.variant-btn').classList.add('active-variant');
  });
});
</script>
<!-- 2. Product Authenticity & Sensory Highlights (Icon Cards Bento) -->
<section class="w-full bg-surface-container-low py-space-xl my-space-lg">
<div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin">
<div class="flex flex-col md:flex-row md:items-end justify-between mb-space-lg">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">খাঁটি ও প্রাকৃতিক বৈশিষ্ট্য</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-1">সুন্দরবনের খলিশা মধুর বিশেষত্ব</h2>
</div>
<p class="font-body-md text-body-md text-on-surface-variant max-w-md mt-2 md:mt-0">
          প্রকৃতিতে খলিশা ফুল ফোটে বসন্তের শুরুতে। এই মধুর হালকা সোনালী বর্ণ ও অনন্য হালকা মিষ্টি স্বাদ এটিকে অন্যান্য মধুর চেয়ে স্বতন্ত্র করে তোলে।
        </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-md">
<!-- Card 1 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center shrink-0 text-primary">
<span class="material-symbols-outlined text-[24px]">forest</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">আহরণের উৎস ও রেঞ্জ</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">সুন্দরবন ম্যানগ্রোভ বন</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">সাতক্ষীরা ও মুন্সিগঞ্জ রেঞ্জের উপকূলীয় বুনো খলিশা গাছের বন থেকে বন্য মৌমাছির প্রাকৃতিক চাক থেকে সংগৃহীত।</p>
</div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl bg-tertiary-fixed flex items-center justify-center shrink-0 text-tertiary">
<span class="material-symbols-outlined text-[24px]">spa</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">ফুলের প্রজাতি</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">একক ফুলের খলিশা নেকটার</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">মনোফ্লোরাল বৈশিষ্ট্যযুক্ত; খলিশা ফুলের পরাগ ঘনত্ব শতকরা ৮০ ভাগের বেশি হওয়ায় এতে কোনো কড়া ঝাঁজ নেই।</p>
</div>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl bg-secondary-fixed flex items-center justify-center shrink-0 text-secondary">
<span class="material-symbols-outlined text-[24px]">science</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">বিশুদ্ধতা ও আর্দ্রতা</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">ল্যাব টেস্ট সার্টিফাইড</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">আর্দ্রতার পরিমাণ ২০% এর নিচে, কোনো বাড়তি চিনি বা সিরাম মেশানো হয় না। সম্পূর্ণ রাসায়নিক মুক্ত।</p>
</div>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl bg-primary-fixed-dim/40 flex items-center justify-center shrink-0 text-primary">
<span class="material-symbols-outlined text-[24px]">calendar_month</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">আহরণের মৌসুম</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">চৈত্র ও বৈশাখ (প্রথম লট)</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">বসন্তের বিদায়লগ্নে সুন্দরবনের খলিশা ফুল ফোটে এবং প্রথম মধু আহরণের এই লটটি সর্বোচ্চ ঔষধি গুণসম্পন্ন থাকে।</p>
</div>
</div>
<!-- Card 5 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl bg-secondary-fixed-dim/40 flex items-center justify-center shrink-0 text-secondary">
<span class="material-symbols-outlined text-[24px]">hourglass_top</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">সংরক্ষণ ও মেয়াদকাল</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">২ বছর (স্বাভাবিক তাপমাত্রায়)</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">খাঁটি কাঁচা মধু কখনই নষ্ট হয় না। শুকনো সাধারণ তাপমাত্রায় ঢাকনা শক্তভাবে আটকে রাখাই যথেষ্ট।</p>
</div>
</div>
<!-- Card 6 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl bg-tertiary-fixed-dim/40 flex items-center justify-center shrink-0 text-tertiary">
<span class="material-symbols-outlined text-[24px]">volunteer_activism</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">ন্যায্য বাণিজ্য ও মৌয়াল কল্যাণ</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">নৈতিক ও জীববৈচিত্র্যবান্ধব</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">মৌয়ালদের উপযুক্ত মজুরি প্রদান ও চাক না কেটে শুধুমাত্র মধুর অংশ কেটে মৌমাছির বংশবৃদ্ধি সুরক্ষিত রাখা হয়।</p>
</div>
</div>
</div>
</div>
</section>
<!-- 3. Comprehensive Tabbed Information Center -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-lg">
<div class="bg-surface-container-lowest rounded-xl p-space-md lg:p-space-lg shadow-sm">
<!-- Tab Header Strip -->
<div class="flex items-center gap-space-sm overflow-x-auto pb-space-sm border-b-0" id="pdp-tab-headers">
<button class="tab-btn active-tab px-space-md py-2.5 rounded-full font-label-md text-label-md whitespace-nowrap bg-primary-container text-on-primary-container font-bold shadow-sm transition-all" onclick="switchTab(this, 'tab-taste-overview')">
          বিবরণ ও স্বাদ (Overview)
        </button>
<button class="tab-btn px-space-md py-2.5 rounded-full font-label-md text-label-md whitespace-nowrap bg-surface-container text-on-surface-variant hover:text-on-surface transition-all" onclick="switchTab(this, 'tab-specs-table')">
          পণ্য পরিচিতি ও স্পেসিফিকেশন
        </button>
<button class="tab-btn px-space-md py-2.5 rounded-full font-label-md text-label-md whitespace-nowrap bg-surface-container text-on-surface-variant hover:text-on-surface transition-all" onclick="switchTab(this, 'tab-purity-lab')">
          বিশুদ্ধতা ও ল্যাব তথ্য (Lab Report)
        </button>
<button class="tab-btn px-space-md py-2.5 rounded-full font-label-md text-label-md whitespace-nowrap bg-surface-container text-on-surface-variant hover:text-on-surface transition-all" onclick="switchTab(this, 'tab-shipping-returns')">
          ডেলিভারি ও রিটার্ন পলিসি
        </button>
</div>
<!-- Tab Content 1: Overview & Taste Profile -->
<div class="tab-pane pt-space-lg flex flex-col gap-space-lg" id="tab-taste-overview">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
<div class="lg:col-span-7 flex flex-col gap-space-sm">
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">প্রকৃতির অতুলনীয় অমৃত: সুন্দরবনের খাঁটি খলিশা মধু</h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              সুন্দরবনের ম্যানগ্রোভ বনের গভীর ভেতরে ফোটা খলিশা ফুলের নেকটার থেকে পাওয়া যায় এই অমূল্য মধু। প্রচলিত অন্যান্য সরিষা বা লিচুর মধুর তুলনায় খলিশা মধুর মিষ্টতা অত্যন্ত পরিমিত ও স্নিগ্ধ। মুখের ভেতর নেয়ার সাথে সাথেই এক অপূর্ব প্রাকৃতিক ফুলের সুবাস ছড়িয়ে পড়ে এবং কোনো কৃত্রিম ক্ষারীয় স্বাদ পাওয়া যায় না।
            </p>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              যেহেতু এই মধুটি পাস্তুরিত বা অতিরিক্ত তাপে উত্তপ্ত করা হয় না, তাই এর ভেতর বিদ্যমান অ্যাক্টিভ এনজাইম, পলিফেনল অ্যান্টিঅক্সিডেন্ট ও প্রাকৃতিক ভিটামিন সম্পূর্ণ অটুট থাকে যা রোগ প্রতিরোধ ক্ষমতা বাড়াতে ও হজমশক্তি উন্নত করতে প্রত্যক্ষ ভূমিকা রাখে।
            </p>
<!-- Sensory Progress Bars (Visual Richness) -->
<div class="bg-surface-container-low p-space-md rounded-xl mt-space-xs flex flex-col gap-3">
<span class="font-label-md text-label-md text-on-surface font-bold">টেস্ট ও সেন্সরি প্রোফাইল:</span>
<div>
<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
<span>মিষ্টতা (Sweetness)</span>
<span class="font-bold text-on-surface">হালকা থেকে মাঝারি (৬.৫/১০)</span>
</div>
<div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 65%;"></div>
</div>
</div>
<div>
<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
<span>ফুলের সুবাস (Floral Aroma)</span>
<span class="font-bold text-on-surface">উচ্চ ও মৃদু মিষ্টি (৯.০/১০)</span>
</div>
<div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 90%;"></div>
</div>
</div>
<div>
<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1">
<span>ঘনত্ব ও টেক্সচার (Viscosity)</span>
<span class="font-bold text-on-surface">স্বাভাবিক মসৃণ তরল (৭.৫/১০)</span>
</div>
<div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 75%;"></div>
</div>
</div>
</div>
</div>
<div class="lg:col-span-5 bg-surface-container-low p-space-md rounded-xl flex flex-col gap-space-sm">
<h4 class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[20px]">local_cafe</span>
              সেবনবিধি ও আয়ুর্বেদিক পরামর্শ
            </h4>
<ul class="flex flex-col gap-2.5 font-body-sm text-body-sm text-on-surface-variant">
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
<span><strong>সকালের এনার্জি ড্রিংক:</strong> প্রতিদিন সকালে এক গ্লাস কুসুম গরম পানির সাথে ১ চামচ খলিশা মধু ও অর্ধেক লেবুর রস মিশিয়ে খালি পেটে পান করুন।</span>
</li>
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
<span><strong>কাশি ও শ্বাসকষ্টে:</strong> তুলসী পাতার রস বা আদার রসের সাথে আধ চা চামচ মধু মিশিয়ে দিনে ২ বার সেবন করুন।</span>
</li>
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
<span><strong>উষ্ণ দুধে:</strong> রাতে ঘুমানোর পূর্বে হালকা গরম দুধে এক চা চামচ খলিশা মধু অনিদ্রা দূর করতে অত্যন্ত সহায়ক।</span>
</li>
<li class="flex items-start gap-2 text-error">
<span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">warning</span>
<span>কখনই ফুটন্ত গরম পানিতে বা ফুটন্ত দুধে মধু মেশাবেন না, এতে প্রাকৃতিক এনজাইম নষ্ট হয়।</span>
</li>
</ul>
</div>
</div>
</div>
<!-- Tab Content 2: Specification Table -->
<div class="tab-pane hidden pt-space-lg" id="tab-specs-table">
<div class="overflow-x-auto">
<table class="w-full text-left font-body-md text-body-md">
<tbody>
<tr class="bg-surface-container-low">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold w-1/3">পণ্যের সাধারণ নাম</td>
<td class="py-3.5 px-4 text-on-surface-variant">সুন্দরবনের খলিশা ফুলের কাঁচা মধু (Sundarbans Khalisha Raw Honey)</td>
</tr>
<tr class="bg-surface-container-lowest">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">উদ্ভিদতাত্ত্বিক উৎস</td>
<td class="py-3.5 px-4 text-on-surface-variant">খলিশা বৃক্ষ (Aegiceras corniculatum) নেকটার</td>
</tr>
<tr class="bg-surface-container-low">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">আহরণের ভৌগোলিক অবস্থান</td>
<td class="py-3.5 px-4 text-on-surface-variant">সাতক্ষীরা রেঞ্জ, সুন্দরবন সংরক্ষিত বনাঞ্চল, বাংলাদেশ</td>
</tr>
<tr class="bg-surface-container-lowest">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">প্রক্রিয়াজাতকরণ ধরন</td>
<td class="py-3.5 px-4 text-on-surface-variant">সম্পূর্ণ তাপহীন (Raw &amp; Unpasteurized), মাইক্রো-কটন ফিল্টারেশন</td>
</tr>
<tr class="bg-surface-container-low">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">বর্তমান ব্যাচ নম্বর</td>
<td class="py-3.5 px-4 text-on-surface-variant font-mono text-[14px]">#MDH-KH-2024-B3</td>
</tr>
<tr class="bg-surface-container-lowest">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">আহরণের মাস ও সন</td>
<td class="py-3.5 px-4 text-on-surface-variant">চৈত্র ১৪৩০ / এপ্রিল ২০২৪</td>
</tr>
<tr class="bg-surface-container-low">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">প্যাকেজিং উপাদান</td>
<td class="py-3.5 px-4 text-on-surface-variant">ফুড-গ্রেড বোরোসিলিকেট এয়ারটাইট গ্লাস জার (লেড-মুক্ত মেটাল ক্যাপ)</td>
</tr>
<tr class="bg-surface-container-lowest">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold">সংরক্ষণ নির্দেশনা</td>
<td class="py-3.5 px-4 text-on-surface-variant">স্বাভাবিক কক্ষ তাপমাত্রায় রাখুন। ফ্রিজে রাখবেন না। আর্দ্র চামচ ব্যবহার করবেন না।</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Tab Content 3: Purity & Lab Test Report -->
<div class="tab-pane hidden pt-space-lg flex flex-col gap-space-lg" id="tab-purity-lab">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
<div class="lg:col-span-7 flex flex-col gap-space-sm">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[28px]">verified</span>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">সরকারি ও বিজ্ঞানাগার পরীক্ষিত বিশুদ্ধতার নিশ্চয়তা</h3>
</div>
<p class="font-body-md text-body-md text-on-surface-variant">
              মধুবন প্রতিটি ব্যাচের মধু সংগ্রহের পরপরই বাংলাদেশ বিজ্ঞান ও শিল্প গবেষণা পরিষদ (BCSIR) অনুমোদিত ল্যাবে কঠোর রাসায়নিক ও মাইক্রোবায়োলজিক্যাল টেস্ট সম্পন্ন করে। আমাদের খলিশা মধুতে সুক্রোজ, ফ্রুক্টোজ ও গ্লুকোজের অনুপাত প্রকৃতি প্রদত্ত আদর্শ মানে বিদ্যমান।
            </p>
<div class="grid grid-cols-2 gap-space-sm my-space-xs">
<div class="p-3 bg-surface-container-low rounded-lg">
<span class="font-label-sm text-label-sm text-on-surface-variant">আর্দ্রতার মান (Moisture)</span>
<div class="font-headline-sm text-headline-sm text-primary font-bold">১৯.২%</div>
<span class="text-[11px] text-on-surface-variant">মানদণ্ড: &lt; ২০% (খাঁটি কাঁচা মধুর আদর্শ)</span>
</div>
<div class="p-3 bg-surface-container-low rounded-lg">
<span class="font-label-sm text-label-sm text-on-surface-variant">কৃত্রিম চিনি / সি-৪ সুগার</span>
<div class="font-headline-sm text-headline-sm text-primary font-bold">০% (নেগেটিভ)</div>
<span class="text-[11px] text-on-surface-variant">আইসোটোপ রেশিও মাস স্পেকট্রোমেট্রি টেস্ট</span>
</div>
<div class="p-3 bg-surface-container-low rounded-lg">
<span class="font-label-sm text-label-sm text-on-surface-variant">এইচএমএফ মান (HMF)</span>
<div class="font-headline-sm text-headline-sm text-primary font-bold">১১.৪ mg/kg</div>
<span class="text-[11px] text-on-surface-variant">আন্তর্জাতিক সর্বোচ্চ সীমা: ৮০ mg/kg</span>
</div>
<div class="p-3 bg-surface-container-low rounded-lg">
<span class="font-label-sm text-label-sm text-on-surface-variant">পরাগকণা ঘনত্ব</span>
<div class="font-headline-sm text-headline-sm text-primary font-bold">৯৫%+ খলিশা ফুল</div>
<span class="text-[11px] text-on-surface-variant">মেলিসোপ্যালিনোলজি মাইক্রোস্কোপ টেস্ট</span>
</div>
</div>
</div>
<div class="lg:col-span-5 bg-surface-container-low p-space-md rounded-xl flex flex-col items-center text-center gap-space-sm">
<div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[32px]">description</span>
</div>
<h4 class="font-title-lg text-title-lg text-on-surface font-semibold">ল্যাব টেস্ট রিপোর্ট ডাউনলোড করুন</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">
              ব্যাচ #MDH-KH-2024-B3 এর বিস্তারিত টেস্ট রিপোর্টের অনুলিপি দেখতে বা ডাউনলোড করতে নিচের লিঙ্কে ক্লিক করুন।
            </p>
<a class="inline-flex items-center gap-2 bg-primary text-on-primary px-space-md py-2.5 rounded-full font-label-md text-label-md shadow-sm hover:bg-secondary transition-all" href="#" onclick="event.preventDefault(); alert('ল্যাব রিপোর্ট পিডিএফ ডাউনলোড শুরু হচ্ছে...');">
<span class="material-symbols-outlined text-[18px]">download</span>
<span>পিডিএফ সার্টিফিকেট ডাউনলোড</span>
</a>
<span class="text-[11px] text-on-surface-variant">ফাইল সাইজ: ১.৪ মেগাবাইট | টেস্ট তারিখ: ১৫ এপ্রিল ২০২৪</span>
</div>
</div>
</div>
<!-- Tab Content 4: Shipping & Returns -->
<div class="tab-pane hidden pt-space-lg" id="tab-shipping-returns">
<div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
<div class="bg-surface-container-low p-space-md rounded-xl flex flex-col gap-space-sm">
<h4 class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[20px]">local_shipping</span>
              ডেলিভারি চার্জ ও সময়সীমা
            </h4>
<ul class="flex flex-col gap-2 font-body-sm text-body-sm text-on-surface-variant">
<li class="flex justify-between py-1 border-b-0">
<span>ঢাকা সিটির ভেতরে:</span>
<span class="font-bold text-on-surface">৳৬০ (২৪ থেকে ৪৮ ঘণ্টা)</span>
</li>
<li class="flex justify-between py-1 border-b-0">
<span>ঢাকা সিটির বাইরে ও সারাদেশে:</span>
<span class="font-bold text-on-surface">৳১২০ (৪৮ থেকে ৭২ ঘণ্টা)</span>
</li>
<li class="flex justify-between py-1 border-b-0 text-primary font-bold">
<span>ফ্রি ডেলিভারি অফার:</span>
<span>১,০০০ টাকার অধিক যেকোনো অর্ডারে ডেলিভারি চার্জ সম্পূর্ণ ফ্রি!</span>
</li>
<li class="pt-2 text-on-surface-variant">
                পণ্য পৌঁছানোর পূর্বে আমাদের কুরিয়ার প্রতিনিধি আপনার সাথে ফোনে যোগাযোগ করবেন।
              </li>
</ul>
</div>
<div class="bg-surface-container-low p-space-md rounded-xl flex flex-col gap-space-sm">
<h4 class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[20px]">sync</span>
              রিটার্ন ও রিফান্ড নীতি
            </h4>
<ul class="flex flex-col gap-2 font-body-sm text-body-sm text-on-surface-variant">
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
<span>ডেলিভারিম্যানের উপস্থিতিতে পণ্য চেক করে গ্রহণ করুন। ক্ষতিগ্রস্ত বা ভুল পণ্য এলে ডেলিভারিম্যানকে তাৎক্ষণিক ফেরত দিতে পারবেন।</span>
</li>
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
<span>প্যাকেজের মূল সিল অক্ষত থাকলে পণ্য প্রাপ্তির ৩ দিনের মধ্যে নিঃশর্ত রিটার্ন গ্রহণযোগ্য।</span>
</li>
<li class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
<span>অনলাইন পেমেন্ট করা থাকলে রিটার্ন নিশ্চিতকরণের ৪৮ ঘণ্টার মধ্যে বিকাশ/নগদ বা ব্যাংকে টাকা রিফান্ড করা হয়।</span>
</li>
</ul>
</div>
</div>
</div>
</div>
</section>
<!-- 4. Editorial Mouyal Sourcing Story (Provenance Narrative) -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-xl">
<div class="bg-surface-container rounded-2xl overflow-hidden shadow-sm grid grid-cols-1 lg:grid-cols-12 items-center">
<!-- Story Visual -->
<div class="lg:col-span-5 h-80 lg:h-full relative min-h-[380px]">
<div class="w-full h-full bg-cover bg-center" alt="An editorial documentary photograph of an experienced Sundarbans Mouyal honey collector dressed in traditional attire navigating a wooden boat through a serene mangrove canal in misty golden morning light with giant mangrove roots visible." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAExJ94Ebh293YddgrKWGCu8kl1PdAsFb5SW_t9_zjsgiS9nqb7q6iJSfzxfPuQXKh7PHVaTw0LtR-pDeqtsuP-TgWjg3S3Dzf5i7q_kp8UqGLfoHh3wsEYs3JbXM8tA2baDhKb6_4YqPZ7sK3mGq37HKNgWJFEyLs8LMbhknO--6TJ6io_rvoafVPpPCnVIrKb0kEKNSBBPDGmmCx8qJBJiaZEOXk8ZUa5xrNjSzI3')"></div>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-transparent to-transparent lg:hidden"></div>
<div class="absolute bottom-4 left-4 right-4 lg:hidden text-on-primary">
<span class="font-label-sm text-label-sm uppercase tracking-widest text-primary-fixed">আহরণের গল্প</span>
<h3 class="font-headline-sm text-headline-sm font-semibold">মৌয়ালদের জীবনের ঝুঁকি ও বিশুদ্ধতার ঐতিহ্য</h3>
</div>
</div>
<!-- Story Narrative & Provenance Callout -->
<div class="lg:col-span-7 p-space-md lg:p-space-xl flex flex-col gap-space-md">
<div class="hidden lg:flex flex-col">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">প্রকৃতির কোল থেকে আপনার ঘরে</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-1">সুন্দরবনের মৌয়াল ও খাঁটি খলিশা মধুর ঐতিহ্য</h2>
</div>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
          প্রতি বছর বসন্তের বিদায় ও গ্রীষ্মের আগমনী লগ্নে সুন্দরবনের খলিশা গাছে সুগন্ধি সাদা ফুল ফোটে। সুন্দরবনের ঐতিহ্যবাহী মৌয়ালরা বন বিভাগের অনুমতি নিয়ে জীবন বাজি রেখে গভীর অরণ্যে প্রবেশ করেন। রয়েল বেঙ্গল টাইগার ও বিষধর সাপের ভয় উপেক্ষা করে তারা সংগ্রহ করেন এই অমূল্য খলিশা মধু।
        </p>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
          মধুবন সরাসরি এই স্থানীয় মৌয়াল সম্প্রদায়ের সাথে কাজ করে। আমরা কোনো মধ্যস্বত্বভোগী ছাড়া তাদের ন্যায্য মূল্য প্রদান করি। চাক থেকে সংগ্রহ করা মধু কোনো উত্তাপ বা কেমিক্যাল ছাড়া কেবল পাতলা সুতি কাপড়ে ছেঁকে কাঁচের জারে বোতলজাত করা হয়—যাতে আপনি পান ঠিক সেই স্বাদ, যা সুন্দরবনের বুনো চাকে ছিল।
        </p>
<!-- Sourcing Origin Metadata Pill Box -->
<div class="bg-surface-container-lowest p-space-md rounded-xl flex flex-wrap items-center justify-between gap-space-sm shadow-sm">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-primary-container/20 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[20px]">explore</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">সংগ্রহের স্থান</span>
<span class="font-title-md text-title-md text-on-surface font-semibold">সাতক্ষীরা সুন্দরবন রেঞ্জ</span>
</div>
</div>
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-secondary-fixed/40 flex items-center justify-center text-secondary">
<span class="material-symbols-outlined text-[20px]">handshake</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">বাণিজ্য নীতি</span>
<span class="font-title-md text-title-md text-on-surface font-semibold">১০০% ফেয়ার ট্রেড ও সরাসরি মৌয়াল</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- 5. Customer Ratings & Reviews Section -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-lg" id="customer-reviews">
<div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm flex flex-col items-center text-center gap-space-sm">
<span class="material-symbols-outlined text-on-surface-variant text-[40px]">rate_review</span>
<h3 class="font-title-lg text-title-lg text-on-surface font-semibold">এখনো কোনো রিভিউ নেই</h3>
<p class="font-body-md text-body-md text-on-surface-variant max-w-md">এই পণ্যের প্রথম ক্রেতা হয়ে আপনার অভিজ্ঞতা শেয়ার করুন — এটি অন্য ক্রেতাদের সিদ্ধান্ত নিতে সাহায্য করবে।</p>
</div>
</section>
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
<!-- 8. Product FAQ Accordion Section -->
<section class="max-w-4xl mx-auto w-full px-gutter-mobile lg:px-margin pb-space-xl">
<div class="text-center mb-space-md">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">সাধারণ জিজ্ঞাসা</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-1">খলিশা মধু সম্পর্কে সচরাচর প্রশ্ন ও উত্তর</h2>
</div>
<div class="flex flex-col gap-space-xs" id="faq-accordion">
<!-- FAQ 1 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
<button aria-expanded="false" class="w-full p-space-md flex items-center justify-between text-left text-on-surface hover:text-primary transition-colors font-title-md text-title-md font-semibold" onclick="toggleFaq(this)">
<span>১. খাঁটি মধু কি শীতকালে জমে যেতে পারে (Crystallization)?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-300">expand_more</span>
</button>
<div class="faq-content hidden px-space-md pb-space-md text-body-md font-body-md text-on-surface-variant border-t-0">
          হ্যাঁ, মধু জমে যাওয়া বা ক্রিস্টালাইজ হওয়া সম্পূর্ণ প্রাকৃতিক একটি জৈবিক প্রক্রিয়া। মধুতে উপস্থিত গ্লুকোজের পরিমাণের ওপর ভিত্তি করে তা জমে যায়। তবে সুন্দরবনের খলিশা মধুতে ফ্রুক্টোজের ভাগ বেশি থাকায় এটি সাধারণত খুব সহজে জমে না। যদি কখনো হালকা জমাট বাঁধে, তবে কুসুম গরম পানির পাত্রে বয়ামটি কিছুক্ষণ রাখলেই তা আবার তরল রূপ ধারণ করে।
        </div>
</div>
<!-- FAQ 2 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
<button aria-expanded="false" class="w-full p-space-md flex items-center justify-between text-left text-on-surface hover:text-primary transition-colors font-title-md text-title-md font-semibold" onclick="toggleFaq(this)">
<span>২. সুন্দরবনের খলিশা মধু কেন অন্যান্য মধুর চেয়ে কিছুটা দামি?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-300">expand_more</span>
</button>
<div class="faq-content hidden px-space-md pb-space-md text-body-md font-body-md text-on-surface-variant border-t-0">
          খলিশা ফুল সুন্দরবনের গভীর অরণ্যে বছরে মাত্র একবার খুব স্বল্প সময়ের জন্য ফোটে। বাঘের আক্রমণ ও প্রাকৃতিক দুর্যোগের প্রবল ঝুঁকি নিয়ে মৌয়ালরা এই মধু সংগ্রহ করেন। তাছাড়া এর প্রাকৃতিক ফলন অত্যন্ত সীমিত এবং ঔষধি গুণাগুণ সাধারণ চাষের মধুর চেয়ে বহুগুণ সমৃদ্ধ হওয়ায় এর চাহিদা ও আহরণ ব্যয় বেশি।
        </div>
</div>
<!-- FAQ 3 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
<button aria-expanded="false" class="w-full p-space-md flex items-center justify-between text-left text-on-surface hover:text-primary transition-colors font-title-md text-title-md font-semibold" onclick="toggleFaq(this)">
<span>৩. কাঁচা মধু (Raw Honey) ও সাধারণ প্রক্রিয়াজাত মধুর মধ্যে পার্থক্য কী?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-300">expand_more</span>
</button>
<div class="faq-content hidden px-space-md pb-space-md text-body-md font-body-md text-on-surface-variant border-t-0">
          সাধারণ বাণিজ্যিক মধু উচ্চ তাপে পাস্তুরিত (Pasteurized) ও অতি-পরিশোধিত করা হয়, যার ফলে মধুর ভেতর থাকা অমূল্য প্রাকৃতিক পরাগরেণু, ভিটামিন ও রোগ প্রতিরোধকারী এনজাইমগুলো পুরোপুরি নষ্ট হয়ে যায়। আমাদের মধুবনের কাঁচা মধু কখনোই উত্তপ্ত করা হয় না; চাক কাটার পর প্রাকৃতিক কাপড়ে ছেঁকে সরাসরি বয়ামজাত করা হয়।
        </div>
</div>
<!-- FAQ 4 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
<button aria-expanded="false" class="w-full p-space-md flex items-center justify-between text-left text-on-surface hover:text-primary transition-colors font-title-md text-title-md font-semibold" onclick="toggleFaq(this)">
<span>৪. মধু কীভাবে সংরক্ষণ করলে সবচেয়ে ভালো থাকবে?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-300">expand_more</span>
</button>
<div class="faq-content hidden px-space-md pb-space-md text-body-md font-body-md text-on-surface-variant border-t-0">
          মধু সর্বদা স্বাভাবিক রুম টেম্পারেচারে (২৫° - ৩০° সে.) শুকনো স্থানে রাখুন। ভুলেও মধু ফ্রিজে রাখবেন না। ব্যবহারের সময় সবসময় শুকনো কাঠের বা স্টিলের চামচ ব্যবহার করবেন এবং বয়ামের মুখ সবসময় শক্তভাবে আটকে রাখবেন যেন বাইরের জলীয় বাষ্প প্রবেশ না করতে পারে।
        </div>
</div>
<!-- FAQ 5 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
<button aria-expanded="false" class="w-full p-space-md flex items-center justify-between text-left text-on-surface hover:text-primary transition-colors font-title-md text-title-md font-semibold" onclick="toggleFaq(this)">
<span>৫. পণ্য হাতে পাওয়ার পর কীভাবে এর আসল প্রমাণ যাচাই করব?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-300">expand_more</span>
</button>
<div class="faq-content hidden px-space-md pb-space-md text-body-md font-body-md text-on-surface-variant border-t-0">
          প্রতিটি বয়ামের লেবেলে একটি অনন্য কিউআর কোড (QR Code) যুক্ত রয়েছে। এটি আপনার স্মার্টফোন দিয়ে স্ক্যান করলেই সরাসরি সেই ব্যাচের অনুমোদিত ল্যাব টেস্ট সার্টিফিকেট ও আহরণের তারিখ দেখতে পারবেন। এছাড়াও আমরা শতভাগ ক্যাশ অন ডেলিভারি সুবিধা দিই—আপনি পণ্য দেখে নিশ্চিত হয়ে মূল্য পরিশোধ করতে পারেন।
        </div>
</div>
</div>
</section>
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
@forelse ($product->media as $media)
@continue(! $media->media)
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $media->media->url() }}"/>
</div>
@empty
<div class="w-full h-full flex-shrink-0 snap-center relative flex items-center justify-center bg-surface-container text-8xl">🍯</div>
@endforelse
</div>
<!-- Wishlist Heart Button -->
<form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3">
@csrf
<button aria-label="উইশলিস্টে যোগ করুন" type="submit" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md flex items-center justify-center shadow-md active:scale-90 transition-transform {{ in_array($product->id, $wishlistIds) ? 'text-error' : 'text-on-surface' }}">
<span class="material-symbols-outlined text-[20px] transition-colors" style="{{ in_array($product->id, $wishlistIds) ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
</button>
</form>
</div>
@if ($product->media->count() > 1)
<!-- Gallery Thumbnail Strip -->
<div class="flex items-center gap-2 mt-2.5 overflow-x-auto pb-1">
@foreach ($product->media as $media)
@continue(! $media->media)
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
<a href="#customer-reviews" class="flex items-center gap-1 bg-surface-container-high px-2 py-0.5 rounded-lg font-label-sm text-label-sm text-on-surface-variant">এখনো কোনো রিভিউ নেই</a>
</div>
</section>
<!-- 4. Dynamic Price Display -->
<section class="px-gutter-mobile pt-space-md">
<div class="p-space-md rounded-xl bg-surface-container-low shadow-sm flex items-center justify-between">
<div class="flex items-baseline gap-2.5">
<span class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary" id="current-price">৳{{ number_format(($mobileDefaultVariant?->effectivePrice() ?? 0) / 100, 0) }}</span>
@if ($mobileDefaultVariant && $mobileDefaultVariant->sale_price)
<span class="font-title-md text-title-md line-through text-outline" id="regular-price">৳{{ number_format($mobileDefaultVariant->price / 100, 0) }}</span>
@endif
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
<input type="radio" name="variant_id" value="{{ $variant->id }}" class="hidden pdp-variant-radio-mobile" data-price="{{ $variant->effectivePrice() }}" {{ $loop->first ? 'checked' : '' }} {{ $mobileAvailable ? '' : 'disabled' }}>
<div class="flex flex-col">
<span class="font-label-md text-label-md font-semibold">{{ $variant->weight_grams ? $variant->weight_grams.' গ্রাম' : ($variant->sku ?? '') }}</span>
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
<!-- 7. Accordions for Product Information -->
<section class="px-gutter-mobile pt-space-lg flex flex-col gap-2.5">
<!-- Accordion 1: Description (Expanded by default) -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-primary text-[20px]">menu_book</span>
<span class="font-title-md text-title-md text-on-surface font-semibold">পণ্যের বিবরণ ও ব্যবহার</span>
</div>
<span class="material-symbols-outlined text-outline transition-transform duration-200 rotate-180">keyboard_arrow_down</span>
</button>
<div class="accordion-content px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<p class="leading-relaxed">
          সুন্দরবনের গভীর ম্যানগ্রোভ অরণ্যের প্রাকৃতিকভাবে ফোটা খলিশা (Aegiceras corniculatum) ফুল থেকে সংগৃহীত খাঁটি মধু। এতে রয়েছে উচ্চমাত্রার প্রাকৃতিক অ্যান্টি-অক্সিডেন্ট ও এনজাইম। এর হালকা সোনালী আভা, মৃদু প্রাকৃতিক মিষ্টি সুবাস এবং অনন্য স্বাদ শরীরকে সতেজ রাখে ও রোগ প্রতিরোধ ক্ষমতা বৃদ্ধি করে।
        </p>
<div class="mt-3 p-3 bg-surface-container-low rounded-lg">
<h4 class="font-label-md text-label-md text-on-surface font-bold mb-1">নিয়মিত ব্যবহারের উপকারিতা:</h4>
<ul class="font-body-sm text-body-sm space-y-1 text-on-surface-variant list-disc pl-4">
<li>সকালে হালকা গরম পানিতে লেবু ও মধু মেটাবলিজম উন্নত করে।</li>
<li>ঠাণ্ডা, খুসখুসে কাশি এবং গলা ব্যথায় তাৎক্ষণিক উপশম দেয়।</li>
<li>ত্বকের লাবণ্য ধরে রাখতে প্রাকৃতিকভাবে সহায়তা করে।</li>
</ul>
</div>
</div>
</div>
<!-- Accordion 2: Specs & Batch -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-primary text-[20px]">fact_check</span>
<span class="font-title-md text-title-md text-on-surface font-semibold">পণ্যের বৈশিষ্ট্য ও স্পেসিফিকেশন</span>
</div>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<div class="space-y-2">
<div class="flex justify-between py-1.5 border-none bg-surface-container-low px-3 rounded-lg">
<span class="text-on-surface-variant font-body-sm text-body-sm">উৎস অঞ্চল</span>
<span class="font-label-md text-label-md text-on-surface font-semibold">সুন্দরবন (শ্যামনগর রেঞ্জ)</span>
</div>
<div class="flex justify-between py-1.5 bg-surface-container-low px-3 rounded-lg">
<span class="text-on-surface-variant font-body-sm text-body-sm">ফুলের উৎস</span>
<span class="font-label-md text-label-md text-on-surface font-semibold">১০০% খলিশা ফুল</span>
</div>
<div class="flex justify-between py-1.5 bg-surface-container-low px-3 rounded-lg">
<span class="text-on-surface-variant font-body-sm text-body-sm">সংগ্রহের তারিখ</span>
<span class="font-label-md text-label-md text-on-surface font-semibold">মার্চ-এপ্রিল ২০২৪</span>
</div>
<div class="flex justify-between py-1.5 bg-surface-container-low px-3 rounded-lg">
<span class="text-on-surface-variant font-body-sm text-body-sm">আর্দ্রতার হার</span>
<span class="font-label-md text-label-md text-on-surface font-semibold">১৮.৪% (স্ট্যান্ডার্ড মানসম্মত)</span>
</div>
<div class="flex justify-between py-1.5 bg-surface-container-low px-3 rounded-lg">
<span class="text-on-surface-variant font-body-sm text-body-sm">সংরক্ষণ মেয়াদ</span>
<span class="font-label-md text-label-md text-on-surface font-semibold">প্যাকিং থেকে ২ বছর</span>
</div>
</div>
</div>
</div>
<!-- Accordion 3: Purity & Lab Guarantee -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-primary text-[20px]">science</span>
<span class="font-title-md text-title-md text-on-surface font-semibold">বিশুদ্ধতা ও কোয়ালিটি গ্যারান্টি</span>
</div>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<div class="p-3 bg-secondary-container/40 rounded-xl space-y-2">
<div class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[20px] mt-0.5">verified_user</span>
<p class="font-body-sm text-body-sm text-on-surface">
              প্রতিটি ব্যাচ বিসিএসআইআর (BCSIR) অনুমোদিত ল্যাব টেস্টে পরিশোধিত চিনি, কৃত্রিম সুগন্ধি ও রাসায়নিক মুক্ত প্রমাণিত।
            </p>
</div>
<div class="flex items-start gap-2">
<span class="material-symbols-outlined text-primary text-[20px] mt-0.5">thermostat</span>
<p class="font-body-sm text-body-sm text-on-surface">
              কোল্ড ফিল্টার প্রযুক্তিতে ফিল্টার করা হয়, কোনো ধরণের উচ্চ তাপে গরম করা হয় না যাতে প্রাকৃতিক এনজাইম অক্ষুণ্ণ থাকে।
            </p>
</div>
</div>
</div>
</div>
<!-- Accordion 4: Delivery & COD Policy -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5">
<span class="material-symbols-outlined text-primary text-[20px]">local_shipping</span>
<span class="font-title-md text-title-md text-on-surface font-semibold">ডেলিভারি ও রিটার্ন পলিসি</span>
</div>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<ul class="font-body-sm text-body-sm space-y-2">
<li class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
<span>ঢাকা সিটির ভেতরে: ২৪-৪৮ ঘণ্টার মধ্যে হোম ডেলিভারি (চার্জ ৳৬০)।</span>
</li>
<li class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[18px]">travel_explore</span>
<span>ঢাকার বাইরে সারাদেশে: ২-৪ কার্যদিবস (চার্জ ৳১১০)।</span>
</li>
<li class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[18px]">payments</span>
<span>ক্যাশ অন ডেলিভারি (COD) সুবিধা উপলব্ধ। পণ্য দেখে মূল্য পরিশোধ করুন।</span>
</li>
<li class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[18px]">replay</span>
<span>প্যাকেজে ভাঙা বা ভিন্ন পণ্য পেলে ৭ দিনের মানিব্যাক গ্যারান্টি।</span>
</li>
</ul>
</div>
</div>
</section>
<!-- 8. Origin Story Card -->
<section class="px-gutter-mobile pt-space-lg">
<div class="relative rounded-2xl overflow-hidden bg-surface-container-low shadow-sm">
<div class="flex flex-col">
<div class="w-full h-36 relative">
<img class="w-full h-full object-cover" alt="Breathtaking wide view of the Sundarbans mangrove river network during early dawn with traditional wooden boats of traditional honey hunters Mawalis gently cruising through misty canals under golden sunlight." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCkZa4CkQFaokR2Gwkh16zOWYJR-1YLT60PZsn8A55Uy4YU6eroMZeS1zmer18kzD8L1Z00Ge6W4TB3a9W-Jt3NIhHRYVxK3tJAA1QXlDpDuvKx4iBGNTPSIJf2jBks0nKCvuh9is4qWpQBtGXoSdGUGJB3PeRGtkZTmryhcx4xbQye6FltKcchD0h5MEGSsBph_llhC-6ubNhyy5WXAqvksuiDVGiZ_RSSaXfcUbKP"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface-container-low via-transparent to-transparent"></div>
<span class="absolute top-2.5 left-2.5 bg-inverse-surface/80 backdrop-blur-sm text-inverse-on-surface px-2.5 py-0.5 rounded-full font-label-sm text-label-sm">
            ঐতিহ্যবাহী সংগ্রহ
          </span>
</div>
<div class="p-space-md">
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold mb-1">
            মৌয়ালদের শ্রম ও সুন্দরবনের ঐতিহ্য
          </h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
            সুন্দরবনের দুর্গম গভীরে জীবনের ঝুঁকি নিয়ে আমাদের নিবন্ধিত স্থানীয় মৌয়ালরা খলিশা ফুল থেকে ফোটা বিশুদ্ধ মৌচাক থেকে এই মধু সংগ্রহ করেন। মৌমাছি বা বনভূমির ক্ষতি না করে আমরা শতভাগ নৈতিক পদ্ধতিতে মধু আহরণ নিশ্চিত করি।
          </p>
<div class="mt-3 flex items-center gap-2 pt-2">
<div class="w-7 h-7 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">
              M
            </div>
<span class="font-label-sm text-label-sm text-on-surface font-medium">সরাসরি মৌয়াল সমবায় থেকে সংগৃহীত</span>
</div>
</div>
</div>
</div>
</section>
<!-- 9. Mobile Customer Reviews -->
<section class="px-gutter-mobile pt-space-lg" id="customer-reviews">
<div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col items-center text-center gap-2">
<span class="material-symbols-outlined text-on-surface-variant text-[32px]">rate_review</span>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">এখনো কোনো রিভিউ নেই</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">এই পণ্যের প্রথম ক্রেতা হয়ে আপনার অভিজ্ঞতা শেয়ার করুন।</p>
</div>
</section>
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
<!-- 11. Compact Product FAQ Accordion -->
<section class="px-gutter-mobile pt-space-lg mb-4">
<div class="flex items-center gap-2 mb-space-sm">
<span class="material-symbols-outlined text-primary text-[22px]">quiz</span>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">সাধারণ জিজ্ঞাসা (FAQ)</h3>
</div>
<div class="flex flex-col gap-2">
<!-- FAQ 1 -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden">
<button class="w-full min-h-[48px] px- space-md py-3 px-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<span class="font-label-md text-label-md text-on-surface font-semibold pr-2">খলিশা মধু কি জমে যেতে পারে?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-3.5 pb-3 font-body-sm text-body-sm text-on-surface-variant">
          খাঁটি মধু শীতকালে বা অতিরিক্ত ঠান্ডায় স্ফটিক (Granulation) রূপ নিতে পারে। এটি খাঁটি মধুর একটি স্বাভাবিক ভৌত ধর্ম। হালকা কুসুম গরম পানিতে বৈয়াম কিছুক্ষণ রাখলে আবার তরল হয়ে যায়।
        </div>
</div>
<!-- FAQ 2 -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden">
<button class="w-full min-h-[48px] px- space-md py-3 px-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<span class="font-label-md text-label-md text-on-surface font-semibold pr-2">মধুটি কি ডায়াবেটিস রোগীরা খেতে পারবেন?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-3.5 pb-3 font-body-sm text-body-sm text-on-surface-variant">
          আমাদের মধু সম্পূর্ণ প্রাকৃতিক ও চিনিমুক্ত। তবে ডায়াবেটিস রোগীদের যেকোনো মিষ্টি জাতীয় উপাদান গ্রহণের পূর্বে চিকিৎসকের পরামর্শ নেওয়া আবশ্যক।
        </div>
</div>
<!-- FAQ 3 -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden">
<button class="w-full min-h-[48px] px- space-md py-3 px-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<span class="font-label-md text-label-md text-on-surface font-semibold pr-2">আমি কীভাবে নিশ্চিত হব এটি খাঁটি মধু?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-3.5 pb-3 font-body-sm text-body-sm text-on-surface-variant">
          প্রতিটি বোতলের সাথে আমাদের ল্যাব টেস্ট কিউআর কোড থাকে যা স্ক্যান করে খাঁটি মানের সার্টিফিকেট দেখতে পারবেন। এছাড়াও রয়েছে ১০০% ক্যাশব্যাক নিশ্চয়তা।
        </div>
</div>
<!-- FAQ 4 -->
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden">
<button class="w-full min-h-[48px] px- space-md py-3 px-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<span class="font-label-md text-label-md text-on-surface font-semibold pr-2">ডেলিভারি পাওয়ার পর পণ্য ভাঙা থাকলে করণীয়?</span>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-3.5 pb-3 font-body-sm text-body-sm text-on-surface-variant">
          ডেলিভারি ম্যানের সামনে পণ্য দেখে নিন। কোনো সমস্যা পরিলক্ষিত হলে ডেলিভারি ম্যান থাকা অবস্থাতেই আমাদের হেল্পলাইনে কল দিন, আমরা বিনা খরচে তাৎক্ষণিক রিপ্লেসমেন্ট পাঠাব।
        </div>
</div>
</div>
</section>
<!-- 12. STICKY BOTTOM PURCHASE BAR (sits just above the shared mobile bottom-nav) -->
<aside aria-label="Quick Purchase Bar" class="fixed bottom-16 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl shadow-[0_-4px_24px_rgba(56,39,25,0.08)]">
<div class="max-w-[430px] mx-auto px-gutter-mobile py-2.5 flex items-center justify-between gap-3">
<!-- Price & Selected Weight Information -->
<div class="flex flex-col min-w-0 pr-1">
<span class="font-label-sm text-[11px] text-on-surface-variant leading-none">মোট মূল্য</span>
<div class="flex items-baseline gap-1 mt-0.5">
<span class="font-title-lg text-title-lg text-primary font-bold leading-tight" id="bar-price">৳{{ number_format(($mobileDefaultVariant?->effectivePrice() ?? 0) / 100, 0) }}</span>
</div>
<span class="font-label-sm text-[11px] text-outline truncate font-medium" id="bar-variant">@if ($mobileDefaultVariant?->weight_grams){{ $mobileDefaultVariant->weight_grams }} গ্রাম@endif</span>
</div>
<!-- Action Buttons -->
<div class="flex items-center gap-2 flex-1 justify-end">
<!-- Add to Cart (Secondary Full Pill) -->
<button form="pdp-add-to-cart-form" type="submit" class="min-h-[46px] px-3.5 py-2 rounded-full bg-surface-container-highest text-on-surface hover:text-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition-all" {{ $mobileInStock ? '' : 'disabled' }}>
<span class="material-symbols-outlined text-[19px]">add_shopping_cart</span>
<span class="whitespace-nowrap">কার্টে যোগ</span>
</button>
<!-- Buy Now (same action — no separate instant-checkout flow yet) -->
<button form="pdp-add-to-cart-form" type="submit" class="min-h-[46px] px-4 py-2 rounded-full bg-gradient-to-r from-primary-container to-primary text-on-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1 shadow-md hover:shadow-lg active:scale-95 transition-all flex-shrink-0" {{ $mobileInStock ? '' : 'disabled' }}>
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
