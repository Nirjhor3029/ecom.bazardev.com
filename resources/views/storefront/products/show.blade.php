@extends('storefront.layout')

@section('title', ($product->name['bn'] ?? '') . ' — মধুবন')

@section('bare-chrome', 'yes')

@section('content')
<!-- ================= DESKTOP (lg and up) — ported verbatim from product-details-code.html ================= -->
<div class="hidden lg:block">
<header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]"><div class="bg-surface-container-high py-space-xs text-on-surface-variant"><div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin flex flex-col sm:flex-row items-center justify-between text-body-sm gap-space-xs"><div class="flex items-center gap-space-sm"><span class="material-symbols-outlined text-primary text-[18px]">verified</span><span class="font-label-sm text-label-sm text-on-surface">বিশুদ্ধ প্রাকৃতিক মধু ও বাদাম এখন আপনার হাতেই | সারা দেশে ক্যাশ অন ডেলিভারি | ১০০০+ টাকার অর্ডারে ফ্রি ডেলিভারি</span></div><div class="flex items-center gap-space-md"><div class="flex items-center gap-space-xs font-label-md text-label-md"><span class="material-symbols-outlined text-[16px] text-primary">call</span><span>হটলাইন: ০১৭০০-০০০০০০</span></div><div class="flex items-center gap-space-xs font-label-sm text-label-sm"><button class="font-bold text-primary underline">বা</button><span class="text-outline-variant">|</span><button class="text-on-surface-variant hover:text-on-surface transition-colors">EN</button></div></div></div></div><div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin py-space-sm flex items-center justify-between gap-space-md"><a class="flex items-center gap-space-sm" data-path="home" href="#"><div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary-container text-[24px]">hive</span></div><div class="flex flex-col"><span class="font-headline-sm text-headline-sm text-on-surface font-semibold tracking-tight">মধুবন</span><span class="font-label-sm text-label-sm text-on-surface-variant">প্রাকৃতিক মধু ও বাদাম</span></div></a><div class="hidden md:flex flex-1 max-w-xl mx-space-lg"><div class="relative w-full"><input class="w-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant text-body-md font-body-md pl-space-md pr-12 py-space-sm rounded-full outline-none ring-1 ring-outline-variant focus:ring-2 focus:ring-primary transition-all" placeholder="আপনি কী খুঁজছেন?..." type="text"/><button class="absolute right-space-xs top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center hover:bg-secondary transition-colors"><span class="material-symbols-outlined text-[20px]">search</span></button></div></div><div class="flex items-center gap-space-md"><a class="relative p-space-xs rounded-full hover:bg-surface-container transition-colors text-on-surface-variant hover:text-on-surface" data-path="wishlist" href="#"><span class="material-symbols-outlined text-[24px]">favorite</span><span class="absolute -top-1 -right-1 bg-primary text-on-primary font-label-sm text-[10px] w-4 h-4 rounded-full flex items-center justify-center leading-none">৩</span></a><a class="relative p-space-xs rounded-full hover:bg-surface-container transition-colors text-on-surface-variant hover:text-on-surface" data-path="cart" href="#"><span class="material-symbols-outlined text-[24px]">shopping_bag</span><span class="absolute -top-1 -right-1 bg-primary text-on-primary font-label-sm text-[10px] w-4 h-4 rounded-full flex items-center justify-center leading-none">২</span></a><a class="flex items-center gap-space-xs p-space-xs rounded-full hover:bg-surface-container transition-colors" data-path="account" href="#"><div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div></a></div></div><nav class="hidden lg:flex items-center justify-center gap-space-xs pb-space-xs" data-active-classes="bg-primary-container text-on-primary-container font-bold rounded-full"><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="home" href="#">হোম</a><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="all-products" href="#">সব পণ্য</a><a aria-current="page" class="px-space-md py-space-xs transition-all bg-primary-container text-on-primary-container font-bold rounded-full" data-path="honey" href="#">মধু</a><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="nuts" href="#">বাদাম</a><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="combos-gift-boxes" href="#">কম্বো ও গিফট বক্স</a><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="lab-test-purity" href="#">ল্যাব টেস্ট ও বিশুদ্ধতা</a><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="our-story" href="#">আমাদের গল্প</a><a class="px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-path="contact" href="#">যোগাযোগ</a></nav></header><main class="w-full pt-44 bg-surface"><div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin pt-space-sm pb-space-xs"><div class="flex items-center gap-space-xs text-body-sm font-body-sm text-on-surface-variant"><a class="hover:text-primary transition-colors flex items-center gap-1" data-path="home" href="#"><span class="material-symbols-outlined text-[16px]">home</span><span>হোম</span></a><span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span><span class="text-on-surface font-label-sm text-label-sm">বিভাগ</span></div></div><div class="flex flex-col w-full">
<!-- Top Secondary Breadcrumb Hierarchy Anchor -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pt-space-xs pb-space-sm">
<nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-space-xs text-body-sm font-body-sm text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="#">হোম</a>
<span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">মধু (Honey)</a>
<span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">র হানি (Raw Honey)</a>
<span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
<span class="text-on-surface font-label-md text-label-md bg-secondary-fixed/50 px-2 py-0.5 rounded-full">সুন্দরবনের খলিশা মধু</span>
</nav>
</section>
<!-- 1. Main PDP Hero Two-Column Layout -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pb-space-xl">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg lg:gap-space-xl items-start">
<!-- Left Column: Product Visual Media Hub (7 Cols on desktop) -->
<div class="lg:col-span-7 flex flex-col gap-space-md">
<!-- Main Media Frame -->
<div class="relative bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm aspect-[4/3] flex items-center justify-center group">
<div class="w-full h-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-105" alt="An exquisite aesthetic photograph of a premium glass hexagonal jar filled with golden-amber raw Khalisha flower honey from Sundarbans. Natural warm morning sunlight illuminates the crystal clarity, illuminating tiny suspended floral pollen grains. A handcrafted wooden honey dipper rests atop, allowing rich, viscous, glistening honey to drip delicately into a ceramic dish. Warm creamy linen background, artisanal packaging." id="main-product-display" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCCEuO9N_GeLspXje75bL0eLIuFTUIhnnoouQ7PWXEWIk8O8Mfuz6AQJRuCQN7uSXHPq7te3ZuBA7VdN_Zmqf8nt4jn7SsC5PufR0yvJKWOnsTaLSUrlYMpKwuPNF4him0zc8-z7cOk_HzMaYJ4QRlk-Jv2Cy8-mq7fCpes60hJdOmGtbHDP9XS3NtvJ5kQ0f-Gvt5oTgWo3Gi_SpbCi0jciN9VmSPRc9mEAhBtvo4l')"></div>
<!-- Top-Left Origin / Purity Badge -->
<div class="absolute top-4 left-4 z-10 flex flex-col gap-1.5 items-start">
<span class="inline-flex items-center gap-1.5 bg-primary-container text-on-primary-container font-label-sm text-label-sm px-3 py-1 rounded-full shadow-sm backdrop-blur-md">
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">eco</span>
              ১০০% র ও খাঁটি
            </span>
<span class="inline-flex items-center gap-1 bg-surface-container-high/90 text-on-surface font-label-sm text-label-sm px-2.5 py-0.5 rounded-full backdrop-blur-sm">
<span class="material-symbols-outlined text-[14px] text-primary">pin_drop</span>
              সাতক্ষীরা রেঞ্জ
            </span>
