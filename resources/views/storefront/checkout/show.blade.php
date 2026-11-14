@extends('storefront.layout')

@section('title', 'নিরাপদ চেকআউট — মধুবন')
@section('minimal', '1')

@php
    use App\Modules\Core\Support\Numerals as N;
    $items = $cart->items;
    $count = $items->count();
    $fmt = fn ($paisa) => '৳'.number_format($paisa / 100, 0);
    $weightLabel = fn ($v) => $v->weight_grams ? ($v->weight_grams >= 1000 ? N::bn(rtrim(rtrim(number_format($v->weight_grams / 1000, 1), '0'), '.')).' কেজি' : N::bn($v->weight_grams).' গ্রাম') : ($v->sku ?? '');
    $verified = (bool) $customer;
    $pre = fn ($key, $fallback = null) => old($key, $last[$key] ?? $fallback);
    $initName = old('name', $last['name'] ?? $customer?->name);
    $initEmail = old('email', $last['email'] ?? $customer?->email);
    $initDistrict = old('district', $district);
    $initDivision = old('division', $last['division'] ?? null);
    $discount = $priced['discount_total'];
    $deliveryKnown = $quote !== null;
    $deliveryFee = $quote['delivery_fee'] ?? 0;
    $grand = $priced['grand_total'];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin py-space-md lg:py-space-lg">
@if ($count === 0)
<div class="bg-surface-container-low rounded-2xl p-space-xl flex flex-col items-center text-center gap-3 shadow-sm">
<span class="material-symbols-outlined text-[48px] text-outline">shopping_cart_off</span>
<h1 class="font-headline-sm text-headline-sm text-on-surface">আপনার কার্ট খালি</h1>
<p class="font-body-md text-body-md text-on-surface-variant">চেকআউট করতে আগে কার্টে কিছু পণ্য যোগ করুন।</p>
<a href="{{ route('products.index') }}" class="mt-2 px-6 py-2.5 rounded-full bg-primary text-on-primary font-label-md text-label-md font-bold shadow-md hover:bg-secondary transition-colors">কেনাকাটা শুরু করুন</a>
</div>
@else
<!-- Progress -->
<section class="w-full mb-space-md">
<div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-space-sm">
<div><span class="font-label-sm text-label-sm uppercase tracking-wider text-primary">অর্ডার প্রস্তুতি ধাপ</span><h1 class="font-headline-sm text-headline-sm text-on-surface">নিরাপদ চেকআউট</h1></div>
<div class="flex items-center gap-space-xs sm:gap-space-sm overflow-x-auto pb-1 md:pb-0">
<div class="flex items-center gap-1.5 shrink-0"><span class="w-7 h-7 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-sm text-label-sm">✓</span><span class="font-label-md text-label-md text-on-surface font-semibold">কার্ট</span></div>
<span class="w-6 h-[2px] bg-primary/40 shrink-0"></span>
<div class="flex items-center gap-1.5 shrink-0"><span class="w-7 h-7 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-label-md text-label-md shadow-sm">২</span><span class="font-label-md text-label-md text-primary font-bold">তথ্য ও ডেলিভারি</span></div>
<span class="w-6 h-[2px] bg-outline-variant/60 shrink-0"></span>
<div class="flex items-center gap-1.5 shrink-0 opacity-70"><span class="w-7 h-7 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-label-sm text-label-sm">৩</span><span class="font-label-md text-label-md text-on-surface-variant">পেমেন্ট পদ্ধতি</span></div>
<span class="w-6 h-[2px] bg-outline-variant/60 shrink-0"></span>
<div class="flex items-center gap-1.5 shrink-0 opacity-70"><span class="w-7 h-7 rounded-full bg-surface-container-high text-on-surface-variant flex items-center justify-center font-label-sm text-label-sm">৪</span><span class="font-label-md text-label-md text-on-surface-variant">সম্পন্ন</span></div>
</div>
</div>
</div>
</section>

@if ($errors->any())
<div class="mb-space-md p-3.5 rounded-xl bg-error-container text-on-error-container font-body-sm text-body-sm flex items-start gap-3"><span class="material-symbols-outlined text-[20px] shrink-0">error</span><div>@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div></div>
@endif

<form id="checkout-form" action="{{ route('checkout.place') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start" novalidate>
@csrf
<input type="hidden" name="expected_total" id="expected-total" value="{{ $grand }}">
<input type="hidden" name="phone" id="order-phone" value="{{ $customer?->phone }}">
<input type="hidden" name="division" id="division-name" value="{{ $initDivision }}">
<input type="hidden" name="payment_method" value="cod">

