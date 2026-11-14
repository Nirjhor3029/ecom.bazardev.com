@extends('storefront.layout')

@section('title', 'আপনার শপিং কার্ট — মধুবন')

@php
    use App\Modules\Core\Support\Numerals as N;
    $cart->items->load('variant.inventoryItem', 'variant.product.media.media', 'variant.product.category');
    $items = $cart->items;
    $count = $items->count();
    $units = $items->sum('qty');
    $fmt = fn ($paisa) => '৳'.number_format($paisa / 100, 0);
    $weightLabel = fn ($v) => $v->weight_grams ? ($v->weight_grams >= 1000 ? N::bn(rtrim(rtrim(number_format($v->weight_grams / 1000, 1), '0'), '.')).' কেজি' : N::bn($v->weight_grams).' গ্রাম') : ($v->sku ?? '');
    $avail = fn ($item) => $item->variant->inventoryItem ? (int) $item->variant->inventoryItem->available() : 99;
    $over = $items->filter(fn ($i) => $i->variant->inventoryItem?->track_stock && $i->qty > $avail($i));
    $changed = $items->filter(fn ($i) => $i->variant->effectivePrice() !== $i->unit_price_snapshot);
    $payable = $priced['subtotal'] - $priced['discount_total'];
    $freeOver = $delivery['free_over'] ?? null;
    $deliveryFee = $delivery['fee'] ?? 0;
    $savings = $items->sum(fn ($i) => max(0, $i->variant->price - $i->variant->effectivePrice()) * $i->qty) + $priced['discount_total'];
    $couponLabel = $coupon ? ($coupon->type === 'percent' ? N::bn($coupon->value).'% ছাড়' : ($coupon->type === 'fixed' ? $fmt($coupon->value).' ছাড়' : 'ফ্রি ডেলিভারি')) : null;
@endphp

@section('content')
<div class="w-full">
<!-- Header -->
<section class="w-full bg-surface-container-low/60 pb-space-md pt-space-sm">
<div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin">
<nav class="flex items-center gap-2 font-label-md text-label-md text-secondary mb-space-sm">
<a class="hover:text-primary transition-colors flex items-center gap-1.5" href="{{ route('home') }}"><span class="material-symbols-outlined text-[16px]">home</span><span>হোম</span></a>
<span class="text-outline/40">/</span>
<a class="hover:text-primary transition-colors" href="{{ route('products.index') }}">শপ</a>
<span class="text-outline/40">/</span>
<span class="text-primary font-bold">শপিং কার্ট<span class="hidden sm:inline"> (Shopping Cart)</span></span>
</nav>
<div class="flex flex-col md:flex-row md:items-end justify-between gap-space-sm">
<div>
@if ($count)<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed-variant font-label-sm text-label-sm font-bold mb-2"><span class="material-symbols-outlined text-[16px] text-primary">local_mall</span><span>{{ N::bn($count) }}টি প্রিমিয়াম পণ্য নির্বাচিত</span></div>@endif
<h1 class="font-headline-md lg:font-headline-lg text-headline-md lg:text-headline-lg text-on-surface tracking-tight">আপনার শপিং কার্ট <span class="font-body-lg text-body-lg text-secondary font-normal hidden sm:inline">(Your Shopping Cart)</span></h1>
<p class="font-body-sm lg:font-body-md text-body-sm lg:text-body-md text-on-surface-variant mt-1.5 max-w-2xl">আপনার পছন্দের প্রাকৃতিক পণ্যগুলো একবার দেখে নিন এবং নিশ্চিন্তে অর্ডার সম্পন্ন করুন। সরাসরি মৌয়াল ও কৃষক পরিবার থেকে সংগৃহীত।</p>
</div>
<div class="hidden sm:flex items-center gap-2 bg-surface rounded-full p-1.5 shadow-sm self-start md:self-auto">
<div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary text-on-primary font-label-md text-label-md font-bold"><span class="w-5 h-5 rounded-full bg-surface text-primary flex items-center justify-center text-xs">১</span><span>কার্ট</span></div>
<span class="material-symbols-outlined text-outline/40 text-[16px]">arrow_forward</span>
<div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-secondary font-label-md text-label-md"><span class="w-5 h-5 rounded-full bg-surface-container-high text-secondary flex items-center justify-center text-xs">২</span><span>ডেলিভারি তথ্য</span></div>
<span class="material-symbols-outlined text-outline/40 text-[16px]">arrow_forward</span>
<div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-secondary font-label-md text-label-md"><span class="w-5 h-5 rounded-full bg-surface-container-high text-secondary flex items-center justify-center text-xs">৩</span><span>নিশ্চিতকরণ</span></div>
</div>
</div>
@if ($count)
<div class="mt-space-sm p-3 rounded-xl bg-secondary-container/70 text-on-secondary-container font-body-sm text-body-sm flex items-start sm:items-center gap-3 shadow-sm">
<span class="material-symbols-outlined text-primary text-[22px] shrink-0">info</span>
<div class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-2"><span><strong>সতর্কবার্তা:</strong> কার্টে পণ্য যোগ করলে স্টক স্বয়ংক্রিয়ভাবে সংরক্ষিত (Reserve) হয় না। সীমিত স্টক নিশ্চিত করতে দ্রুত চেকআউট সম্পন্ন করুন।</span><span class="hidden sm:inline font-label-sm text-label-sm uppercase tracking-wider text-primary font-bold shrink-0 bg-surface/80 px-2.5 py-1 rounded-md">লাইভ ইনভেন্টরি</span></div>
</div>
@endif
</div>
</section>