</div>
<!-- Top-Right Actions (Zoom & Wishlist) -->
<div class="absolute top-4 right-4 z-10 flex items-center gap-2">
<button aria-label="Zoom image" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-on-surface flex items-center justify-center shadow-md hover:bg-surface-container transition-all hover:scale-110 active:scale-95" id="zoom-button" title="জুম করে দেখুন">
<span class="material-symbols-outlined text-[20px]">zoom_in</span>
</button>
<button aria-label="Add to wishlist" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-on-surface flex items-center justify-center shadow-md hover:bg-surface-container transition-all hover:scale-110 active:scale-95 group/wishlist" id="wishlist-toggle-btn" onclick="toggleWishlist(this)" title="উইশলিস্টে যুক্ত করুন">
<span class="material-symbols-outlined text-[20px] text-error transition-all group-[.is-active]/wishlist:fill-1" style="font-variation-settings: 'FILL' 0;">favorite</span>
</button>
</div>
<!-- Bottom Provenance Strip Overlay -->
<div class="absolute bottom-3 left-3 right-3 py-2 px-3 rounded-lg bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-between text-body-sm font-body-sm text-on-surface">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px] text-primary">verified_user</span>
<span class="font-label-sm text-label-sm">ব্যাচ আইডি: #MDH-KH-2024-B3</span>
</div>
<span class="font-label-sm text-label-sm text-primary font-semibold">আর্দ্রতা ১৯.২% (ল্যাব সার্টিফাইড)</span>
</div>
</div>
<!-- Thumbnails Gallery & Video Selector -->
<div class="grid grid-cols-6 gap-space-xs sm:gap-space-sm" id="pdp-thumbnails">
<!-- Thumbnail 1 (Active) -->
<button aria-label="View packaging jar" class="thumb-item active-thumb relative aspect-square rounded-lg overflow-hidden bg-surface-container-high transition-all shadow-sm hover:opacity-100" onclick="selectThumbnail(this, 'placeholder', 'An exquisite aesthetic photograph of a premium glass hexagonal jar filled with golden-amber raw Khalisha flower honey from Sundarbans. Natural warm morning sunlight illuminates the crystal clarity, illuminating tiny suspended floral pollen grains.')">
<img class="w-full h-full object-cover" alt="Close up photograph of sealed luxury glass jar of Khalisha honey with traditional wooden lid and paper seal ribbon." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAEpDVs1eHqLy7yqU0jVsSPhqNRICB4c_QEs3hFAHsndkWgM8ybSojhjJCA89QbdyWoYn48Rk7QXBWxc6rB1O2PsI0ZLr1Iuqi35se2GpDBI3aLrNQ3heeP1u2lA-iwugkTbhXyfmGeGGHhkj5sjrp4-NcScOWep-859175H09vULitp13gMlIttAXHDC6yuaL_ou0uGi7d3rskLbrKizpZ_RCYXbGanad-K7KwTLgY"/>
<span class="absolute inset-0 bg-primary/20 transition-opacity"></span>
</button>
<!-- Thumbnail 2 -->
<button aria-label="View honey texture close up" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-high opacity-70 hover:opacity-100 transition-all" onclick="selectThumbnail(this, 'placeholder', 'Extreme macro close-up of dense amber Khalisha honey texture with microscopic pollen particles and warm sunlight reflection.')">
<img class="w-full h-full object-cover" alt="Macro macro-photography of golden raw honey texture highlighting natural thick viscosity, small air bubbles and rich golden amber hues." src="https://lh3.googleusercontent.com/aida-public/AB6AXuASCjGTV_F6sLPvrq5nq5UHZtOG4iDQ63Ox0yhpKXWja_aFX0DgcygA7KCIiMQs667R0u32Qcwzz6lrdLMt0Cc2_RMn__T_t7BiokiyaK1QXGsIj5Xge2Cgz7soJ02ICl9N7-Lx4VgZkG6f9yamzC93RI1JRc8W9H-HsTK8dmjSqlGE795R4eyiPouuHYqE7M3tjUD89VQ4t6RcBLw2l24nQqmYJxAs5O0IQN2vs_9R"/>
</button>
<!-- Thumbnail 3 -->
<button aria-label="View honey pouring" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-high opacity-70 hover:opacity-100 transition-all" onclick="selectThumbnail(this, 'placeholder', 'Slow-motion capture of thick unfiltered Sundarbans raw honey pouring gracefully from a wooden honey dipper into a bowl.')">
<img class="w-full h-full object-cover" alt="Dynamic shot of Khalisha honey streaming cleanly from a wooden dipper creating concentric ripples in a shallow artisanal ceramic bowl." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBYfxnHFZCJ9a5Mx2Cofh6fFgLS6lIcKyFOrHMuCnoYdxvYnd3wzVfLiN0ua1T8linWYflCyMEv64w8A0Uluj4VPAldc8w0DgnziwEwsSSf89NkuR9L55y0ooPPKXNIcpxCi9DRg6ITAGodYgFhtrZDMRC6eIa7XKLZrBffz9vEbb05gNkfhpBAo_6StXe-DVyt53LbxmZr2wfCC91aV5C83CQfPCSGQFwbxzshJyP"/>
</button>
<!-- Thumbnail 4 -->
<button aria-label="View source environment" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-high opacity-70 hover:opacity-100 transition-all" onclick="selectThumbnail(this, 'placeholder', 'Lush mangrove Khalisha tree flowers in full bloom along misty tidal creek in Sundarbans with wild giant honeybees collecting nectar.')">
<img class="w-full h-full object-cover" alt="Sundarbans deep mangrove forest tidal channel with blooming Aegiceras corniculatum Khalisha white flowers in natural soft daylight." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZzM4XSoSTfgMQ4coLOuneqn2G7d5fUT2j_BZWhA5es3wvkU5t3KyrU36pDuh0oh3hyeoY7jtgXNDou2PlXIuIyJUTYkJDLXjgx4ShWqwQJHOoxefW7n_JY0sqrArkmIv2eTo8RcScrhVH5NO5tIVofMtMa9BiAX75wOFh2jljFN0MLclD56iGrpzUohAzLY-OQVUqhJTnqGQ6GqBzIB55Bmsycm7JhCobV1nmiMwG"/>
</button>
<!-- Thumbnail 5 -->
<button aria-label="View gift box package" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-high opacity-70 hover:opacity-100 transition-all" onclick="selectThumbnail(this, 'placeholder', 'Artisanal pine wooden gift box presentation including two jars of raw Khalisha honey, a brass spoon, and wild bee motif tag.')">
<img class="w-full h-full object-cover" alt="Handcrafted luxury pine wooden gift box with engraved brass clasp containing Sundarban honey jar and honey dipper on velvet lining." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAkeFU7VdJ5npgsEYFbS1B_4JZoU0gQSEO1PgL3Ag97QuTN3L7GnMeUF1cUePyHrvSYixVKVTy9JwMGcoS38nMTTf4pwl9YfWd_YIz2T0SirTReOSbNlBVaWWsUtsAY71fXC9Z_XLjIeEA_9ZJeVHcBk29_Xeq4H9wPsnnKzc2u-7tCCDw7Iy3jfHqMAiMqe-UNln8Jx2AebSBaAi-cqr9mMgGtZCpyPfkuzoVfOlA-"/>
</button>
<!-- Thumbnail 6 (Video Demostration) -->
<button aria-label="Watch video demo" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-highest flex flex-col items-center justify-center text-on-surface opacity-85 hover:opacity-100 transition-all group/vid" onclick="playVideoDemoModal()">
<div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-md group-hover/vid:scale-110 transition-transform">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
</div>
<span class="font-label-sm text-[10px] mt-1 text-on-surface-variant font-semibold">ভিডিও দেখুন</span>
</button>
</div>
<!-- Quick Honey Harvest Metric Grid -->
<div class="grid grid-cols-3 gap-space-sm pt-space-xs">
<div class="bg-surface-container p-3 rounded-lg flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-[28px]">water_drop</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">প্রাকৃতিক আর্দ্রতা</span>
<span class="font-title-md text-title-md text-on-surface font-bold">১৯.২%</span>
</div>
</div>
<div class="bg-surface-container p-3 rounded-lg flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-[28px]">local_florist</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">পরাগকণা ঘনত্ব</span>
<span class="font-title-md text-title-md text-on-surface font-bold">৯৪.৮% খাঁটি</span>
</div>
</div>
<div class="bg-surface-container p-3 rounded-lg flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-[28px]">verified</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">এইচএমএফ লেভেল</span>
<span class="font-title-md text-title-md text-on-surface font-bold">&lt; ১৪ mg/kg</span>
</div>
</div>
</div>
</div>
<!-- Right Column: Product Purchase Control Center (5 Cols on desktop) -->
<div class="lg:col-span-5 flex flex-col gap-space-md">
<!-- Category Pill & SKU -->
<div class="flex items-center justify-between">
<span class="inline-flex items-center gap-1 font-label-md text-label-md text-primary bg-primary-fixed px-3 py-0.5 rounded-full font-bold">
<span class="material-symbols-outlined text-[14px]">stars</span>
            র হানি • প্রিমিয়াম গ্রেড
          </span>
<span class="font-label-sm text-label-sm text-on-surface-variant tracking-wider">SKU: <strong class="text-on-surface font-semibold" id="product-sku">MDH-KH-500</strong></span>
</div>
<!-- Product Title & Botanical Designation -->
<div class="flex flex-col gap-1">
<h1 class="font-headline-lg text-headline-lg text-on-surface font-semibold tracking-tight">সুন্দরবনের খলিশা মধু</h1>
<p class="font-title-lg text-title-lg text-secondary italic font-headline-sm">Sundarbans Raw Khalisha Floral Honey</p>
</div>
<!-- Ratings, Reviews, & Stock Status -->
<div class="flex flex-wrap items-center justify-between gap-space-xs py-space-xs border-y-0">
<div class="flex items-center gap-2">
<div class="flex items-center text-primary-container">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star_half</span>
</div>
<span class="font-label-md text-label-md text-on-surface font-bold">৪.৮</span>
<span class="text-outline-variant">|</span>
<a class="font-label-sm text-label-sm text-primary hover:underline underline-offset-4" href="#customer-reviews">১২৮টি ভেরিফায়েড রিভিউ</a>
</div>
<!-- Stock Pill -->
<div class="inline-flex items-center gap-1.5 bg-secondary-fixed/70 text-on-secondary-fixed px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
            ইন স্টক — দ্রুত শেষ হচ্ছে
          </div>
</div>
<!-- Price Display Box -->
<div class="bg-surface-container-low p-space-md rounded-xl flex items-baseline justify-between shadow-sm">
<div class="flex items-baseline gap-space-sm">
<span class="font-display-hero text-headline-lg text-primary font-bold" id="display-price">৳৭৫০</span>
<span class="font-title-lg text-title-lg text-on-surface-variant line-through" id="display-original-price">৳৮৫০</span>
<span class="bg-error-container text-on-error-container font-label-sm text-label-sm px-2 py-0.5 rounded-full font-bold" id="display-discount">১২% ছাড়</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">ভ্যাট অন্তর্ভুক্ত</span>
</div>
<!-- Sensory & Harvest Summary -->
<p class="font-body-md text-body-md text-on-surface-variant">
          সুন্দরবনের গভীর ম্যানগ্রোভ বনের খলিশা ফুলের প্রাকৃতিক নেকটার থেকে মৌয়ালদের দ্বারা সংগৃহীত কাঁচা ও অপ্রক্রিয়াজাত খাঁটি মধু। এতে রয়েছে সজীব প্রাকৃতিক এনজাইম, অমূল্য পরাগরেণু ও হালকা মনোহর সুবাস।
        </p>