<!-- LEFT -->
<div class="lg:col-span-8 flex flex-col gap-space-md min-w-0">
<!-- A: customer -->
<section class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg shadow-sm">
<div class="flex items-start justify-between gap-3 mb-space-md">
<div class="flex items-center gap-3"><span class="w-9 h-9 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-title-md text-title-md font-bold shrink-0">১</span><div><h2 class="font-headline-sm text-headline-sm text-on-surface">গ্রাহকের প্রাথমিক তথ্য</h2><p class="font-body-sm text-body-sm text-on-surface-variant">অর্ডারের আপডেট ও ট্র্যাকিং মেসেজ পাঠানোর জন্য প্রয়োজন</p></div></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-space-sm">
<div>
<label for="f-name" class="block font-label-md text-label-md text-on-surface mb-1.5">আপনার পূর্ণ নাম <span class="text-error">*</span></label>
<div class="relative"><input id="f-name" name="name" value="{{ $initName }}" required autocomplete="name" placeholder="যেমন: তাহমিদ আহমেদ" class="w-full bg-surface-container-low rounded-xl px-4 py-3 pr-10 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary"><span class="material-symbols-outlined absolute right-3 top-3 text-outline text-[20px]">person</span></div>
</div>
<div>
<label for="f-email" class="block font-label-md text-label-md text-on-surface mb-1.5">ইমেইল ঠিকানা <span class="font-body-sm text-body-sm text-on-surface-variant">(ঐচ্ছিক - ইনভয়েসের জন্য)</span></label>
<div class="relative"><input id="f-email" name="email" type="email" value="{{ $initEmail }}" autocomplete="email" placeholder="you@example.com" class="w-full bg-surface-container-low rounded-xl px-4 py-3 pr-10 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary"><span class="material-symbols-outlined absolute right-3 top-3 text-outline text-[20px]">mail</span></div>
</div>
</div>
<!-- phone + OTP -->
<div class="mt-space-sm bg-surface-container-low rounded-xl p-space-sm lg:p-space-md">
<div class="flex items-center justify-between gap-2 flex-wrap mb-2">
<label for="f-phone" class="font-label-md text-label-md text-on-surface">মোবাইল নম্বর <span class="text-error">*</span> <span class="font-body-sm text-body-sm text-on-surface-variant">(অর্ডার নিশ্চিত ও ডেলিভারি আপডেটের জন্য)</span></label>
<span id="verify-badge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm {{ $verified ? 'bg-primary-fixed text-on-primary-fixed' : 'bg-surface-container-highest text-on-surface-variant' }}"><span class="material-symbols-outlined text-[14px]">{{ $verified ? 'verified' : 'shield' }}</span><span id="verify-badge-text">{{ $verified ? 'যাচাইকৃত' : 'ভেরিফিকেশন আবশ্যক' }}</span></span>
</div>
<div class="flex flex-col sm:flex-row gap-space-sm">
<div class="flex-1 flex items-center bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
<span class="px-3 py-3 font-label-md text-label-md text-on-surface-variant border-r border-surface-container shrink-0">🇧🇩 +৮৮</span>
<input id="f-phone" inputmode="numeric" autocomplete="tel" value="{{ $customer ? preg_replace('/^\+?88/', '', $customer->phone) : '' }}" {{ $verified ? 'readonly' : '' }} placeholder="01712345678" class="flex-1 min-w-0 px-3 py-3 bg-transparent font-title-md text-title-md focus:outline-none {{ $verified ? 'text-on-surface-variant' : '' }}">
</div>
<button type="button" id="otp-send" class="px-6 py-3 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-on-surface transition-colors shadow-sm shrink-0 flex items-center justify-center gap-2 {{ $verified ? 'hidden' : '' }}"><span class="material-symbols-outlined text-[18px]">send</span><span id="otp-send-text">OTP পাঠান</span></button>
</div>
<p id="phone-error" class="hidden mt-2 font-body-sm text-body-sm text-error"></p>
<div id="otp-panel" class="hidden mt-space-sm pt-space-sm border-t border-surface-container">
<div class="flex items-center justify-between gap-2 flex-wrap mb-2"><span class="font-label-md text-label-md text-on-surface">আপনার ফোনে পাঠানো ৬-সংখ্যার OTP কোডটি লিখুন:</span><span class="font-label-sm text-label-sm text-on-surface-variant">পুনরায় কোড পাঠান: <span id="otp-timer" class="font-bold text-primary">০:৬০</span> <button type="button" id="otp-resend" class="hidden text-primary underline font-bold">আবার পাঠান</button></span></div>
<div class="flex items-center gap-2" id="otp-boxes">
@for ($i = 0; $i < 6; $i++)<input inputmode="numeric" maxlength="1" aria-label="OTP অঙ্ক {{ $i + 1 }}" class="otp-box w-11 h-12 sm:w-12 sm:h-14 text-center bg-surface-container-lowest rounded-lg shadow-sm font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-primary">@endfor
<button type="button" id="otp-verify" class="ml-2 px-4 h-12 rounded-lg bg-primary-container text-on-primary-container font-label-md text-label-md font-bold hover:bg-primary hover:text-on-primary transition-colors">যাচাই</button>
</div>
<p id="otp-error" class="hidden mt-2 font-body-sm text-body-sm text-error"></p>
<p id="otp-debug" class="hidden mt-2 font-label-sm text-label-sm text-on-surface-variant bg-surface-container px-2 py-1 rounded inline-block"></p>
</div>
<div id="otp-success" class="{{ $verified ? '' : 'hidden' }} mt-space-sm flex items-center justify-between gap-2 px-3 py-2.5 rounded-lg bg-primary-fixed/40 font-body-sm text-body-sm"><span class="flex items-center gap-2 text-on-surface"><span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>মোবাইল নম্বর সফলভাবে যাচাই করা হয়েছে ✓</span><span class="font-label-sm text-label-sm text-on-surface-variant hidden sm:inline">নিরাপত্তা স্তর: লেভেল ১</span></div>
</div>
</section>