<div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin py-space-md pb-28 lg:pb-space-xl">
@if ($undo)
<div class="mb-space-sm p-4 rounded-xl bg-inverse-surface text-inverse-on-surface shadow-sm flex items-center justify-between gap-4">
<div class="flex items-center gap-3 min-w-0"><span class="w-8 h-8 rounded-full bg-surface/15 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[18px]">delete_sweep</span></span><p class="font-body-md text-body-md truncate">একটি আইটেম <strong class="text-primary-fixed">“{{ $undo['name'] }}”</strong> কার্ট থেকে সরানো হয়েছে।</p></div>
<form action="{{ route('cart.items.store') }}" method="POST" class="shrink-0">@csrf<input type="hidden" name="variant_id" value="{{ $undo['variant_id'] }}"><input type="hidden" name="qty" value="{{ $undo['qty'] }}"><button class="px-3 py-1.5 rounded-full bg-surface-container-lowest text-primary hover:bg-primary hover:text-on-primary font-label-md text-label-md font-bold transition-all flex items-center gap-1 shadow-sm"><span class="material-symbols-outlined text-[16px]">undo</span><span class="hidden sm:inline">আগের অবস্থায় ফিরিয়ে আনুন (Undo)</span><span class="sm:hidden">Undo</span></button></form>
</div>
@endif