<!-- Weight / Variant Selector (Interactive) -->
<div class="flex flex-col gap-space-xs pt-space-xs">
<div class="flex items-center justify-between">
<label class="font-title-md text-title-md text-on-surface font-semibold">ওজন নির্বাচন করুন (Select Weight):</label>
<span class="font-label-sm text-label-sm text-primary underline cursor-pointer" onclick="showWeightGuideModal()">সাইজ গাইড</span>
</div>
<div class="grid grid-cols-2 gap-space-xs sm:gap-space-sm" id="variant-group">
<!-- Variant 1 -->
<button class="variant-btn text-left p-3 rounded-xl bg-surface-container-lowest hover:bg-surface-container transition-all flex flex-col justify-between shadow-sm cursor-pointer" onclick="selectWeight(this, '২৫০ গ্রাম', 420, 480, 'MDH-KH-250', '১৩%')" type="button">
<div class="flex items-center justify-between w-full">
<span class="font-label-md text-label-md font-bold text-on-surface">২৫০ গ্রাম</span>
<span class="check-icon hidden material-symbols-outlined text-[18px] text-primary">check_circle</span>
</div>
<span class="font-body-sm text-body-sm text-secondary font-semibold mt-1">৳৪২০</span>
</button>
<!-- Variant 2 (Default Selected) -->
<button class="variant-btn active-variant text-left p-3 rounded-xl bg-primary-container/20 text-on-surface transition-all flex flex-col justify-between shadow-sm cursor-pointer" onclick="selectWeight(this, '৫০০ গ্রাম', 750, 850, 'MDH-KH-500', '১২%')" type="button">
<div class="flex items-center justify-between w-full">
<span class="font-label-md text-label-md font-bold text-on-surface">৫০০ গ্রাম (জনপ্রিয়)</span>
<span class="check-icon material-symbols-outlined text-[18px] text-primary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<span class="font-body-sm text-body-sm text-primary font-bold mt-1">৳৭৫০</span>
</button>
<!-- Variant 3 -->
<button class="variant-btn text-left p-3 rounded-xl bg-surface-container-lowest hover:bg-surface-container transition-all flex flex-col justify-between shadow-sm cursor-pointer" onclick="selectWeight(this, '১ কেজি', 1450, 1650, 'MDH-KH-1000', '১২%')" type="button">
<div class="flex items-center justify-between w-full">
<span class="font-label-md text-label-md font-bold text-on-surface">১ কেজি (পারিবারিক)</span>
<span class="check-icon hidden material-symbols-outlined text-[18px] text-primary">check_circle</span>
</div>
<span class="font-body-sm text-body-sm text-secondary font-semibold mt-1">৳১,৪৫০</span>
</button>
<!-- Variant 4 (Out of Stock) -->
<button class="opacity-50 cursor-not-allowed text-left p-3 rounded-xl bg-surface-dim transition-all flex flex-col justify-between shadow-none relative overflow-hidden" disabled="" type="button">
<div class="flex items-center justify-between w-full">
<span class="font-label-md text-label-md font-medium text-on-surface-variant line-through">২ কেজি (বাল্ক জার)</span>
<span class="font-label-sm text-[10px] bg-outline/20 px-1.5 py-0.5 rounded text-on-surface-variant">স্টক শেষ</span>
</div>
<span class="font-body-sm text-body-sm text-outline mt-1">৳২,৮০০</span>
</button>
</div>
</div>
<!-- Quantity Stepper & Cart Action CTAs -->
<div class="flex flex-col gap-space-sm pt-space-xs">
<div class="flex items-center gap-space-sm">
<!-- Quantity Box -->
<div class="flex items-center bg-surface-container-lowest rounded-full p-1 shadow-sm">
<button aria-label="Decrease quantity" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors active:scale-90" onclick="updateQuantity(-1)">
<span class="material-symbols-outlined text-[20px]">remove</span>
</button>
<input class="w-12 text-center bg-transparent font-title-lg text-title-lg font-bold text-on-surface outline-none" id="product-qty-input" max="10" min="1" readonly="" type="number" value="1"/>
<button aria-label="Increase quantity" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors active:scale-90" onclick="updateQuantity(1)">
<span class="material-symbols-outlined text-[20px]">add</span>
</button>
</div>
<!-- Primary Add to Cart CTA -->
<button class="flex-1 py-3.5 px-space-md rounded-full bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center gap-2 shadow-md hover:bg-secondary transition-all hover:-translate-y-0.5 active:translate-y-0" onclick="handleAddToCart()">
<span class="material-symbols-outlined text-[20px]">shopping_bag</span>
<span>কার্টে যোগ করুন</span>
</button>
<!-- Instant Buy Now CTA -->
<button class="py-3.5 px-space-lg rounded-full bg-secondary text-on-secondary font-label-md text-label-md flex items-center justify-center gap-2 shadow-md hover:bg-on-background transition-all hover:-translate-y-0.5 active:translate-y-0" onclick="handleInstantCheckout()">
<span>এখনই কিনুন</span>
</button>
</div>
<div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant px-1">
<span class="flex items-center gap-1">
<span class="material-symbols-outlined text-[16px] text-tertiary">inventory</span>
              সর্বোচ্চ ১০টি জার প্রতি অর্ডারে অনুমোদিত
            </span>
<div class="flex items-center gap-3">
<button class="hover:text-primary transition-colors flex items-center gap-1 font-label-sm text-label-sm" onclick="shareProduct()">
<span class="material-symbols-outlined text-[16px]">share</span>
                শেয়ার
              </button>
</div>
</div>
</div>
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
</section>
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
<!-- 5. Customer Ratings & Verified Reviews Section -->
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-lg" id="customer-reviews">
<div class="flex flex-col gap-space-lg">
<!-- Review Section Header & Breakdown Bento -->
<div class="bg-surface-container-lowest p-space-md lg:p-space-lg rounded-2xl shadow-sm">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
<!-- Overall Rating -->
<div class="lg:col-span-4 flex flex-col items-center lg:items-start text-center lg:text-left gap-1">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">গ্রাহক পর্যালোচনা</span>
<div class="flex items-baseline gap-2 mt-1">
<span class="font-display-hero text-display-hero text-on-surface font-bold">৪.৮</span>
<span class="font-title-lg text-title-lg text-on-surface-variant">/ ৫.০</span>
</div>
<div class="flex items-center text-primary-container my-1">
<span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">star_half</span>
</div>
<span class="font-body-sm text-body-sm text-on-surface-variant">১২৮ জন যাচাইকৃত ক্রেতার মতামতের ভিত্তিতে</span>
</div>
<!-- Rating Bar Distributions (Inline Visual Chart) -->
<div class="lg:col-span-5 flex flex-col gap-1.5">
<!-- 5 star -->
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-12 text-on-surface-variant">৫ স্টার</span>
<div class="flex-1 bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 88%;"></div>
</div>
<span class="w-10 text-right text-on-surface font-semibold">৮৮%</span>
</div>
<!-- 4 star -->
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-12 text-on-surface-variant">৪ স্টার</span>
<div class="flex-1 bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 9%;"></div>
</div>
<span class="w-10 text-right text-on-surface font-semibold">৯%</span>
</div>
<!-- 3 star -->
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-12 text-on-surface-variant">৩ স্টার</span>
<div class="flex-1 bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 2%;"></div>
</div>
<span class="w-10 text-right text-on-surface font-semibold">২%</span>
</div>
<!-- 2 star -->
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-12 text-on-surface-variant">২ স্টার</span>
<div class="flex-1 bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 1%;"></div>
</div>
<span class="w-10 text-right text-on-surface font-semibold">১%</span>
</div>
<!-- 1 star -->
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-12 text-on-surface-variant">১ স্টার</span>
<div class="flex-1 bg-surface-container-high h-2.5 rounded-full overflow-hidden">
<div class="bg-primary h-full rounded-full" style="width: 0%;"></div>
</div>
<span class="w-10 text-right text-on-surface font-semibold">০%</span>
</div>
</div>
<!-- Write a Review Action CTA -->
<div class="lg:col-span-3 flex flex-col items-center justify-center p-space-sm rounded-xl bg-surface-container-low text-center gap-2">
<span class="font-title-md text-title-md text-on-surface font-semibold">আপনার অভিজ্ঞতা কেমন?</span>
<p class="font-body-sm text-[12px] text-on-surface-variant">আপনার সৎ মতামত অন্য ক্রেতাদের সঠিক মধু বেছে নিতে সাহায্য করবে।</p>
<button class="w-full py-2.5 px-space-md rounded-full bg-on-surface text-surface font-label-md text-label-md hover:bg-primary transition-all mt-1 shadow-sm" onclick="openReviewModal()">
              রিভিউ লিখুন
            </button>
</div>
</div>
</div>
<!-- Verified Review Cards (3 Rich Cards with user photos) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
<!-- Review 1 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between gap-space-sm hover:shadow-md transition-shadow">
<div class="flex flex-col gap-2">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-9 h-9 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed font-bold font-label-md">
                  রা
                </div>
<div class="flex flex-col">
<span class="font-title-md text-title-md text-on-surface font-semibold">রাকিবুল হাসান</span>
<span class="font-label-sm text-[11px] text-on-surface-variant">ধানমন্ডি, ঢাকা</span>
</div>
</div>
<span class="inline-flex items-center gap-1 font-label-sm text-[11px] text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full">
<span class="material-symbols-outlined text-[13px]">verified</span> ভেরিফাইড
              </span>
</div>
<div class="flex items-center text-primary-container">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm text-on-surface-variant ml-2">৩ দিন আগে</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
              "মধুবনের খলিশা মধুর গন্ধটা অসাধারণ! অন্যান্য মধুর মতো মুখে কড়া মিষ্টি লাগে না, খুব হালকা এবং একটা বুনো স্নিগ্ধ ঘ্রাণ আছে। কাঁচের বয়ামটাও খুব সুরক্ষিত অবস্থায় পেয়েছি।"
            </p>
</div>
<!-- Customer photo attachment -->
<div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container mt-2">
<img class="w-full h-full object-cover" alt="Real customer unboxing photo showing the open jar of Khalisha honey on a clean dining table in Dhaka." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCj7XVYja7itSU_nqAz7z6t5BnGyRf5-3oWy-oNf7DuxgtbgCEKCb47zO8BKhuCST9_e6s90oRRxTokjaKDbRmQgY_QKfDpZDP6wRkXOWASeRs9jFUHYvHKkS-bDRpAILGQajXeZndXjC3Vagoeawhohtu5CaV4ulXTflKrsebsnwn_KeAzYR9YOpfcVbw5xqVlHKWG5rcO5oe_PFZEnAxdCIISuXUAHbvbWgODG7Im"/>
</div>
</div>
<!-- Review 2 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between gap-space-sm hover:shadow-md transition-shadow">
<div class="flex flex-col gap-2">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-bold font-label-md">
                  তা
                </div>
<div class="flex flex-col">
<span class="font-title-md text-title-md text-on-surface font-semibold">তানজিলা আহমেদ</span>
<span class="font-label-sm text-[11px] text-on-surface-variant">পাঁচলাইশ, চট্টগ্রাম</span>
</div>
</div>
<span class="inline-flex items-center gap-1 font-label-sm text-[11px] text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full">
<span class="material-symbols-outlined text-[13px]">verified</span> ভেরিফাইড
              </span>
</div>
<div class="flex items-center text-primary-container">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm text-on-surface-variant ml-2">১ সপ্তাহ আগে</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
              "আমি দ্বিতীয়বার ১ কেজি প্যাক অর্ডার করলাম। আব্বুর ডায়াবেটিস থাকায় চিকিৎসকের পরামর্শে পরিমিত র মধু খান। এই মধুর টেস্টে শতভাগ খাঁটি হওয়ার প্রমাণ স্পষ্ট। অনেক ধন্যবাদ মধুবন টিমকে।"
            </p>
</div>
<!-- Customer photo attachment -->
<div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container mt-2">
<img class="w-full h-full object-cover" alt="Customer phone photo showing a glass spoon scooping clear amber honey out of the 1kg honey container." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAwVOIkTxg_L4n04fMSQTfYlYCpRQVfGy0LB3XMecaPgTSntE6ko5n2Nb-dBP3AbO6HvBHus5rLaL2YZ_DWYFbV9nlZm4UWbWzqjwL5Jf9759N-dFMIVMys-0ZrOeeqWv2wLbk4JpiQmSylCdYVo9opJtkQioqNZn6xggAZ8EzY3C0MA9Eor5957dW5MzNPQ-QuOx0_SnyAUZ1gDRNR-mrtvM_zM9M87jNCEvQjvdZ_"/>
</div>
</div>
<!-- Review 3 -->
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between gap-space-sm hover:shadow-md transition-shadow">
<div class="flex flex-col gap-2">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-9 h-9 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed font-bold font-label-md">
                  ম
                </div>