<!-- B: delivery -->
<section class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg shadow-sm">
<div class="flex items-center gap-3 mb-space-md"><span class="w-9 h-9 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-title-md text-title-md font-bold shrink-0">২</span><div><h2 class="font-headline-sm text-headline-sm text-on-surface">ডেলিভারি ঠিকানা ও অঞ্চল</h2><p class="font-body-sm text-body-sm text-on-surface-variant">সঠিক পণ্যপৌঁছানোর জন্য নির্ভুল তথ্য দিন</p></div></div>
<div class="grid grid-cols-2 gap-space-sm">
<div class="col-span-1">
<label for="f-division" class="block font-label-md text-label-md text-on-surface mb-1.5">বিভাগ (Division) <span class="text-error">*</span></label>
<select id="f-division" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer"><option value="">বিভাগ নির্বাচন করুন</option></select>
</div>
<div class="col-span-1">
<label for="f-district" class="block font-label-md text-label-md text-on-surface mb-1.5">জেলা (District) <span class="text-error">*</span></label>
<select id="f-district" name="district" required class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer"><option value="">আগে বিভাগ নির্বাচন করুন</option></select>
</div>
<div class="col-span-1">
<label for="f-upazila" class="block font-label-md text-label-md text-on-surface mb-1.5">উপজেলা / থানা</label>
<input id="f-upazila" name="upazila" value="{{ $pre('upazila') }}" placeholder="যেমন: মিরপুর" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="col-span-1">
<label for="f-area" class="block font-label-md text-label-md text-on-surface mb-1.5">এলাকা / মহল্লা</label>
<input id="f-area" name="area" value="{{ $pre('area') }}" placeholder="যেমন: মিরপুর-১" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="col-span-2 sm:col-span-1 sm:col-span-2 md:col-span-1">
<label for="f-address" class="block font-label-md text-label-md text-on-surface mb-1.5">বিস্তারিত ঠিকানা (বাড়ি, রোড ও ফ্ল্যাট নম্বর) <span class="text-error">*</span></label>
<textarea id="f-address" name="address_line" required rows="2" placeholder="বাড়ি #১২, রোড #৫, ব্লক এ, দক্ষিণ বিশিল" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">{{ $pre('address_line') }}</textarea>
</div>
<div class="col-span-2 sm:col-span-1">
<label for="f-postcode" class="block font-label-md text-label-md text-on-surface mb-1.5">ডাক কোড <span class="font-body-sm text-body-sm text-on-surface-variant">(ঐচ্ছিক)</span></label>
<input id="f-postcode" name="postcode" inputmode="numeric" value="{{ $pre('postcode') }}" placeholder="১২১৬" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="col-span-2 sm:col-span-1">
<label for="f-alt-name" class="block font-label-md text-label-md text-on-surface mb-1.5">বিকল্প গ্রাহকের নাম <span class="font-body-sm text-body-sm text-on-surface-variant">(প্রয়োজনে)</span></label>
<input id="f-alt-name" name="alt_name" value="{{ old('alt_name') }}" placeholder="যেমন: ভাবনা আফরোজ" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
</div>
<div class="col-span-2 sm:col-span-1">
<label for="f-alt-phone" class="block font-label-md text-label-md text-on-surface mb-1.5">বিকল্প ফোন নম্বর <span class="font-body-sm text-body-sm text-on-surface-variant">(ঐচ্ছিক)</span></label>
<input id="f-alt-phone" name="alt_phone" inputmode="numeric" value="{{ old('alt_phone') }}" placeholder="01XXXXXXXXX" class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">
</div>
</div>
<div id="delivery-card" class="mt-space-md rounded-xl bg-surface-container-low p-space-sm lg:p-space-md flex items-center gap-3">
<div class="w-11 h-11 rounded-full bg-surface-container-highest flex items-center justify-center text-primary shrink-0"><span id="delivery-icon" class="material-symbols-outlined text-[24px]">local_shipping</span></div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-2 flex-wrap"><h4 class="font-title-md text-title-md text-on-surface" id="delivery-title">{{ $deliveryKnown ? 'হোম ডেলিভারি উপলব্ধ' : 'ডেলিভারি এলাকা নির্বাচন করুন' }}</h4><span id="delivery-zone" class="hidden px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-[11px]"></span></div>
<p id="delivery-eta" class="font-body-sm text-body-sm text-on-surface-variant">@if ($deliveryKnown && ($quote['est_days_min'] ?? null))সম্ভাব্য ডেলিভারি সময়: <strong>{{ N::bn($quote['est_days_min']) }}–{{ N::bn($quote['est_days_max']) }} কার্যদিবস</strong>@else জেলা নির্বাচন করলে ডেলিভারি চার্জ ও সময় দেখতে পাবেন।@endif</p>
</div>
<div class="text-right shrink-0"><span class="block font-label-sm text-label-sm text-on-surface-variant">ডেলিভারি ফি</span><span id="delivery-fee-card" class="font-title-lg text-title-lg text-primary font-bold">{{ $deliveryKnown ? ($deliveryFee ? $fmt($deliveryFee) : 'ফ্রি') : '—' }}</span></div>
</div>
</section>