@if ($count === 0)
<div class="bg-surface-container-low rounded-2xl p-space-xl flex flex-col items-center text-center gap-3 shadow-sm">
<div class="w-20 h-20 rounded-full bg-surface-container-highest flex items-center justify-center text-outline"><span class="material-symbols-outlined text-[40px]">shopping_cart_off</span></div>
<h2 class="font-headline-sm text-headline-sm text-on-surface">আপনার কার্ট এখনো খালি!</h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-md">আপনার পছন্দের প্রাকৃতিক খাঁটি মধু ও সুস্বাদু পাহাড়ি বাদাম সংগ্রহ দেখতে এখনই কেনাকাটা শুরু করুন।</p>
<a href="{{ route('products.index') }}" class="mt-2 px-6 py-2.5 rounded-full bg-primary text-on-primary font-label-md text-label-md font-bold shadow-md hover:bg-secondary transition-colors flex items-center gap-2">এখনই কেনাকাটা করুন <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
<!-- LEFT -->
<div class="lg:col-span-8 flex flex-col gap-space-md min-w-0">
@if ($errors->has('cart') || $over->isNotEmpty())
<div class="p-3.5 rounded-xl bg-error-container text-on-error-container font-body-sm text-body-sm flex items-center gap-3"><span class="material-symbols-outlined text-[20px]">error</span><p>@if ($errors->has('cart')){{ $errors->first('cart') }}@else কিছু পণ্যের স্টক আপনার নির্বাচিত পরিমাণের চেয়ে কম। চেকআউটের আগে পরিমাণ কমিয়ে নিন।@endif</p></div>
@endif
@foreach ($changed as $ci)
<div class="p-3.5 rounded-xl bg-surface-container text-on-surface-variant font-body-sm text-body-sm flex items-center gap-3"><span class="material-symbols-outlined text-primary text-[20px]">price_change</span><p><strong>বাজার দর হালনাগাদ:</strong> <span class="font-semibold text-on-surface">“{{ $ci->variant->product->name['bn'] ?? '' }}”</span>-এর মূল্য {{ $fmt($ci->unit_price_snapshot) }} থেকে {{ $fmt($ci->variant->effectivePrice()) }} নির্ধারণ করা হয়েছে।</p></div>
@endforeach

<div class="hidden sm:grid grid-cols-12 text-secondary font-label-sm text-label-sm uppercase tracking-wider px-6 py-2.5 bg-surface-container-low rounded-lg">
<div class="col-span-6">পণ্য বিবরণী (Product)</div><div class="col-span-2 text-center">একক মূল্য</div><div class="col-span-2 text-center">পরিমাণ</div><div class="col-span-2 text-right">মোট মূল্য</div>
</div>

<div class="flex flex-col gap-space-sm">
@foreach ($items as $item)
@php
    $v = $item->variant; $p = $v->product; $a = $avail($item);
    $unit = $v->effectivePrice(); $isOver = $v->inventoryItem?->track_stock && $item->qty > $a;
    $max = $v->inventoryItem?->track_stock ? max(min($a, 10), 1) : 10;
    $batch = ($p->content ?? [])['batch'] ?? null;