<div class="flex flex-col">
<span class="font-title-md text-title-md text-on-surface font-semibold">মাহমুদুল করিম</span>
<span class="font-label-sm text-[11px] text-on-surface-variant">উত্তরা, ঢাকা</span>
</div>
</div>
<span class="inline-flex items-center gap-1 font-label-sm text-[11px] text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full">
<span class="material-symbols-outlined text-[13px]">verified</span> ভেরিফাইড
              </span>
</div>
<div class="flex items-center text-primary-container">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]">star</span>
<span class="font-label-sm text-label-sm text-on-surface-variant ml-2">২ সপ্তাহ আগে</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
              "ডেলিভারি পেয়েছি মাত্র ২৪ ঘণ্টায়। প্যাকেজিং চমৎকার ছিল, বাবল র‍্যাপে মোড়ানো। ল্যাব টেস্ট রিপোর্ট কিউআর কোড স্ক্যান করেও দেখে নিয়েছি। শতভাগ প্রাকৃতিক প্রোডাক্ট।"
            </p>
</div>
<!-- Customer photo attachment -->
<div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container mt-2">
<img class="w-full h-full object-cover" alt="Customer photo of the packaged box and lab test QR code tag sitting on a wooden study table." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCjcjJCBht3-KUx9qAuwT6tQYgDtM6VsNHputJRePX2bsPzcG0PHsnpLwFjZI39df3IWi7j6KJM-VgyEvfWdug2EiY7F561i2ynmqsOcJpIjlK-Yj3PUxudjMb1054fDiBskVKXFEw-BaRBMWk1U0KiM355OwGUxtIav7k2UvfY8WFOJ0ZiIs1PQO-FQxZKIiffiHnDWnTwmtJLgCqoiFOXIzHFB7u3vL_Jgfm5n5Yb"/>
</div>
</div>
</div>
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
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-xl">
<div class="flex items-center justify-between mb-space-md">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">সম্পর্কিত পণ্য</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-0.5">আপনার আরও ভালো লাগতে পারে</h2>
</div>
<a class="font-label-md text-label-md text-primary hover:underline flex items-center gap-1" data-path="all-products" href="#">
        সব দেখুন <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
<!-- Card 1 -->
<div class="bg-surface-container-lowest rounded-xl p-space-sm shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
<div class="relative aspect-square rounded-lg overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Artisanal glass jar filled with golden creamy raw mustard flower honey (Shorisha Modhu) on a rustic wooden platform." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB0tdSQuCtWjCJo5ykmNCLeXVGSCtp485jkddKxPwCWckC9Nv1IjGLq3stABPk3Gaq1HdNDRsLGrzZJQNVfdRHBZGHdzUt6WeWt-Bd27Z_kgzdHVUGsR6cE6UChCGCHKmmi-xBiTeD_rFmMQOPR1Oc3lnWZqP6VAVoXhNvPJzKw3h4r-RU-bgsIQKUkq_FjQ_RwTjvzf3CdrdmjctJFd-CQ5t15PAlv3ZcVUugyHAVq"/>
<span class="absolute top-2.5 left-2.5 bg-surface-container-lowest/90 backdrop-blur-sm text-on-surface font-label-sm text-[10px] px-2 py-0.5 rounded-full font-bold">
            সরিষা ফুল
          </span>
</div>
<div class="flex flex-col gap-1 mt-space-sm">
<span class="font-label-sm text-[11px] text-on-surface-variant">র হানি • ৫০০ গ্রাম</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold group-hover:text-primary transition-colors">সরিষা ফুলের খাঁটি মধু</h3>
<div class="flex items-baseline gap-2 mt-1">
<span class="font-title-lg text-title-lg font-bold text-primary">৳৫৮০</span>
<span class="text-body-sm font-body-sm text-on-surface-variant line-through">৳৬৫০</span>
</div>
</div>
<button class="w-full mt-space-sm py-2 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-1.5" onclick="quickAddToCart('সরিষা ফুলের খাঁটি মধু', 580)">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
          কার্টে যোগ করুন
        </button>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest rounded-xl p-space-sm shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
<div class="relative aspect-square rounded-lg overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Pristine roasted whole jumbo cashew nuts in a ceramic bowl with natural warm lighting and organic packaging in background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD05WRch2j9lB4iBNcKm4zaozRLJVghEtRbOIWarQtKCe5gpxfQepvDMvAKkuiLCtymoYm2SOVuOqLaKCWhyllhmIS5jb6AwK4MMh0DWSm0_sktzreSUR3DyQa_bmpqwrZRBnlu6aFTIuW4gbBLZqGgQYz3_rmx-awynCfGf5CYsa2e81OYriB0wVH89-56MiHcgrIqvmJhIr9ba6sn3kTVXSEzW42XE1d7y6C3IZ5v"/>
<span class="absolute top-2.5 left-2.5 bg-surface-container-lowest/90 backdrop-blur-sm text-on-surface font-label-sm text-[10px] px-2 py-0.5 rounded-full font-bold">
            W180 জাম্বো
          </span>
</div>
<div class="flex flex-col gap-1 mt-space-sm">
<span class="font-label-sm text-[11px] text-on-surface-variant">ড্রাই নাটস • ২৫০ গ্রাম</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold group-hover:text-primary transition-colors">প্রিমিয়াম রোস্টেড কাজুবাদাম</h3>
<div class="flex items-baseline gap-2 mt-1">
<span class="font-title-lg text-title-lg font-bold text-primary">৳৪৫০</span>
<span class="text-body-sm font-body-sm text-on-surface-variant line-through">৳৫০০</span>
</div>
</div>
<button class="w-full mt-space-sm py-2 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-1.5" onclick="quickAddToCart('প্রিমিয়াম রোস্টেড কাজুবাদাম', 450)">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
          কার্টে যোগ করুন
        </button>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest rounded-xl p-space-sm shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
<div class="relative aspect-square rounded-lg overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Rich deep dark Kalijira black seed flower raw honey in clear glass bottle with nigella seeds scattered around." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCGwwUC7-RJP0P-moB4Tu37cs8XvK4gazkoYrQs5P3V43jKl8XTUi-Kg01CnY4NEkvVk3OWHIJNPVKkdR-QGC9ZmySmTVCKmpYoEsNlBywMErKqRopNO7ciHSSv5imZlWCt1pGgd5uCTbN7yZVjTGtZaJaQ9fqTQrT6ucm-f8bNvEUwVf2qIY_s5wuiM_mk4IPz2Z53943CPvaehNpaNzRblrqNl9ZQbkgQ-RUIBezW"/>
<span class="absolute top-2.5 left-2.5 bg-surface-container-lowest/90 backdrop-blur-sm text-on-surface font-label-sm text-[10px] px-2 py-0.5 rounded-full font-bold">
            কালোজিরা ফুল
          </span>
</div>
<div class="flex flex-col gap-1 mt-space-sm">
<span class="font-label-sm text-[11px] text-on-surface-variant">র হানি • ৫০০ গ্রাম</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold group-hover:text-primary transition-colors">কালোজিরা ফুলের প্রাকৃতিক মধু</h3>
<div class="flex items-baseline gap-2 mt-1">
<span class="font-title-lg text-title-lg font-bold text-primary">৳৮২০</span>
<span class="text-body-sm font-body-sm text-on-surface-variant line-through">৳৯২০</span>
</div>
</div>
<button class="w-full mt-space-sm py-2 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-1.5" onclick="quickAddToCart('কালোজিরা ফুলের প্রাকৃতিক মধু', 820)">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
          কার্টে যোগ করুন
        </button>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest rounded-xl p-space-sm shadow-sm flex flex-col justify-between group hover:shadow-md transition-all">
<div class="relative aspect-square rounded-lg overflow-hidden bg-surface-container">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Luxury combo box featuring raw honey jars, almond packs and wooden dippers decorated with natural burlap." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBtfpnk19tzWOXtYQl-XGFam-pLcUIG67Sr4B_un2QynRgU-pV-cu9nHwu_SAQhvhLpZBaPH7-g867PnsbG6DA8h6dr3nBHnYT_4OpD_I5azEc79X-zGqJEipd-G8e9XLp40Tf0mxkbGtTynVq_VEroRG_eRbiVgURNdWgsSAPpLFTmcPOavc67ur0W-dfTFo_gXdbey4m7pZgTxCvDF4EU-T2qpDsynz6ymidNEQTG"/>
<span class="absolute top-2.5 left-2.5 bg-primary-container text-on-primary-container font-label-sm text-[10px] px-2 py-0.5 rounded-full font-bold">
            গিফট কম্বো
          </span>
</div>
<div class="flex flex-col gap-1 mt-space-sm">
<span class="font-label-sm text-[11px] text-on-surface-variant">কম্বো সেট • স্পেশাল প্যাক</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold group-hover:text-primary transition-colors">হানি ও নাটস রয়্যাল কম্বো</h3>
<div class="flex items-baseline gap-2 mt-1">
<span class="font-title-lg text-title-lg font-bold text-primary">৳১,৭৫০</span>
<span class="text-body-sm font-body-sm text-on-surface-variant line-through">৳১,৯৮০</span>
</div>
</div>
<button class="w-full mt-space-sm py-2 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-bold hover:bg-primary hover:text-on-primary transition-all flex items-center justify-center gap-1.5" onclick="quickAddToCart('হানি ও নাটস রয়্যাল কম্বো', 1750)">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
          কার্টে যোগ করুন
        </button>