<!-- C: payment -->
<section class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg shadow-sm">
<div class="flex items-center gap-3 mb-space-md"><span class="w-9 h-9 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-title-md text-title-md font-bold shrink-0">৩</span><div><h2 class="font-headline-sm text-headline-sm text-on-surface">পেমেন্ট মেথড বাছাই করুন</h2><p class="font-body-sm text-body-sm text-on-surface-variant">আপনার সুবিধাজনক ও শতভাগ নিরাপদ পেমেন্ট অপশন নির্বাচন করুন</p></div></div>
<div class="flex flex-col gap-space-sm">
<label class="flex items-start gap-3 p-space-sm lg:p-space-md rounded-xl bg-primary-fixed/40 ring-1 ring-primary-container cursor-pointer">
<input type="radio" checked disabled class="mt-1 w-4 h-4 accent-primary">
<div class="flex-1"><div class="flex items-center gap-2 flex-wrap"><span class="font-title-md text-title-md text-on-surface font-semibold">ক্যাশ অন ডেলিভারি (Cash on Delivery)</span><span class="px-2 py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-[11px] font-bold">জনপ্রিয় পদ্ধতি</span></div><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">পণ্য হাতে পেয়ে পুরোপুরি নিশ্চিত হয়ে নগদ মূল্য পরিশোধ করুন। কোনো অগ্রিম পেমেন্টের ঝামেলা নেই।</p><p class="mt-1 font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">info</span>সর্বোচ্চ ৳১০,০০০ পর্যন্ত ক্যাশ অন ডেলিভারি সুবিধা প্রযোজ্য।</p></div>
<span class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-primary shadow-sm shrink-0"><span class="material-symbols-outlined">payments</span></span>
</label>
<div class="flex items-start gap-3 p-space-sm lg:p-space-md rounded-xl bg-surface-container-low/60 opacity-70 cursor-not-allowed">
<input type="radio" disabled class="mt-1 w-4 h-4"><div class="flex-1"><div class="flex items-center gap-2 flex-wrap"><span class="font-title-md text-title-md text-on-surface">মোবাইল ব্যাংকিং (bKash, Nagad, Rocket)</span><span class="px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface-variant font-label-sm text-[11px]">শীঘ্রই আসছে</span></div><div class="mt-1.5 flex gap-1.5"><span class="px-2 py-0.5 rounded bg-[#fde7ee] text-[#c2185b] font-label-sm text-[11px] font-bold">bKash</span><span class="px-2 py-0.5 rounded bg-[#fff2dc] text-[#ef6c00] font-label-sm text-[11px] font-bold">Nagad</span><span class="px-2 py-0.5 rounded bg-[#f3e5f5] text-[#7b1fa2] font-label-sm text-[11px] font-bold">Rocket</span></div></div>
<span class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-on-surface-variant shrink-0"><span class="material-symbols-outlined">account_balance_wallet</span></span>
</div>
<div class="flex items-start gap-3 p-space-sm lg:p-space-md rounded-xl bg-surface-container-low/60 opacity-70 cursor-not-allowed">
<input type="radio" disabled class="mt-1 w-4 h-4"><div class="flex-1"><div class="flex items-center gap-2 flex-wrap"><span class="font-title-md text-title-md text-on-surface">ডেবিট / ক্রেডিট কার্ড (Debit or Credit Card)</span><span class="px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface-variant font-label-sm text-[11px]">শীঘ্রই আসছে</span></div><div class="mt-1.5 flex gap-1.5">@foreach (['VISA', 'Mastercard', 'AMEX', 'UnionPay'] as $c)<span class="px-2 py-0.5 rounded bg-surface-container-highest text-on-surface-variant font-label-sm text-[11px] font-bold">{{ $c }}</span>@endforeach</div></div>
<span class="w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center text-on-surface-variant shrink-0"><span class="material-symbols-outlined">credit_card</span></span>
</div>
<div class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-surface-container font-body-sm text-body-sm text-on-surface-variant"><span class="material-symbols-outlined text-primary text-[18px]">shield</span>আপনার পেমেন্ট তথ্য সম্পূর্ণ ২৫৬-বিট SSL এনক্রিপশন প্রযুক্তির মাধ্যমে সুরক্ষিত ও গোপনীয়।</div>
</div>
</section>