@endphp
<div class="p-4 sm:p-5 rounded-2xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-shadow relative">
<div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
<div class="sm:col-span-6 flex items-start gap-4 min-w-0">
<a href="{{ route('products.show', $p->slug) }}" class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden shrink-0 bg-surface-container block">
@if ($img = $p->primaryImageUrl())<img class="w-full h-full object-cover" alt="{{ $p->name['bn'] ?? '' }}" src="{{ $img }}" loading="lazy">@else<div class="w-full h-full flex items-center justify-center text-3xl">🍯</div>@endif
@if ($p->category)<span class="absolute top-1 left-1 bg-surface-container-lowest/90 px-1.5 py-0.5 rounded text-[10px] font-bold text-primary">{{ $p->category->name['bn'] ?? '' }}</span>@endif
</a>
<div class="flex flex-col min-w-0 flex-1">
<div class="flex items-center gap-2 mb-1 flex-wrap">
<span class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed font-label-sm text-label-sm">{{ $weightLabel($v) }}</span>
@if ($isOver)<span class="px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-[11px] font-bold">স্টকে আছে মাত্র {{ N::bn($a) }}টি</span>
@elseif ($v->inventoryItem?->track_stock && $a <= 5)<span class="px-2 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-[11px] font-bold">মাত্র {{ N::bn($a) }}টি বাকি</span>
@else<span class="text-xs font-semibold text-secondary flex items-center gap-0.5"><span class="material-symbols-outlined text-[14px] text-primary">check_circle</span> ইন-স্টক</span>@endif
@if ($v->effectivePrice() < $v->price)<span class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-[11px] font-bold">{{ N::bn((int) round((1 - $v->effectivePrice() / $v->price) * 100)) }}% ছাড়</span>@endif
</div>
<h3 class="font-title-lg text-title-lg text-on-surface truncate"><a href="{{ route('products.show', $p->slug) }}">{{ $p->name['bn'] ?? '' }}</a></h3>
<span class="font-body-sm text-body-sm text-secondary truncate">{{ $p->short_desc['bn'] ?? '' }}</span>
<div class="flex items-center gap-2 mt-2 sm:hidden"><span class="font-bold text-on-surface">{{ $fmt($unit) }}</span>@if ($v->effectivePrice() < $v->price)<span class="text-outline line-through text-sm">{{ $fmt($v->price) }}</span>@endif</div>
</div>
</div>
<div class="hidden sm:flex sm:col-span-2 flex-col items-center justify-center">
<span class="font-title-md text-title-md text-on-surface">{{ $fmt($unit) }}</span>
@if ($v->effectivePrice() < $v->price)<span class="font-label-sm text-label-sm text-outline line-through">{{ $fmt($v->price) }}</span>@else<span class="font-label-sm text-label-sm text-outline">প্রতি পিস</span>@endif
</div>
<div class="sm:col-span-2 flex items-center justify-between sm:justify-center">
<span class="sm:hidden font-body-sm text-body-sm text-secondary">পরিমাণ:</span>
<form action="{{ route('cart.items.update', $item->id) }}" method="POST" class="inline-flex items-center bg-surface-container-low rounded-full p-1 shadow-inner" data-max="{{ $max }}">
@csrf @method('PATCH')
<input type="hidden" name="qty" value="{{ $item->qty }}">
<button type="button" aria-label="কমান" onclick="cartStep(this,-1)" class="w-7 h-7 rounded-full bg-surface-container-lowest hover:bg-primary hover:text-on-primary text-on-surface flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[16px]">remove</span></button>
<span class="w-8 text-center font-bold text-on-surface text-sm">{{ N::bn($item->qty) }}</span>
<button type="button" aria-label="বাড়ান" onclick="cartStep(this,1)" class="w-7 h-7 rounded-full bg-surface-container-lowest hover:bg-primary hover:text-on-primary text-on-surface flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[16px]">add</span></button>
</form>
</div>
<div class="sm:col-span-2 flex sm:flex-col items-center sm:items-end justify-between">
<span class="sm:hidden font-body-sm text-body-sm text-secondary">উপমোট:</span>
<span class="font-headline-sm text-headline-sm text-primary font-bold">{{ $fmt($unit * $item->qty) }}</span>
<span class="hidden sm:block font-label-sm text-label-sm text-secondary">{{ N::bn($item->qty) }} × {{ $fmt($unit) }}</span>
</div>
</div>
<div class="mt-4 flex items-center justify-between text-secondary bg-surface-container-low/40 rounded-xl px-3 sm:px-4 py-2 gap-2">
<div class="hidden sm:flex items-center gap-1.5 text-xs text-outline min-w-0"><span class="material-symbols-outlined text-[15px] text-primary">eco</span><span class="truncate">@if ($batch)ল্যাব রিপোর্ট ব্যাচ: {{ $batch }}@else১০০% প্রাকৃতিক ও ল্যাব টেস্টেড@endif</span></div>
<div class="flex items-center gap-3 sm:gap-4 ml-auto">
<form action="{{ route('cart.items.save-later', $item->id) }}" method="POST">@csrf<button class="font-label-md text-label-md text-secondary hover:text-primary transition-colors flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">bookmark</span><span>পরে কিনুন<span class="hidden sm:inline"> (Save for later)</span></span></button></form>
<span class="text-outline/30">|</span>
<form action="{{ route('cart.items.destroy', $item->id) }}" method="POST">@csrf @method('DELETE')<button class="font-label-md text-label-md text-error hover:opacity-80 transition-opacity flex items-center gap-1 font-semibold"><span class="material-symbols-outlined text-[16px]">delete</span><span>মুছে ফেলুন<span class="hidden sm:inline"> (Remove)</span></span></button></form>
</div>
</div>
</div>
@endforeach
</div>

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-surface-container-low">
<a class="inline-flex items-center gap-2 font-label-md text-label-md text-secondary hover:text-primary transition-colors font-bold" href="{{ route('products.index') }}"><span class="material-symbols-outlined text-[18px]">keyboard_backspace</span><span>আরও পণ্য পছন্দ করুন (Continue Shopping)</span></a>
<div class="flex items-center gap-3">
<a href="{{ route('cart.show') }}" class="px-5 py-2.5 rounded-full bg-surface-container-lowest text-on-surface hover:bg-surface-container-high transition-colors font-label-md text-label-md font-semibold shadow-sm flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px]">refresh</span><span>কার্ট আপডেট করুন</span></a>
<form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('কার্টের সব পণ্য মুছে ফেলবেন?')">@csrf @method('DELETE')<button class="px-4 py-2.5 rounded-full text-secondary hover:text-error hover:bg-error-container/40 transition-colors font-label-md text-label-md">সব খালি করুন</button></form>
</div>
</div>