</div>
</div>
</section>
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
<!-- Toast Notification Overlay (Micro-interaction for Cart / Wishlist) -->
<div class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-3 bg-inverse-surface text-inverse-on-surface px-5 py-3 rounded-xl shadow-2xl pointer-events-none" id="pdp-toast">
<span class="material-symbols-outlined text-inverse-primary text-[24px]" id="toast-icon">check_circle</span>
<span class="font-body-md text-body-md font-medium" id="toast-message">পণ্যটি কার্টে সফলভাবে যোগ করা হয়েছে!</span>
</div>
<!-- Interactive JavaScript Handling Tabs, Variants, Stepper, and Wishlist -->
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

    // Weight / Variant Selector Logic
    function selectWeight(btnElement, weightName, salePrice, originalPrice, sku, discountPercent) {
      const buttons = document.querySelectorAll('#variant-group .variant-btn');
      buttons.forEach(b => {
        b.classList.remove('active-variant', 'bg-primary-container/20');
        b.classList.add('bg-surface-container-lowest');
        const icon = b.querySelector('.check-icon');
        if (icon) icon.classList.add('hidden');
      });

      btnElement.classList.add('active-variant', 'bg-primary-container/20');
      btnElement.classList.remove('bg-surface-container-lowest');
      const activeIcon = btnElement.querySelector('.check-icon');
      if (activeIcon) activeIcon.classList.remove('hidden');

      // Update Pricing UI
      document.getElementById('display-price').textContent = '৳' + salePrice.toLocaleString('bn-BD');
      document.getElementById('display-original-price').textContent = '৳' + originalPrice.toLocaleString('bn-BD');
      document.getElementById('display-discount').textContent = discountPercent + ' ছাড়';
      document.getElementById('product-sku').textContent = sku;
    }

    // Quantity Stepper
    function updateQuantity(delta) {
      const input = document.getElementById('product-qty-input');
      let current = parseInt(input.value) || 1;
      current += delta;
      if (current < 1) current = 1;
      if (current > 10) {
        current = 10;
        showToast('warning', 'একটি অর্ডারে সর্বোচ্চ ১০টি জার নেওয়া যাবে');
      }
      input.value = current;
    }

    // Thumbnail Switcher
    function selectThumbnail(btn, imgSrc, imgAlt) {
      const thumbs = document.querySelectorAll('#pdp-thumbnails .thumb-item');
      thumbs.forEach(t => t.classList.remove('active-thumb', 'opacity-100'));
      btn.classList.add('active-thumb', 'opacity-100');

      const display = document.getElementById('main-product-display');
      display.style.backgroundImage = `url('${imgSrc}')`;
      display.setAttribute('data-alt', imgAlt);
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

    // Wishlist Toggle
    function toggleWishlist(btn) {
      btn.classList.toggle('is-active');
      const heartIcon = btn.querySelector('.material-symbols-outlined');
      const isActive = btn.classList.contains('is-active');

      if (isActive) {
        heartIcon.style.fontVariationSettings = "'FILL' 1";
        showToast('favorite', 'উইশলিস্টে যুক্ত করা হয়েছে!');
      } else {
        heartIcon.style.fontVariationSettings = "'FILL' 0";
        showToast('heart_broken', 'উইশলিস্ট থেকে সরানো হয়েছে');
      }
    }

    // Toast Notification Engine
    function showToast(iconName, message) {
      const toast = document.getElementById('pdp-toast');
      const icon = document.getElementById('toast-icon');
      const msg = document.getElementById('toast-message');

      icon.textContent = iconName;
      msg.textContent = message;

      toast.classList.remove('translate-y-20', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');

      setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
      }, 3000);
    }

    function handleAddToCart() {
      const qty = document.getElementById('product-qty-input').value;
      showToast('shopping_bag', `${qty}টি জার কার্টে যোগ করা হয়েছে!`);
    }

    function quickAddToCart(title, price) {
      showToast('shopping_bag', `${title} কার্টে যোগ করা হয়েছে!`);
    }

    function handleInstantCheckout() {
      showToast('credit_card', 'চেকআউট পেজে নিয়ে যাওয়া হচ্ছে...');
    }

    function shareProduct() {
      if (navigator.share) {
        navigator.share({
          title: 'সুন্দরবনের খলিশা মধু - মধুবন',
          text: '১০০% খাঁটি ও প্রাকৃতিক খলিশা মধু',
          url: window.location.href,
        }).catch(() => {});
      } else {
        showToast('share', 'লিঙ্ক কপি করা হয়েছে!');
      }
    }

    function openReviewModal() {
      showToast('rate_review', 'রিভিউ ফরম চালু করা হচ্ছে...');
    }

    function showWeightGuideModal() {
      showToast('straighten', '২৫০ গ্রাম ১ জনের জন্য, ৫০০ গ্রাম স্ট্যান্ডার্ড পরিবার, ১ কেজি মাসিক পারিবারিক ব্যবহারের জন্য উপযুক্ত।');
    }

    function playVideoDemoModal() {
      showToast('play_circle', 'খলিশা মধুর কাটিং ও ফিল্টারিং ভিডিও লোড হচ্ছে...');
    }
  </script>
</div></main><footer class="w-full bg-surface-container-low text-on-surface pt-space-xl pb-space-lg"><div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-lg mb-space-xl"><div class="flex flex-col gap-space-sm"><div class="flex items-center gap-space-sm"><div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary-container text-[20px]">hive</span></div><span class="font-headline-sm text-headline-sm text-on-surface font-semibold">মধুবন</span></div><p class="font-body-sm text-body-sm text-on-surface-variant">আমরা সুন্দরবনের গভীর অরণ্য এবং পাহাড়ি অঞ্চলের শতভাগ খাঁটি ও প্রাকৃতিক মধু এবং প্রিমিয়াম মানের কাঠবাদাম, কাজুবাদাম আপনার ঘরে পৌঁছে দিতে অঙ্গীকারবদ্ধ। প্রতিটি ড্রপ শতভাগ ল্যাব সার্টিফাইড ও ক্যামিক্যালমুক্ত।</p><div class="flex items-center gap-space-xs mt-space-xs"><span class="inline-flex items-center gap-1 bg-tertiary-fixed text-on-tertiary-fixed px-space-sm py-1 rounded-full font-label-sm text-label-sm"><span class="material-symbols-outlined text-[14px]">verified</span>১০০% খাঁটি</span><span class="inline-flex items-center gap-1 bg-secondary-fixed text-on-secondary-fixed px-space-sm py-1 rounded-full font-label-sm text-label-sm"><span class="material-symbols-outlined text-[14px]">eco</span>ন্যাচারাল</span></div></div><div class="flex flex-col gap-space-sm"><h4 class="font-title-md text-title-md text-on-surface font-semibold">প্রয়োজনীয় লিঙ্ক</h4><nav class="flex flex-col gap-space-xs text-body-sm font-body-sm" data-active-classes="text-primary font-bold"><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="all-products" href="#">সব পণ্যসমগ্র</a><a aria-current="page" class="transition-colors text-primary font-bold" data-path="honey" href="#">প্রাকৃতিক কাঁচা মধু</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="nuts" href="#">প্রিমিয়াম ড্রাই নাটস</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="combos-gift-boxes" href="#">কম্বো অফার প্যাক</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="lab-test-purity" href="#">ল্যাব রিপোর্ট ও বিশুদ্ধতা সনদ</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="our-story" href="#">আমাদের মৌয়াল ও গল্প</a></nav></div><div class="flex flex-col gap-space-sm"><h4 class="font-title-md text-title-md text-on-surface font-semibold">গ্রাহক সেবা ও নীতিমালা</h4><nav class="flex flex-col gap-space-xs text-body-sm font-body-sm" data-active-classes="text-primary font-bold"><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="return-policy" href="#">রিটার্ন ও রিফান্ড পলিসি</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="delivery-policy" href="#">ডেলিভারি এবং পেমেন্ট শর্তাবলী</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="faq" href="#">সাধারণ জিজ্ঞাসা (FAQ)</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="terms-conditions" href="#">শর্তাবলী ও নীতিমালা</a><a class="text-on-surface-variant hover:text-on-surface transition-colors" data-path="privacy-policy" href="#">গোপনীয়তা রক্ষা নীতি</a></nav></div><div class="flex flex-col gap-space-sm"><h4 class="font-title-md text-title-md text-on-surface font-semibold">যোগাযোগ ও ঠিকানা</h4><div class="flex flex-col gap-space-xs text-body-sm font-body-sm text-on-surface-variant"><div class="flex items-start gap-space-xs"><span class="material-symbols-outlined text-[18px] text-primary shrink-0">location_on</span><span>হাউস #১২, রোড #০৫, ধানমন্ডি, ঢাকা - ১২০৫, বাংলাদেশ</span></div><div class="flex items-center gap-space-xs"><span class="material-symbols-outlined text-[18px] text-primary shrink-0">call</span><span>হটলাইন: ০১৭০০-০০০০০০, ০১৮০০-০০০০০০</span></div><div class="flex items-center gap-space-xs"><span class="material-symbols-outlined text-[18px] text-primary shrink-0">mail</span><span>support@madhuban.com.bd</span></div><div class="flex items-center gap-space-xs"><span class="material-symbols-outlined text-[18px] text-primary shrink-0">schedule</span><span>প্রতিদিন সকাল ৯:০০ - রাত ১০:০০</span></div></div></div></div><div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin pt-space-lg flex flex-col md:flex-row items-center justify-between gap-space-md"><div class="flex flex-col sm:flex-row items-center gap-space-sm"><span class="font-label-sm text-label-sm text-on-surface-variant">পেমেন্ট মেথড:</span><div class="flex items-center flex-wrap gap-space-xs"><span class="bg-surface-container px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold text-on-surface">bKash</span><span class="bg-surface-container px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold text-on-surface">Nagad</span><span class="bg-surface-container px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold text-on-surface">Rocket</span><span class="bg-surface-container px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold text-on-surface">Visa</span><span class="bg-surface-container px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold text-on-surface">Mastercard</span><span class="bg-surface-container px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold text-on-surface">Cash On Delivery</span></div></div><div class="font-body-sm text-body-sm text-on-surface-variant text-center md:text-right">© ২০২৪ মধুবন। সর্বস্বত্ব সংরক্ষিত। খাঁটি প্রাকৃতিক পণ্যের বিশ্বস্ত প্রতিষ্ঠান।</div></div></footer></div>

<!-- ================= MOBILE (below lg) — ported verbatim from product-details-mobile-code.html -->
<!-- Mobile PDP intentionally uses its own compact back-button app-bar (no shared
     banner/search/nav or bottom tab bar) — that is how the source mockup built it,
     a deliberate drill-down pattern distinct from the home page chrome, not an omission. -->
<div class="lg:hidden">
<header class="fixed top-0 w-full z-50 pt-safe bg-surface/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]"><div class="h-16 px-gutter-mobile flex items-center justify-between"><div class="flex items-center gap-space-sm"><button class="w-11 h-11 flex items-center justify-center text-on-surface hover:text-primary transition-colors active:scale-95" onclick="history.back()"><span class="material-symbols-outlined text-[24px]">arrow_back</span></button><h1 class="font-title-lg text-title-lg text-on-surface truncate max-w-[200px]">Product Details</h1></div><div class="flex items-center gap-1"><a class="w-11 h-11 relative flex items-center justify-center text-on-surface hover:text-primary transition-colors" data-path="cart" href="#"><span class="material-symbols-outlined text-[22px]">shopping_bag</span><span class="absolute top-2 right-2 w-4 h-4 bg-primary-container text-on-primary-container font-label-sm text-[10px] rounded-full flex items-center justify-center font-bold">2</span></a><div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center ml-1 flex-shrink-0 shadow-sm"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div></div></div></header><main class="flex flex-col relative w-full pt-16 pb-safe bg-surface min-h-screen"><div class="flex flex-col w-full pb-28">
<!-- 1. Breadcrumb -->
<nav aria-label="Breadcrumb" class="px-gutter-mobile py-space-sm">
<ol class="flex items-center gap-1.5 font-label-md text-label-md text-on-surface-variant flex-wrap">
<li>
<a class="hover:text-primary transition-colors" href="#">হোম</a>
</li>
<li class="flex items-center text-outline-variant">
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
</li>
<li>
<a class="hover:text-primary transition-colors" href="#">মধু</a>
</li>
<li class="flex items-center text-outline-variant">
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
</li>
<li aria-current="page" class="text-primary font-semibold truncate max-w-[140px]">
        খলিশা মধু
      </li>