<!-- D: note -->
<section class="bg-surface-container-lowest rounded-2xl p-space-md lg:p-space-lg shadow-sm">
<div class="flex items-center gap-3 mb-space-sm"><span class="w-9 h-9 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-title-md text-title-md font-bold shrink-0">৪</span><h2 class="font-headline-sm text-headline-sm text-on-surface">ডেলিভারি নোট ও বিশেষ নির্দেশনা <span class="font-body-sm text-body-sm text-on-surface-variant">(ঐচ্ছিক)</span></h2></div>
<textarea name="note" rows="3" maxlength="500" placeholder="ডেলিভারি সংক্রান্ত কোনো বিশেষ নির্দেশনা থাকলে লিখুন (যেমন: বাসায় আসার পূর্বে কল করবেন, বা দারোয়ানের কাছে পার্সেল রাখবেন)..." class="w-full bg-surface-container-low rounded-xl px-4 py-3 font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary">{{ old('note') }}</textarea>
</section>
</div>

<!-- RIGHT: summary -->
<aside class="lg:col-span-4 flex flex-col gap-space-md min-w-0">
<div class="lg:sticky lg:top-24 flex flex-col gap-space-md">
<div class="bg-surface-container-lowest rounded-3xl shadow-md overflow-hidden">
<div class="p-space-md lg:p-space-lg">
<div class="flex items-center justify-between mb-space-sm"><h2 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[22px]">shopping_bag</span>আপনার অর্ডার <span class="font-body-sm text-body-sm text-on-surface-variant">({{ N::bn($count) }}টি পণ্য)</span></h2><a href="{{ route('cart.show') }}" class="font-label-sm text-label-sm text-primary underline">কার্ট পরিবর্তন</a></div>
<div class="flex flex-col gap-3 max-h-80 overflow-y-auto pr-1">
@foreach ($items as $item)
@php($v = $item->variant)
<div class="flex items-center gap-3">
<a href="{{ route('products.show', $v->product->slug) }}" class="relative w-14 h-14 rounded-lg overflow-hidden bg-surface-container shrink-0">@if ($img = $v->product->primaryImageUrl())<img src="{{ $img }}" alt="" class="w-full h-full object-cover" loading="lazy">@else<span class="w-full h-full flex items-center justify-center text-xl">🍯</span>@endif<span class="absolute -top-0 -right-0 min-w-[18px] h-[18px] px-1 bg-primary text-on-primary text-[10px] font-bold rounded-bl-lg flex items-center justify-center">{{ N::bn($item->qty) }}</span></a>
<div class="flex-1 min-w-0"><p class="font-title-md text-[15px] text-on-surface font-semibold truncate">{{ $v->product->name['bn'] ?? '' }}</p><p class="font-label-sm text-label-sm text-on-surface-variant">আকার: {{ $weightLabel($v) }} ({{ N::bn($item->qty) }}টি)</p></div>
<span class="font-title-md text-title-md text-on-surface font-bold">{{ $fmt($v->effectivePrice() * $item->qty) }}</span>
</div>
@endforeach
</div>
@if ($cart->coupon_code && $discount > 0)
<div class="mt-space-sm flex items-center justify-between px-3 py-2 rounded-lg bg-primary-fixed/40 font-label-sm text-label-sm"><span class="flex items-center gap-1.5 text-on-surface"><span class="material-symbols-outlined text-primary text-[16px]">sell</span>{{ $cart->coupon_code }} কুপন কোড প্রয়োগ হয়েছে</span><span class="text-primary font-bold">সংরক্ষিত</span></div>
@endif
<div class="mt-space-sm flex flex-col gap-2.5 font-body-md text-body-md text-secondary">
<div class="flex justify-between"><span>উপ-মোট (Subtotal)</span><span class="font-semibold text-on-surface">{{ $fmt($priced['subtotal']) }}</span></div>
@if ($discount > 0)<div class="flex justify-between text-primary font-semibold"><span>কুপন ছাড় ({{ $cart->coupon_code }})</span><span>-{{ $fmt($discount) }}</span></div>@endif
<div class="flex justify-between"><span>ডেলিভারি চার্জ <span id="sum-delivery-where" class="text-xs"></span></span><span id="sum-delivery" class="font-semibold text-on-surface">{{ $deliveryKnown ? ($deliveryFee ? $fmt($deliveryFee) : 'ফ্রি') : 'জেলা বাছাই করুন' }}</span></div>
<div class="flex justify-between font-body-sm text-body-sm text-outline"><span>ভ্যাট ও সরকার নির্ধারিত কর</span><span class="text-xs bg-surface-container-high px-2 py-0.5 rounded text-on-surface-variant">মূল্যের অন্তর্ভুক্ত (০%)</span></div>
</div>
</div>
<div class="bg-surface-container-high/50 px-space-md lg:px-space-lg py-space-sm flex items-baseline justify-between"><div><span class="font-title-lg text-title-lg text-on-surface font-bold">সর্বমোট প্রদেয়:</span><span class="block font-label-sm text-label-sm text-secondary">সব চার্জ সহ ফাইনাল প্রাইস</span></div><span id="sum-total" class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight">{{ $fmt($grand) }}</span></div>
<div class="p-space-md lg:p-space-lg pt-space-sm">
<p id="delivery-note" class="mb-3 px-3 py-2 rounded-lg bg-surface-container-low font-label-sm text-label-sm text-secondary flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[16px]">verified</span>সার্ভার-ভেরিফাইড ফাইনাল প্রাইস। কোনো লুকানো চার্জ নেই।</p>
<button type="submit" id="place-btn" class="hidden lg:flex w-full py-3.5 px-6 rounded-full bg-gradient-to-r from-primary to-primary-container text-on-primary shadow-[0_6px_20px_rgba(128,86,0,0.28)] hover:shadow-[0_8px_26px_rgba(128,86,0,0.38)] hover:-translate-y-0.5 active:translate-y-0 transition-all items-center justify-center gap-2 font-title-md text-title-md font-bold disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"><span class="material-symbols-outlined text-[20px]">lock</span><span>অর্ডার কনফার্ম করুন (<span class="place-total">{{ $fmt($grand) }}</span>)</span><span class="material-symbols-outlined text-[20px]">arrow_forward</span></button>
<p id="place-hint" class="mt-2 text-center font-label-sm text-label-sm text-error hidden"></p>
<p class="mt-2 text-center font-label-sm text-label-sm text-on-surface-variant">অর্ডার ক্লিক করলে আপনি আমাদের শর্তাবলীতে সম্মতি দিচ্ছেন</p>
</div>
</div>
<div class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm">
<span class="font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant">মধুবন আস্থা ও প্রতিশ্রুতি</span>
<div class="mt-space-sm grid grid-cols-2 gap-space-sm">
@foreach ([['workspace_premium', '১০০% খাঁটি পণ্য', 'ল্যাব টেস্ট সনদপ্রাপ্ত'], ['published_with_changes', '৩ দিনের রিটার্ন', 'পছন্দ না হলে ফেরত'], ['eco', 'আর্টিসানাল কালেকশন', 'সংরক্ষিত উপাদানমুক্ত'], ['support_agent', 'সহায়তা ২৪/৭', '০১৭১২-৩৪৫৬৭৮']] as [$ic, $t, $s])
<div class="bg-surface-container-low rounded-lg p-space-sm"><span class="material-symbols-outlined text-primary text-[20px]">{{ $ic }}</span><p class="font-label-md text-label-md text-on-surface font-semibold mt-1">{{ $t }}</p><p class="font-label-sm text-[11px] text-on-surface-variant">{{ $s }}</p></div>
@endforeach
</div>
</div>
</div>
</aside>
</form>