<!-- Coupon -->
<div class="p-5 lg:p-6 rounded-2xl bg-surface-container-lowest shadow-sm">
<div class="flex items-center justify-between mb-4">
<div class="flex items-center gap-2.5"><div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary"><span class="material-symbols-outlined text-[18px]">sell</span></div><h3 class="font-title-lg text-title-lg text-on-surface">কুপন বা ডিসকাউন্ট ভাউচার</h3></div>
@if ($couponApplied)<span class="font-label-sm text-label-sm text-secondary bg-surface-container px-2.5 py-1 rounded-full">১টি প্রযোজ্য</span>@endif
</div>
<form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
@csrf
<div class="relative flex-1">
<input name="code" value="{{ old('code', $cart->coupon_code) }}" placeholder="কুপন কোড লিখুন (যেমন: HONEY10)" autocomplete="off" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-title-md text-title-md text-on-surface uppercase tracking-wider font-bold focus:outline-none focus:ring-2 focus:ring-primary shadow-inner">
@if ($couponApplied)<span class="absolute right-3 top-3 text-primary flex items-center"><span class="material-symbols-outlined text-[20px]">check_circle</span></span>@endif
</div>
<button class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-on-surface transition-colors shadow-sm shrink-0">{{ $couponApplied ? 'প্রয়োগ করা হয়েছে' : 'প্রয়োগ করুন' }}</button>
</form>
@error('coupon')<p class="mt-3 p-3 rounded-xl bg-error-container text-on-error-container font-body-sm text-body-sm flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">error</span>{{ $message }}</p>@enderror
@if ($couponApplied)
<div class="mt-4 p-4 rounded-xl bg-primary-fixed/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
<div class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">verified</span><div><p class="font-label-md text-label-md text-on-surface font-bold">“{{ $cart->coupon_code }}” কোড সফলভাবে প্রয়োগ করা হয়েছে!@if ($couponLabel) ({{ $couponLabel }})@endif</p><p class="font-body-sm text-body-sm text-secondary mt-0.5">আপনার মোট ক্রয়ের উপর {{ $fmt($priced['discount_total']) }} সংরক্ষিত হয়েছে।</p></div></div>
<form action="{{ route('cart.coupon.remove') }}" method="POST" class="self-end sm:self-center">@csrf @method('DELETE')<button class="font-label-md text-label-md text-error hover:underline font-bold">[কুপন সরান (Remove)]</button></form>
</div>
@elseif ($cart->coupon_code)
<p class="mt-3 p-3 rounded-xl bg-surface-container font-body-sm text-body-sm text-on-surface-variant flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">info</span>“{{ $cart->coupon_code }}” কুপনটি এখন প্রযোজ্য নয়@if ($coupon && $coupon->min_cart) — নূন্যতম অর্ডার {{ $fmt($coupon->min_cart) }}@endif।</p>
@endif
<div class="mt-3 pt-3 flex items-center justify-between text-xs text-outline gap-2 flex-wrap"><span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">help</span>কুপন কোড কাজ না করলে আমাদের হটলাইনে যোগাযোগ করুন: ০১৭১২-৩৪৫৬৭৮</span>@if ($coupon && $coupon->min_cart)<span class="text-secondary">নূন্যতম অর্ডার {{ $fmt($coupon->min_cart) }}</span>@endif</div>
</div>