</ol>
</nav>
<!-- 2. Product Media Gallery -->
<section class="relative w-full px-gutter-mobile pt-space-xs">
<div class="relative w-full aspect-[4/4] rounded-xl overflow-hidden bg-surface-container shadow-sm">
<div class="w-full h-full flex overflow-x-auto snap-x snap-mandatory scroll-smooth" id="gallery-slider">
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="Artisanal glass jar of Sundarbans Khalisha flower honey with thick golden amber honey dripping slowly from a wooden dipper, bathed in soft morning natural lighting with sunbeams, rustic parchment texture background and wild mangrove flowers." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBbupY9shsspsbbiyQR9xhgdtLcn7rsfatz74d28LrasxWz9C35Pcp3rrY_6XwYLwOSpVGjDohpnl_94sxqSgyp54zTktjCZt_orIWREHbq-dk1yEhKPtL0aIqueG6ybkiA1O8S99Oo_ZKWQsexDE0tEZkJoaKypdC_t2BogkXzTIJOHhiSELYrAWy1d1OqKuQwwQIYWT_Yx1xf5yisIx9cRv3ggRtDd7ik5SqFCjx6"/>
</div>
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="Close up Macro view of unfiltered raw Sundarbans honey in a luxury clear glass container showing micro pollen grains and air bubbles with golden backlight in a warm earthy studio setting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBMmSVxsh-UA9NgE4ZOH8v943v9shOjlNPmGMYeD4UePTWF5yqhoXXfQEj7rkeCuSXxGksGqyPPdvWf4uJfHa-XW_U1fTlqQS3mvROdiCNCFeJl211EzkHcgESAQ7SySYc756HGj7ypP_KZLyWDHNZ523PVcVYDi1VMe5w5lh21fLJJBvu3Nh4K6ZyjUCp9Mj7jNg3IWCV0CAcnGQs3k1WE0SRBeI9Xmau9-HJjH62e"/>
</div>
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="Traditional Bengali honey collector Mawali holding a freshly harvested wild honeycomb dripping rich translucent amber honey inside Sundarbans mangrove forest, documentary realistic photography." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjpkN7IFcSBK9Td4-PrgxpogQHEwknOLOgNPnMCksHCd1qUecrhsp9UKttuwNzGskKIA-ARSD0ZNqFGsF0-gvSrcoIEtGvp3ceC4695rse7QyILkheCISCj3eoGSTLZvz7A9auakBu-RnyuhgQ4boOPif0OMEyODlaZtTMqjkh5NsToCaM25xGz6NXP7EdjDWs52-BUPIe_NAMkAxu6SKKUnPd3J_2ljZ34DEjhyl4"/>
</div>
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="Glass honey jar presented with wild Khalisha blossoms, cinnamon sticks and fresh ginger on a handcrafted wooden tray, warm artisanal culinary aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCV4BuO9rDNeqvy_Nhxzou0lchIs6nlPsjJGfJyfwbbN61lYbT1HiE_2A8DgSbU3RAE8kr00aFm3NdKifN6rCfFmFA_vPINa8cCC4NCdo9YOFHc7oodZUMbInkFISPEOGukSvvbxAFPcTKaRT6B9Dx-rMOW_IkHyZ5MXhH--g9Q94m_QiDP8Y1LmBunUb7gvn-r99DatyHYz2AD76x9q93ptWlrfEjOS5AMVAvk05a7"/>
</div>
<div class="w-full h-full flex-shrink-0 snap-center relative">
<img class="w-full h-full object-cover" alt="Artisanal packaging box with laboratory test purity seal certificate, amber wax seal stamp and organic raw honey guarantee tag, warm studio shot." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBnaYJ_tApRKIwhMPScytF-GLO-wn-qZFlFaiF7ZnpLgfcQwA5g_YFzetfpyPkbWT_kcGE1Kv3FN-KF4Dla_X8wwpD0KqKlMa1kjp27nW7TOfeT1snCsp0B5lVJ9j1HTowc7pGet1711L7QRQ303rWdMBWJ3nx6_Aw-2W2swRsAP4GASSZfYvc_z6qs48iNQyu6pU9yhEvq3bb4P5u4zZJA1B8dDQg7gaon5R4qDp71"/>
</div>
</div>
<!-- Badge: Photo Counter -->
<div class="absolute bottom-3 left-3 bg-inverse-surface/85 backdrop-blur-md text-inverse-on-surface px-2.5 py-1 rounded-full font-label-sm text-label-sm tracking-widest shadow-sm flex items-center gap-1">
<span class="material-symbols-outlined text-[13px] text-primary-fixed">photo_camera</span>
<span id="slide-indicator">১ / ৫</span>
</div>
<!-- Badge: Provenance -->
<div class="absolute top-3 left-3 bg-surface-container-lowest/90 backdrop-blur-md text-primary font-label-sm text-label-sm px-2.5 py-1 rounded-full shadow-sm flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]">eco</span>
<span>১০০% খাঁটি ও কাঁচা</span>
</div>
<!-- Wishlist Heart Button -->
<button aria-label="উইশলিস্টে যোগ করুন" class="absolute top-3 right-3 w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-on-surface flex items-center justify-center shadow-md active:scale-90 transition-transform" id="wishlist-btn" onclick="toggleWishlist(this)">
<span class="material-symbols-outlined text-[20px] transition-colors">favorite</span>
</button>
</div>
<!-- Gallery Thumbnail Strip -->
<div class="flex items-center gap-2 mt-2.5 overflow-x-auto pb-1">
<button class="thumb-btn flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-surface-container-highest shadow-sm transition-all shadow-primary-container/20" onclick="goToSlide(0)">
<img class="w-full h-full object-cover" alt="Artisanal glass jar of Sundarbans Khalisha honey with dripping golden dipper macro thumbnail." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBg5MmKfkSx4FGXAZjvuB1XzKQYDaKh5nKz23vMQzAnTfg8aZpXoYVifyqQx89IadfNitTlqk3pbqXCAu5aqxtuiyag8neCT4C_aA3upQocNsqCdKBRZOaTE9KUZcA32vc7VRUSfTtbOVaMiOQjBRLoG_-GsxWTQqzKws8ZDL5QM5_CMru7d3ramB1kjHyHgcYKisZgq1wy8AwJ4AmTnuGZpK-LetsM_1EMDYRCM7Pl"/>
</button>
<button class="thumb-btn flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-surface-container-highest opacity-70 transition-all" onclick="goToSlide(1)">
<img class="w-full h-full object-cover" alt="Golden honey texture macro with natural pollen grains thumbnail." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhQ9-4uv1ro52SN2y2NE5p9zcBOV8m21NaQ91CM05pBiQBwVCaMzfOMIOJ7vOQUv7AJZfOOEBtNKfQQSpaBDb5rP5nwqa9EtxYTdOOEkuhgK7f6BsK4AkGn-J9Eew95f2-atDtS1NiTs0npo6ekE_v7Ompwn6Yo1nJJGHAGu3c0ghaRkxpYYcPAZCiJrEsyZU96WqZKo72MvedZkfWnaqvirKJvNiyRQfsmQvwHrzL"/>
</button>
<button class="thumb-btn flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-surface-container-highest opacity-70 transition-all" onclick="goToSlide(2)">
<img class="w-full h-full object-cover" alt="Mawali beekeeper harvest Sundarbans forest thumbnail." src="https://lh3.googleusercontent.com/aida-public/AB6AXuArXglMTj2rs_mWVY0A-L6mQ1JJP4dXdjLaAMDKyPVyB8JVF2XmG3r2E-4UUAZKYzyeeQ2dkL54VIIeVTqfDtTf0Yj_sSUGFOy-Bpked8b69Lfsb3bHg9ChoC8wMBYIJRmJOmUnQQTipRUMmNx8WYaenZtdb_tgStA5HIU9tmhYUdLjGTC9BA83zhkZCWdOJwdZP_HsH2f1KwBrs6geo37wL5yK1BIMcrDlDNAeFHFz"/>
</button>
<button class="thumb-btn flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-surface-container-highest opacity-70 transition-all" onclick="goToSlide(3)">
<img class="w-full h-full object-cover" alt="Honey jar with wild blossoms thumbnail." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA0V2UfDJahCUc-Y6t334OX7wyBnOp2OXOBvU3dQ8LaBGzcu5W96fcW0yVh86Nt3kqOPfiQiR23Ad4VsDrNR5YTmoxPlu-Vmx1Rimlb68wH9b_RC1nhn3GyFqt1hZgoL9vIAJsHafK7ZULnYYu5_1OA_dADrHIG4yZ4Oj_BGLiJMxsoFJF0mHh727Xa5_66MmN2Jm58sa-6cJ4Pi15-lJYYah2xrKiyT08bKrWV0OC_"/>
</button>
<button class="thumb-btn flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-surface-container-highest opacity-70 transition-all" onclick="goToSlide(4)">
<img class="w-full h-full object-cover" alt="Lab tested pure organic certificate packaging thumbnail." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnb4aiSKXPOcehw0UiQl-JpafYvk_KX5HjImkM8S9UaqgFkB4qKiw_Dyv74O_V7_o5-SWmnFUyvs3-biIRcyvHdOEzECiiUEScSm9xYDYgiH_wg928_PKEtOkFudLn3dIq5XaRZAVwoMqLJwvBtWVwPObAiGr90Zpv64COFUhWeYSMNtxtClW3J8Bn1PIjEsSc8DicVp1HzJWddS-SamidSN_6sPvqu9D_q8vIYZo4"/>
</button>
</div>
</section>
<!-- 3. Title, Rating & Trust Metadata -->
<section class="px-gutter-mobile pt-space-md">
<div class="flex items-center justify-between gap-2 mb-1.5">
<div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">
<span class="material-symbols-outlined text-[13px]">local_florist</span>
<span>মধুবন খাঁটি মধু</span>
</div>
<span class="inline-flex items-center gap-1 font-label-sm text-label-sm px-2.5 py-0.5 rounded-full bg-[#e8f5e9] text-[#1b5e20] font-semibold">
<span class="w-1.5 h-1.5 rounded-full bg-[#2e7d32] animate-pulse"></span>
        ইন স্টক
      </span>