<!-- Mobile sticky place-order bar -->
<aside class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 backdrop-blur-xl shadow-[0_-4px_24px_rgba(56,39,25,0.08)]">
<div class="px-gutter-mobile py-2.5 flex items-center justify-between gap-3">
<div class="flex flex-col"><span class="font-label-sm text-[11px] text-on-surface-variant leading-none">সর্বমোট প্রদেয়</span><span class="font-title-lg text-title-lg text-primary font-bold leading-tight place-total">{{ $fmt($grand) }}</span></div>
<button type="submit" form="checkout-form" id="place-btn-mobile" class="flex-1 max-w-[240px] py-3 rounded-full bg-gradient-to-r from-primary-container to-primary text-on-primary font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-md active:scale-95 transition-all disabled:opacity-60">অর্ডার কনফার্ম করুন <span class="material-symbols-outlined text-[18px]">arrow_forward</span></button>
</div>
</aside>
@endif
</div>

@if ($count)
<script>
(function () {
  var GEO = @json($divisions);
  var INIT = { division: @json($initDivision), district: @json($initDistrict), verified: @json($verified) };
  var URLS = { quote: @json(route('checkout.quote')), send: @json(route('checkout.otp.send')), verify: @json(route('checkout.otp.verify')) };
  var BASE = { subtotal: @json($priced['subtotal']), discount: @json($discount) };
  var $ = function (id) { return document.getElementById(id); };
  var token = document.querySelector('#checkout-form input[name=_token]').value;
  var state = { verified: INIT.verified, quoteOk: @json($deliveryKnown), districtChosen: !!INIT.district };
  var money = function (p) { return '৳' + Math.round(p / 100).toLocaleString('en-US'); };
  var bn = function (n) { return String(n).replace(/\d/g, function (d) { return '০১২৩৪৫৬৭৮৯'[d]; }); };

  // ---- Division → district selects
  var divSel = $('f-division'), distSel = $('f-district');
  GEO.forEach(function (d) { var o = document.createElement('option'); o.value = d.id; o.textContent = d.bn + ' (' + d.name + ')'; divSel.appendChild(o); });
  function fillDistricts(divId, selected) {
    distSel.innerHTML = '';
    var d = GEO.find(function (x) { return String(x.id) === String(divId); });
    if (!d) { distSel.innerHTML = '<option value="">আগে বিভাগ নির্বাচন করুন</option>'; return; }
    distSel.innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';
    d.districts.forEach(function (x) { var o = document.createElement('option'); o.value = x.name; o.textContent = x.bn + ' (' + x.name + ')'; if (x.name === selected) o.selected = true; distSel.appendChild(o); });
    $('division-name').value = d.name;
  }
  if (INIT.district) {
    var owner = GEO.find(function (d) { return d.districts.some(function (x) { return x.name === INIT.district; }); });
    if (owner) { divSel.value = owner.id; fillDistricts(owner.id, INIT.district); }
  } else if (INIT.division) {
    var dv = GEO.find(function (d) { return d.name === INIT.division; }); if (dv) { divSel.value = dv.id; fillDistricts(dv.id); }
  }
  divSel.addEventListener('change', function () { fillDistricts(divSel.value); state.districtChosen = false; state.quoteOk = false; resetQuote(); refresh(); });

  // ---- Live quote
  function resetQuote() {
    $('sum-delivery').textContent = 'জেলা বাছাই করুন'; $('delivery-fee-card').textContent = '—';
    $('delivery-title').textContent = 'ডেলিভারি এলাকা নির্বাচন করুন'; $('delivery-zone').classList.add('hidden');
    $('delivery-eta').textContent = 'জেলা নির্বাচন করলে ডেলিভারি চার্জ ও সময় দেখতে পাবেন।';
    setTotal(BASE.subtotal - BASE.discount);
  }
  function setTotal(paisa) {
    $('expected-total').value = paisa; $('sum-total').textContent = money(paisa);
    document.querySelectorAll('.place-total').forEach(function (e) { e.textContent = money(paisa); });
  }
  distSel.addEventListener('change', function () {
    state.districtChosen = !!distSel.value; state.quoteOk = false;
    if (!distSel.value) { resetQuote(); refresh(); return; }
    fetch(URLS.quote + '?district=' + encodeURIComponent(distSel.value), { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (res) {
        if (!res.ok) {
          state.quoteOk = false; $('delivery-title').textContent = 'এই এলাকায় ডেলিভারি সেবা নেই'; $('delivery-eta').textContent = res.j.message || '';
          $('delivery-icon').textContent = 'location_off'; $('sum-delivery').textContent = '—'; refresh(); return;
        }
        var q = res.j; state.quoteOk = true;
        $('delivery-icon').textContent = 'local_shipping'; $('delivery-title').textContent = 'হোম ডেলিভারি উপলব্ধ';
        var fee = q.delivery_fee ? money(q.delivery_fee) : 'ফ্রি';
        $('sum-delivery').textContent = fee; $('delivery-fee-card').textContent = fee;
        $('delivery-eta').innerHTML = q.est_days_min ? 'সম্ভাব্য ডেলিভারি সময়: <strong>' + bn(q.est_days_min) + '–' + bn(q.est_days_max) + ' কার্যদিবস</strong>' : '';
        setTotal(q.grand_total); refresh();
      }).catch(function () { refresh(); });
  });

  // ---- OTP
  var phoneIn = $('f-phone'), sendBtn = $('otp-send'), panel = $('otp-panel'), boxes = Array.prototype.slice.call(document.querySelectorAll('.otp-box'));
  var timerEl = $('otp-timer'), resendBtn = $('otp-resend'), tick;
  function show(el, msg) { el.textContent = msg; el.classList.remove('hidden'); }
  function hide(el) { el.classList.add('hidden'); }
  function post(url, data) {
    return fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify(data) })
      .then(function (r) { return r.json().catch(function () { return { ok: false, message: 'কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।' }; }).then(function (j) { if (r.status === 429) j = { ok: false, message: 'অনেকবার চেষ্টা হয়েছে — কিছুক্ষণ পর আবার চেষ্টা করুন।' }; return j; }); });
  }
  function startTimer() {
    var s = 60; resendBtn.classList.add('hidden'); timerEl.classList.remove('hidden'); clearInterval(tick);
    function paint() { timerEl.textContent = '০:' + bn(String(s).padStart(2, '0')); }
    paint(); tick = setInterval(function () { s--; paint(); if (s <= 0) { clearInterval(tick); timerEl.classList.add('hidden'); resendBtn.classList.remove('hidden'); } }, 1000);
  }
  function sendOtp() {
    hide($('phone-error')); var raw = phoneIn.value.replace(/\D/g, '');
    if (raw.length < 10) { show($('phone-error'), 'সঠিক মোবাইল নম্বর দিন (যেমন: 01712345678)।'); return; }
    sendBtn.disabled = true; $('otp-send-text').textContent = 'পাঠানো হচ্ছে…';
    post(URLS.send, { phone: raw }).then(function (j) {
      sendBtn.disabled = false; $('otp-send-text').textContent = 'আবার পাঠান';
      if (!j.ok) { show($('phone-error'), j.message || 'OTP পাঠানো যায়নি।'); return; }
      panel.classList.remove('hidden'); boxes.forEach(function (b) { b.value = ''; }); boxes[0].focus(); hide($('otp-error')); startTimer();
      if (j.debug_code) show($('otp-debug'), 'ডেভ মোড — আপনার কোড: ' + j.debug_code);
    });
  }
  function verifyOtp() {
    var code = boxes.map(function (b) { return b.value; }).join('');
    if (code.length < 6) { show($('otp-error'), '৬ সংখ্যার পুরো কোডটি লিখুন।'); return; }
    hide($('otp-error'));
    post(URLS.verify, { code: code }).then(function (j) {
      if (!j.ok) { show($('otp-error'), j.message || 'OTP সঠিক নয়।'); boxes.forEach(function (b) { b.value = ''; }); boxes[0].focus(); return; }
      state.verified = true; $('order-phone').value = j.phone; panel.classList.add('hidden'); sendBtn.classList.add('hidden'); phoneIn.readOnly = true;
      $('otp-success').classList.remove('hidden'); clearInterval(tick);
      var badge = $('verify-badge'); badge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-label-sm text-label-sm bg-primary-fixed text-on-primary-fixed';
      badge.querySelector('.material-symbols-outlined').textContent = 'verified'; $('verify-badge-text').textContent = 'যাচাইকৃত';
      refresh();
    });
  }
  if (sendBtn) { sendBtn.addEventListener('click', sendOtp); resendBtn.addEventListener('click', sendOtp); $('otp-verify').addEventListener('click', verifyOtp); }
  boxes.forEach(function (b, i) {
    b.addEventListener('input', function () { b.value = b.value.replace(/\D/g, '').slice(-1); if (b.value && boxes[i + 1]) boxes[i + 1].focus(); if (boxes.every(function (x) { return x.value; })) verifyOtp(); });
    b.addEventListener('keydown', function (e) { if (e.key === 'Backspace' && !b.value && boxes[i - 1]) boxes[i - 1].focus(); });
    b.addEventListener('paste', function (e) { var t = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6); if (!t) return; e.preventDefault(); t.split('').forEach(function (c, k) { if (boxes[k]) boxes[k].value = c; }); if (t.length === 6) verifyOtp(); });
  });

  // ---- Submit gating
  var form = $('checkout-form'), btns = [$('place-btn'), $('place-btn-mobile')].filter(Boolean);
  function missing() {
    if (!state.verified) return 'আগে মোবাইল নম্বর যাচাই করুন (OTP)।';
    if (!form.name.value.trim()) return 'আপনার নাম লিখুন।';
    if (!distSel.value) return 'ডেলিভারি জেলা নির্বাচন করুন।';
    if (!state.quoteOk) return 'এই এলাকায় ডেলিভারি সম্ভব নয়, অন্য জেলা বেছে নিন।';
    if (!form.address_line.value.trim()) return 'বিস্তারিত ঠিকানা লিখুন।';
    return '';
  }
  function refresh() {
    var m = missing(); var hint = $('place-hint');
    // keep buttons clickable so the click can explain what is missing; only block double-submits
    if (hint) { hint.classList.add('hidden'); }
  }
  form.addEventListener('submit', function (e) {
    var m = missing();
    if (m) { e.preventDefault(); var hint = $('place-hint'); hint.textContent = m; hint.classList.remove('hidden'); alert(m); return; }
    btns.forEach(function (b) { b.disabled = true; });
  });
  refresh();
})();
</script>
@endif
@endsection