<!-- Delivery area -->
@if ($zoneOptions->isNotEmpty())
<form action="{{ route('cart.area') }}" method="POST" class="p-5 lg:p-6 rounded-2xl bg-surface-container-low/70 flex flex-col md:flex-row items-center justify-between gap-4">
@csrf
<div class="flex items-center gap-3 w-full md:w-auto"><div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary shrink-0"><span class="material-symbols-outlined text-[22px]">local_shipping</span></div><div><h4 class="font-title-md text-title-md text-on-surface">ডেলিভারি এলাকা নির্বাচন করুন</h4><p class="font-body-sm text-body-sm text-secondary">সঠিক চার্জ দেখতে ড্রপডাউন থেকে আপনার এলাকা সিলেক্ট করুন</p></div></div>
<select name="zone_id" onchange="this.form.submit()" class="bg-surface-container-lowest text-on-surface rounded-xl px-4 py-2.5 font-body-sm text-body-sm focus:outline-none shadow-sm cursor-pointer w-full md:w-60">
@foreach ($zoneOptions as $opt)<option value="{{ $opt['id'] }}" {{ $zone && $zone->id === $opt['id'] ? 'selected' : '' }}>{{ $opt['label'] }} ({{ $opt['fee'] ? $fmt($opt['fee']) : 'ফ্রি' }})</option>@endforeach
</select>
</form>
@endif
</div>