</div>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight tracking-tight">
      সুন্দরবনের খলিশা মধু
    </h2>
<div class="flex items-center gap-2 mt-2 flex-wrap">
<div class="flex items-center gap-1 bg-surface-container-high px-2 py-0.5 rounded-lg">
<span class="material-symbols-outlined text-primary text-[17px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-title-md text-title-md text-on-surface">৪.৮</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(১২৮ রিভিউ)</span>
</div>
<span class="flex items-center gap-1 text-primary font-label-sm text-label-sm bg-primary-fixed/40 px-2 py-0.5 rounded-full">
<span class="material-symbols-outlined text-[14px]">verified</span>
        ল্যাব টেস্ট সার্টিফাইড
      </span>
</div>
</section>
<!-- 4. Dynamic Price Display -->
<section class="px-gutter-mobile pt-space-md">
<div class="p-space-md rounded-xl bg-surface-container-low shadow-sm flex items-center justify-between">
<div class="flex items-baseline gap-2.5">
<span class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary" id="current-price">৳৭৫০</span>
<span class="font-title-md text-title-md line-through text-outline" id="regular-price">৳৮৫০</span>
</div>
<div class="flex flex-col items-end">
<span class="px-2.5 py-1 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm font-bold shadow-sm" id="discount-badge">
          ১২% ছাড়
        </span>
<span class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">ভ্যাট অন্তর্ভুক্ত</span>
</div>
</div>
</section>
<!-- 5. Weight / Variant Selector -->
<section class="px-gutter-mobile pt-space-md">
<div class="flex items-center justify-between mb-space-sm">
<label class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-1">
<span>ওজন নির্বাচন করুন</span>
<span class="text-error font-body-sm">*</span>
</label>
<span class="font-label-md text-label-md text-primary font-semibold" id="selected-weight-label">৫০০ গ্রাম</span>
</div>
<div class="grid grid-cols-2 gap-2.5">
<!-- Variant 250g -->
<button class="variant-pill min-h-[48px] px-3 py-2.5 rounded-xl bg-surface-container-lowest text-on-surface shadow-sm text-left flex items-center justify-between active:scale-[0.98] transition-all" onclick="selectVariant(this, '২৫০ গ্রাম', 420, 480, '১২%')" type="button">
<div class="flex flex-col">
<span class="font-label-md text-label-md font-semibold">২৫০ গ্রাম</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">৳৪২০</span>
</div>
<span class="check-icon hidden material-symbols-outlined text-primary text-[18px]">check_circle</span>
</button>
<!-- Variant 500g (SELECTED DEFAULT) -->
<button class="variant-pill selected min-h-[48px] px-3 py-2.5 rounded-xl bg-primary text-on-primary shadow-md text-left flex items-center justify-between active:scale-[0.98] transition-all" onclick="selectVariant(this, '৫০০ গ্রাম', 750, 850, '১২%')" type="button">
<div class="flex flex-col">
<span class="font-label-md text-label-md font-semibold text-on-primary">৫০০ গ্রাম</span>
<span class="font-body-sm text-body-sm text-primary-fixed">৳৭৫০</span>
</div>
<span class="check-icon material-symbols-outlined text-on-primary text-[18px]">check_circle</span>
</button>
<!-- Variant 1kg -->
<button class="variant-pill min-h-[48px] px-3 py-2.5 rounded-xl bg-surface-container-lowest text-on-surface shadow-sm text-left flex items-center justify-between active:scale-[0.98] transition-all" onclick="selectVariant(this, '১ কেজি', 1450, 1650, '১২%')" type="button">
<div class="flex flex-col">
<span class="font-label-md text-label-md font-semibold">১ কেজি</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">৳১,৪৫০</span>
</div>
<span class="check-icon hidden material-symbols-outlined text-primary text-[18px]">check_circle</span>
</button>
<!-- Variant 2kg (Out of Stock) -->
<button class="min-h-[48px] px-3 py-2.5 rounded-xl bg-surface-dim/60 text-outline cursor-not-allowed text-left flex items-center justify-between opacity-70" disabled="" type="button">
<div class="flex flex-col">
<span class="font-label-md text-label-md line-through">২ কেজি</span>
<span class="font-body-sm text-body-sm text-error font-medium">স্টক আউট</span>
</div>
<span class="material-symbols-outlined text-outline text-[18px]">block</span>
</button>
</div>
</section>
<!-- 6. Quantity Selector -->
<section class="px-gutter-mobile pt-space-md">
<div class="flex items-center justify-between p-space-sm bg-surface-container-low rounded-xl">
<div class="flex flex-col pl-1">
<span class="font-title-md text-title-md text-on-surface">পরিমাণ</span>
<span class="font-label-sm text-label-sm text-on-surface-variant" id="qty-limit-note">সর্বোচ্চ ১০টি আইটেম</span>
</div>
<div class="flex items-center bg-surface-container-lowest rounded-full p-1 shadow-sm">
<button aria-label="পরিমাণ কমান" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface active:scale-90 hover:bg-surface-container transition-colors" onclick="changeQuantity(-1)" type="button">
<span class="material-symbols-outlined text-[20px]">remove</span>
</button>
<span class="w-10 text-center font-title-lg text-title-lg font-bold text-on-surface select-none" id="qty-value">১</span>
<button aria-label="পরিমাণ বাড়ান" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface active:scale-90 hover:bg-surface-container transition-colors" onclick="changeQuantity(1)" type="button">
<span class="material-symbols-outlined text-[20px]">add</span>
</button>
</div>
</div>
</section>
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
<section class="px-gutter-mobile pt-space-lg">
<div class="flex items-center justify-between mb-space-sm">
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">গ্রাহক রিভিউ</h3>
<button class="font-label-md text-label-md text-primary font-bold hover:underline flex items-center gap-0.5" type="button">
        সব দেখুন (১২৮)
        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</button>
</div>
<!-- Rating Summary Bento -->
<div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm mb-space-sm flex items-center gap-4">
<div class="flex flex-col items-center justify-center pr-3">
<span class="font-display-hero-mobile text-display-hero-mobile text-primary font-bold leading-none">৪.৮</span>
<div class="flex text-primary mt-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star_half</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant mt-1">১২৮ জন যাচাইকৃত ক্রেতা</span>
</div>
<!-- Rating Bars -->
<div class="flex-1 flex flex-col gap-1">
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm text-on-surface-variant w-2">৫</span>
<div class="flex-1 h-2 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full bg-primary-container rounded-full" style="width: 85%;"></div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant w-5">৮৫%</span>
</div>
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm text-on-surface-variant w-2">৪</span>
<div class="flex-1 h-2 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full bg-primary-container rounded-full" style="width: 10%;"></div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant w-5">১০%</span>
</div>
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm text-on-surface-variant w-2">৩</span>
<div class="flex-1 h-2 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full bg-primary-container rounded-full" style="width: ৩%;"></div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant w-5">৩%</span>
</div>
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm text-on-surface-variant w-2">২</span>
<div class="flex-1 h-2 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full bg-primary-container rounded-full" style="width: ১%;"></div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant w-5">১%</span>
</div>
<div class="flex items-center gap-2">
<span class="font-label-sm text-label-sm text-on-surface-variant w-2">১</span>
<div class="flex-1 h-2 rounded-full bg-surface-container-high overflow-hidden">
<div class="h-full bg-primary-container rounded-full" style="width: ১%;"></div>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant w-5">১%</span>
</div>
</div>
</div>
<!-- Review Card 1 -->
<div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm mb-2.5">
<div class="flex items-center justify-between mb-1.5">
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full bg-secondary text-on-secondary font-label-md flex items-center justify-center">
            রা
          </div>
<div>
<h4 class="font-title-md text-title-md text-on-surface leading-tight">রাশেদুল হাসান</h4>
<span class="font-label-sm text-label-sm text-[#2e7d32] flex items-center gap-0.5">
<span class="material-symbols-outlined text-[13px]">verified</span> যাচাইকৃত ক্রেতা
            </span>
</div>
</div>
<span class="font-label-sm text-label-sm text-outline">৩ দিন আগে</span>
</div>
<div class="flex text-primary my-1">
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
        খলিশা মধুর আসল টেস্ট পেয়েছি। একদম ঘন নয়, স্বাভাবিক পাতলা ও সুন্দর মিষ্টি ঝাঁজ রয়েছে। প্যাকেজিং চমৎকার ছিল, এক ফোঁটাও লিক করেনি।
      </p>
<!-- Review Photo Attachment -->
<div class="mt-2.5 w-16 h-16 rounded-lg overflow-hidden bg-surface-container shadow-sm">
<img class="w-full h-full object-cover" alt="Customer photo of received honey jar unboxed on a dining table with original seal intact." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCAkLCZm-cjA6iN87WX2o0lJbBhBFEEfzu7X9aYZxpUQh0uf1M01y8mgLDcV_HGcO46hWRCWGKFgm9yTe1WkYqFUMx2-xY6Yv0cKMpJiYWzlo6Caj7E32ESUAtvvIHo_mj-iHFCzsHFmVpv2oIUirGVwwyprrvsWZ7PC1thPeNKuJ0eOOsyi8ui4HYMpkv3NmAXSlRekEuB8za89PWkvMoZg40ikZpU_xPVA-lc07Bi"/>
</div>
</div>
<!-- Review Card 2 -->
<div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm">
<div class="flex items-center justify-between mb-1.5">
<div class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full bg-tertiary text-on-tertiary font-label-md flex items-center justify-center">
            তা
          </div>
<div>
<h4 class="font-title-md text-title-md text-on-surface leading-tight">তানজিলা আহমেদ</h4>
<span class="font-label-sm text-label-sm text-[#2e7d32] flex items-center gap-0.5">
<span class="material-symbols-outlined text-[13px]">verified</span> যাচাইকৃত ক্রেতা
            </span>
</div>
</div>
<span class="font-label-sm text-label-sm text-outline">১ সপ্তাহ আগে</span>
</div>
<div class="flex text-primary my-1">
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
        বাচ্চাদের কাশির জন্য নিয়েছিলাম। নিয়মিত দুই চামচ কুসুম গরম পানিতে দিয়েছি, খুব ভালো কাজ করেছে। খাঁটি জিনিসের বিকল্প নেই।
      </p>
</div>
<!-- Write Review Action Button -->
<div class="mt-space-sm">
<button class="w-full min-h-[46px] rounded-full bg-surface-container text-on-surface font-label-md text-label-md font-semibold flex items-center justify-center gap-2 active:scale-95 transition-transform" type="button">
<span class="material-symbols-outlined text-[18px]">rate_review</span>
        আপনার রিভিউ লিখুন
      </button>