<!-- RIGHT: summary -->
<div class="lg:col-span-4 flex flex-col gap-space-md">
<div class="lg:sticky lg:top-44 rounded-3xl bg-surface-container-lowest p-5 lg:p-6 shadow-md">
<div class="flex items-center justify-between pb-3"><h2 class="font-headline-sm text-headline-sm text-on-surface">অর্ডার সামারি</h2><span class="material-symbols-outlined text-primary text-[24px]">receipt_long</span></div>
<div class="flex flex-col gap-3 py-3">
<div class="flex items-center justify-between font-body-md text-body-md text-secondary"><span>পণ্য উপমোট (Subtotal) <span class="text-xs">· {{ N::bn($units) }}টি</span>:</span><span class="font-semibold text-on-surface">{{ $fmt($priced['subtotal']) }}</span></div>
@if ($priced['discount_total'] > 0)
<div class="flex items-center justify-between font-body-md text-body-md text-primary font-semibold"><span>কুপন ছাড় ({{ $cart->coupon_code }}{{ $coupon && $coupon->type === 'percent' ? ' - '.N::bn($coupon->value).'%' : '' }}):</span><span>- {{ $fmt($priced['discount_total']) }}</span></div>
@endif
<div class="flex items-center justify-between font-body-md text-body-md text-secondary"><span class="flex items-center gap-1"><span>আনুমানিক ডেলিভারি চার্জ:</span><span class="material-symbols-outlined text-[14px] text-outline" title="ঢাকার ভিতরে ৳৬০, ঢাকার বাইরে ৳১২০">help</span></span><span class="font-semibold text-on-surface">{{ $deliveryFee ? $fmt($deliveryFee) : 'ফ্রি' }}</span></div>
<div class="flex items-center justify-between font-body-sm text-body-sm text-outline"><span>ভ্যাট ও ট্যাক্স (VAT):</span><span class="text-xs bg-surface-container-high px-2 py-0.5 rounded text-on-surface-variant font-medium">মূল্যের অন্তর্ভুক্ত</span></div>
</div>
@if ($freeOver && $payable < $freeOver)
<div class="p-3 rounded-xl bg-primary-fixed/40 mb-3"><p class="font-label-sm text-label-sm text-on-surface mb-2">আর <strong class="text-primary">{{ $fmt($freeOver - $payable) }}</strong> টাকার পণ্য যোগ করলে ডেলিভারি <strong>ফ্রি!</strong></p><div class="w-full h-1.5 bg-surface-container-highest rounded-full overflow-hidden"><div class="h-full bg-primary rounded-full" style="width: {{ min(100, round($payable / $freeOver * 100)) }}%"></div></div></div>
@elseif ($freeOver)
<div class="p-3 rounded-xl bg-primary-fixed/40 mb-3 font-label-sm text-label-sm text-on-surface flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[18px]">celebration</span>অভিনন্দন! আপনার অর্ডারে ডেলিভারি চার্জ ফ্রি।</div>
@endif
<div class="p-3 rounded-xl bg-surface-container-low font-body-sm text-body-sm text-secondary flex items-start gap-2 mb-3"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">local_shipping</span><span class="text-xs leading-relaxed">ডেলিভারি চার্জ আপনার প্রদত্ত ঠিকানা অনুযায়ী পরিবর্তিত হতে পারে (ঢাকার ভেতরে ৳৬০, ঢাকার বাইরে ৳১২০; ৳১,০০০+ অর্ডারে ঢাকায় ফ্রি ডেলিভারি)।</span></div>
<div class="py-4 my-1 flex items-baseline justify-between bg-surface-container-high/50 px-4 rounded-xl">
<div><span class="font-title-lg text-title-lg text-on-surface block font-bold">সর্বমোট প্রদেয়</span><span class="font-label-sm text-label-sm text-secondary">সকল চার্জ সহ</span></div>
<div class="text-right"><span class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight">{{ $fmt($grandTotal) }}</span>@if ($savings > 0)<span class="block text-xs line-through text-outline font-medium">{{ $fmt($grandTotal + $savings) }}</span>@endif</div>
</div>
@if ($savings > 0)<p class="text-center font-label-sm text-label-sm text-primary font-bold mt-1">🎉 আপনি মোট {{ $fmt($savings) }} সাশ্রয় করছেন!</p>@endif
@if ($over->isNotEmpty())
<span class="w-full py-4 px-6 rounded-full bg-surface-container-high text-on-surface-variant text-center font-label-md mt-3 flex items-center justify-center gap-2 cursor-not-allowed">আগে স্টক সমস্যা ঠিক করুন</span>
@else
<a href="{{ route('checkout.show') }}" class="w-full py-3.5 px-6 rounded-full bg-gradient-to-r from-primary to-primary-container text-on-primary text-center shadow-[0_6px_20px_rgba(128,86,0,0.28)] hover:shadow-[0_8px_26px_rgba(128,86,0,0.38)] hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3 group mt-3">
<span class="material-symbols-outlined text-[22px]">lock</span>
<span class="flex flex-col items-start leading-tight"><span class="font-title-md text-title-md font-bold whitespace-nowrap">চেকআউটে এগিয়ে যান</span><span class="font-label-sm text-[11px] font-normal opacity-85 tracking-wide">Proceed to Checkout</span></span>
<span class="material-symbols-outlined text-[22px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
</a>
@endif
<p class="mt-3 text-center font-label-sm text-label-sm text-secondary">⚡ কোনো পাসওয়ার্ডের ঝামেলা নেই! শুধু ফোন নম্বর দিয়ে দ্রুত গেস্ট চেকআউট করুন।</p>
<div class="mt-5 pt-4 flex flex-col gap-2.5 text-xs text-on-surface-variant">
@foreach ([['paid', 'ক্যাশ অন ডেলিভারি (পণ্য হাতে পেয়ে মূল্য পরিশোধ)'], ['security', 'বিকাশ, নগদ, রকেট ও কার্ডে ১০০% নিরাপদ পেমেন্ট'], ['verified_user', 'ল্যাব সার্টিফাইড ১০০% প্রাকৃতিক ও খাঁটি উপাদানের গ্যারান্টি'], ['published_with_changes', '৩ দিনের মধ্যে সহজ অক্ষত সিল্ড রিপ্লেসমেন্ট সুবিধা']] as [$ic, $tx])
<div class="flex items-center gap-2 font-medium"><span class="material-symbols-outlined text-primary text-[18px]">{{ $ic }}</span><span>{{ $tx }}</span></div>
@endforeach
</div>
<div class="mt-5 p-3 rounded-xl bg-surface-container text-center flex items-center justify-center gap-2"><span class="material-symbols-outlined text-primary text-[18px]">support_agent</span><span class="font-body-sm text-body-sm text-secondary">সহায়তা প্রয়োজন? কল করুন: <strong class="text-on-surface">০১৭১২-৩৪৫৬৭৮</strong></span></div>
</div>
</div>
</div>
@endif

<!-- You may also like -->
@if ($suggested->isNotEmpty())
<section class="mt-space-xl pt-space-md">
<div class="flex items-end justify-between mb-space-md">
<div><span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">মৌয়াল ও বাগান থেকে আরও কিছু</span><h2 class="font-headline-sm lg:font-headline-md text-headline-sm lg:text-headline-md text-on-surface">আপনার আরও পছন্দ হতে পারে <span class="font-body-md text-secondary font-normal hidden sm:inline">(You May Also Like)</span></h2></div>
<a href="{{ route('products.index') }}" class="text-primary hover:underline font-label-md text-label-md flex items-center gap-1 shrink-0"><span>সব পণ্য ব্রাউজ করুন</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-space-sm sm:gap-space-md">
@foreach ($suggested as $product)@include('storefront.partials.shop-card', ['product' => $product])@endforeach
</div>
</section>
@endif
</div>
</div>

@if ($count)
<!-- Mobile sticky checkout bar (sits above the bottom nav) -->
<aside class="lg:hidden fixed bottom-16 left-0 right-0 z-40 bg-surface/95 backdrop-blur-xl shadow-[0_-4px_24px_rgba(56,39,25,0.08)]">
<div class="px-gutter-mobile py-2.5 flex items-center justify-between gap-3">
<div class="flex flex-col"><span class="font-label-sm text-[11px] text-on-surface-variant leading-none">সর্বমোট</span><span class="font-title-lg text-title-lg text-primary font-bold leading-tight">{{ $fmt($grandTotal) }}</span><span class="font-label-sm text-[10px] text-outline">({{ N::bn($count) }}টি পণ্য)</span></div>
@if ($over->isNotEmpty())<span class="flex-1 text-center py-3 rounded-full bg-surface-container-high text-on-surface-variant font-label-md">স্টক সমস্যা ঠিক করুন</span>@else
<a href="{{ route('checkout.show') }}" class="flex-1 max-w-[220px] py-3 rounded-full bg-gradient-to-r from-primary-container to-primary text-on-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-md active:scale-95 transition-all">চেকআউট করুন <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>@endif
</div>
</aside>
@endif
<script>
function cartStep(btn, delta) {
  var form = btn.closest('form'), input = form.querySelector('input[name=qty]');
  var next = parseInt(input.value, 10) + delta, max = parseInt(form.dataset.max || '10', 10);
  if (next < 1 || next > max) { if (next > max) alert('এই পণ্যটির সর্বোচ্চ ' + max + 'টি অর্ডার করা যাবে।'); return; }
  input.value = next; form.submit();
}
</script>
@endsection