</div>
</section>
<!-- 10. Related Products Carousel / Grid -->
<section class="px-gutter-mobile pt-space-lg">
<div class="flex items-center justify-between mb-space-sm">
<div>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">সম্পর্কিত পণ্য</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">একই প্রাকৃতিক উৎস থেকে সংগৃহীত</p>
</div>
<a class="font-label-md text-label-md text-primary font-bold hover:underline" href="#">সবগুলো</a>
</div>
<div class="grid grid-cols-2 gap-3">
<!-- Related Product 1 -->
<div class="flex flex-col bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm p-2.5 group">
<div class="relative w-full aspect-square rounded-lg overflow-hidden bg-surface-container mb-2">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Artisanal glass jar of bright golden yellow natural Mustard flower honey Sorisha Modhu with yellow flowers in soft warm studio lighting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC3OHSBSXUs4G3fUJ3JeuVyfIdlWhhzaoAyMDGhPcQ3lFEljt3oVrWeCG3uAAiOCBPDRkpU4qkIJGmA1uBHDHS9ZIkbTWQxARnyOfW5Dh4_E8PeaatMqUjN_1aXU-OBXUgivtz9LZUB97P896kwOreTux_t5_WBvauH3gx9a6EnjsDOM-yivjUNp5HCbVYHgviTWB95W3yqo8oKZxR3nL9q8Hw-BP_n3twm3jayFdLS"/>
<span class="absolute top-1.5 left-1.5 bg-primary-container text-on-primary-container font-label-sm text-[10px] px-2 py-0.5 rounded-full font-bold">
            ১০% ছাড়
          </span>
</div>
<span class="font-label-sm text-[11px] text-primary truncate">মধুবন কালেকশন</span>
<h4 class="font-title-md text-title-md text-on-surface font-semibold truncate mt-0.5">সরিষা ফুলের মধু</h4>
<div class="flex items-center gap-1.5 mt-1">
<span class="font-title-md text-title-md text-primary font-bold">৳৫৮০</span>
<span class="font-body-sm text-body-sm line-through text-outline">৳৬৫০</span>
</div>
<button class="mt-2 w-full min-h-[38px] rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-semibold flex items-center justify-center gap-1 hover:bg-primary-container hover:text-on-primary-container active:scale-95 transition-all" type="button">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
          যোগ করুন
        </button>
</div>
<!-- Related Product 2 -->
<div class="flex flex-col bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm p-2.5 group">
<div class="relative w-full aspect-square rounded-lg overflow-hidden bg-surface-container mb-2">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Artisanal wooden bowl filled with roasted crispy whole premium cashew nuts with honey glaze in warm rustic aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuA83LchKm40KhNeqRDySLH8u7pQMkoxwadRgVcaKuGPT1BcNX3-OFHnCf4TE9ZsQb2ZduhcjoQKkcQE9jsk8ObBatLb_zHVNyfdQZbaJQEZDZXhBSFvWgi374oRiM2GzODRpWcOekHbivb0b-HE0e6TEzit65zZWB07Fk9gaMMaCZop0HeDrlEa8TNW_-b6Q9_CVwKYTh_6P3612v-mQlFPHph2ZFlufgZ8v4R4W1_V"/>
<span class="absolute top-1.5 left-1.5 bg-secondary-container text-on-secondary-fixed font-label-sm text-[10px] px-2 py-0.5 rounded-full font-bold">
            নতুন
          </span>
</div>
<span class="font-label-sm text-[11px] text-primary truncate">ড্রাই ফ্রুটস</span>
<h4 class="font-title-md text-title-md text-on-surface font-semibold truncate mt-0.5">প্রিমিয়াম কাজুবাদাম</h4>
<div class="flex items-center gap-1.5 mt-1">
<span class="font-title-md text-title-md text-primary font-bold">৳৪৫০</span>
<span class="font-body-sm text-body-sm line-through text-outline">৳৫০০</span>
</div>
<button class="mt-2 w-full min-h-[38px] rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-semibold flex items-center justify-center gap-1 hover:bg-primary-container hover:text-on-primary-container active:scale-95 transition-all" type="button">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
          যোগ করুন
        </button>
</div>
</div>
</section>
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
<!-- 12. STICKY BOTTOM PURCHASE BAR -->
<aside aria-label="Quick Purchase Bar" class="fixed bottom-0 left-0 right-0 z-40 bg-surface/90 backdrop-blur-xl shadow-[0_-4px_24px_rgba(56,39,25,0.08)] pb-safe">
<div class="max-w-[430px] mx-auto px-gutter-mobile py-2.5 flex items-center justify-between gap-3">
<!-- Price & Selected Weight Information -->
<div class="flex flex-col min-w-0 pr-1">
<span class="font-label-sm text-[11px] text-on-surface-variant leading-none">মোট মূল্য</span>
<div class="flex items-baseline gap-1 mt-0.5">
<span class="font-title-lg text-title-lg text-primary font-bold leading-tight" id="bar-price">৳৭৫০</span>
</div>
<span class="font-label-sm text-[11px] text-outline truncate font-medium" id="bar-variant">৫০০ গ্রাম (১টি)</span>
</div>
<!-- Action Buttons -->
<div class="flex items-center gap-2 flex-1 justify-end">
<!-- Add to Cart (Secondary Full Pill) -->
<button class="min-h-[46px] px-3.5 py-2 rounded-full bg-surface-container-highest text-on-surface hover:text-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-sm active:scale-95 transition-all" onclick="addToCartAnimation(this)" type="button">
<span class="material-symbols-outlined text-[19px]">add_shopping_cart</span>
<span class="whitespace-nowrap">কার্টে যোগ</span>
</button>
<!-- Buy Now (Primary CTA Pill) -->
<button class="min-h-[46px] px-4 py-2 rounded-full bg-gradient-to-r from-primary-container to-primary text-on-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1 shadow-md hover:shadow-lg active:scale-95 transition-all flex-shrink-0" onclick="buyNowAction(this)" type="button">
<span class="material-symbols-outlined text-[18px]">bolt</span>
<span class="whitespace-nowrap">এখনই কিনুন</span>
</button>
</div>
</div>
</aside>
</div>
<script>
  // State
  let currentQuantity = 1;
  let currentWeight = '৫০০ গ্রাম';
  let unitPrice = 750;
  let regularUnitPrice = 850;

  // Bengali Number Conversion
  const bnDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
  function toBengaliNum(num) {
    return num.toString().replace(/[0-9]/g, d => bnDigits[d]);
  }

  // Update Dynamic Prices
  function updatePrices() {
    const total = unitPrice * currentQuantity;
    const regTotal = regularUnitPrice * currentQuantity;
    const formatted = '৳' + toBengaliNum(total.toLocaleString('en-US'));
    const formattedReg = '৳' + toBengaliNum(regTotal.toLocaleString('en-US'));
    
    document.getElementById('current-price').innerText = formatted;
    document.getElementById('regular-price').innerText = formattedReg;
    document.getElementById('bar-price').innerText = formatted;
    document.getElementById('bar-variant').innerText = currentWeight + ' (' + toBengaliNum(currentQuantity) + 'টি)';
  }

  // Variant Pill Selection
  function selectVariant(button, weight, price, regPrice, discount) {
    document.querySelectorAll('.variant-pill').forEach(btn => {
      btn.classList.remove('selected', 'bg-primary', 'text-on-primary', 'shadow-md');
      btn.classList.add('bg-surface-container-lowest', 'text-on-surface', 'shadow-sm');
      const icon = btn.querySelector('.check-icon');
      if (icon) icon.classList.add('hidden');
      const spanW = btn.querySelector('span.font-label-md');
      if (spanW) spanW.classList.remove('text-on-primary');
      const spanP = btn.querySelector('span.font-body-sm');
      if (spanP) spanP.classList.remove('text-primary-fixed');
    });

    button.classList.add('selected', 'bg-primary', 'text-on-primary', 'shadow-md');
    button.classList.remove('bg-surface-container-lowest', 'text-on-surface', 'shadow-sm');
    const activeIcon = button.querySelector('.check-icon');
    if (activeIcon) activeIcon.classList.remove('hidden');
    const activeSpanW = button.querySelector('span.font-label-md');
    if (activeSpanW) activeSpanW.classList.add('text-on-primary');
    const activeSpanP = button.querySelector('span.font-body-sm');
    if (activeSpanP) activeSpanP.classList.add('text-primary-fixed');

    currentWeight = weight;
    unitPrice = price;
    regularUnitPrice = regPrice;
    document.getElementById('selected-weight-label').innerText = weight;
    document.getElementById('discount-badge').innerText = discount + ' ছাড়';

    updatePrices();
  }

  // Quantity Stepper
  function changeQuantity(delta) {
    const newQty = currentQuantity + delta;
    if (newQty >= 1 && newQty <= 10) {
      currentQuantity = newQty;
      document.getElementById('qty-value').innerText = toBengaliNum(currentQuantity);
      updatePrices();
    }
  }

  // Gallery Navigation
  const slider = document.getElementById('gallery-slider');
  const indicator = document.getElementById('slide-indicator');
  const thumbs = document.querySelectorAll('.thumb-btn');

  function goToSlide(index) {
    if (!slider) return;
    const width = slider.clientWidth;
    slider.scrollTo({ left: width * index, behavior: 'smooth' });
    updateSliderUI(index);
  }

  function updateSliderUI(index) {
    indicator.innerText = toBengaliNum(index + 1) + ' / ৫';
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

  // Wishlist Toggle
  function toggleWishlist(btn) {
    const icon = btn.querySelector('span');
    if (btn.classList.contains('active-wish')) {
      btn.classList.remove('active-wish');
      icon.style.fontVariationSettings = "'FILL' 0";
      icon.classList.remove('text-error');
    } else {
      btn.classList.add('active-wish');
      icon.style.fontVariationSettings = "'FILL' 1";
      icon.classList.add('text-error');
    }
  }

  // Add to Cart Micro-interaction
  function addToCartAnimation(btn) {
    const originalText = btn.innerHTML;
    btn.innerHTML = `<span class="material-symbols-outlined text-[19px] text-[#2e7d32]">check_circle</span> <span>যোগ হয়েছে!</span>`;
    btn.disabled = true;
    setTimeout(() => {
      btn.innerHTML = originalText;
      btn.disabled = false;
    }, 1500);
  }

  function buyNowAction(btn) {
    btn.classList.add('scale-95');
    setTimeout(() => btn.classList.remove('scale-95'), 150);
  }
</script></main></div>
@endsection
