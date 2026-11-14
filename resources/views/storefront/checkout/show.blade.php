@extends('storefront.layout')

@section('title', 'চেকআউট — মধুবন')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-12 py-10">
    <div class="mb-8">
        <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">অর্ডার সম্পন্ন করুন</span>
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold">চেকআউট</h1>
    </div>

    @if ($cart->items->isEmpty())
        <p class="font-body-md text-body-md text-on-surface-variant">আপনার কার্ট খালি। <a href="{{ route('products.index') }}" class="text-primary font-bold hover:underline">কেনাকাটা করুন</a></p>
    @elseif (! $customer)
        {{-- Step 1: phone verification (CHK-R1) --}}
        <div class="max-w-sm rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">অর্ডার করতে প্রথমে আপনার মোবাইল নাম্বার verify করুন।</p>
            <form action="{{ route('checkout.otp.send') }}" method="POST" class="space-y-3">
                @csrf
                <input name="phone" placeholder="01XXXXXXXXX" required
                    class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                <button class="w-full py-2.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md">OTP পাঠান</button>
            </form>
        </div>
    @else
        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm max-w-md">
            <form action="{{ route('checkout.show') }}" method="GET" class="space-y-3">
                {{-- GET form just to recompute the delivery quote; the real place-order form is below --}}
                <h2 class="font-title-md text-title-md text-on-surface font-bold">ডেলিভারি তথ্য</h2>
                <div>
                    <label class="block font-body-sm text-body-sm text-on-surface-variant mb-1">জেলা</label>
                    <input name="district" value="{{ $district }}" required placeholder="ঢাকা"
                        class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-full bg-surface-container text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-md text-label-md">ডেলিভারি চার্জ হিসাব করুন</button>
            </form>
        </div>

        @if ($error)
            <p class="mt-4 rounded-xl bg-error-container px-4 py-3 font-body-sm text-body-sm text-on-error-container">{{ $error }}</p>
        @endif

        @if ($priced && $district)
            <form action="{{ route('checkout.place') }}" method="POST" class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                @csrf
                <input type="hidden" name="district" value="{{ $district }}">
                <input type="hidden" name="expected_total" value="{{ $priced['grand_total'] }}">

                <div class="lg:col-span-2 space-y-4 rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
                    <h2 class="font-title-md text-title-md text-on-surface font-bold">ঠিকানা</h2>
                    <div>
                        <label class="block font-body-sm text-body-sm text-on-surface-variant mb-1">নাম</label>
                        <input name="name" required class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                    </div>
                    <div>
                        <label class="block font-body-sm text-body-sm text-on-surface-variant mb-1">ফোন</label>
                        <input name="phone" value="{{ $customer->phone }}" required class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                    </div>
                    <div>
                        <label class="block font-body-sm text-body-sm text-on-surface-variant mb-1">এলাকা</label>
                        <input name="area" class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                    </div>
                    <div>
                        <label class="block font-body-sm text-body-sm text-on-surface-variant mb-1">সম্পূর্ণ ঠিকানা</label>
                        <textarea name="address_line" required class="w-full rounded-2xl border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container"></textarea>
                    </div>

                    <h2 class="pt-2 font-title-md text-title-md text-on-surface font-bold">পেমেন্ট</h2>
                    <label class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface">
                        <input type="radio" name="payment_method" value="cod" checked> ক্যাশ অন ডেলিভারি (COD)
                    </label>
                </div>

                <div class="space-y-2 rounded-2xl bg-surface-container-low p-6 font-body-sm text-body-sm h-fit">
                    <h2 class="font-title-md text-title-md text-on-surface font-bold mb-2">অর্ডার সামারি</h2>
                    <div class="flex justify-between"><span class="text-on-surface-variant">সাবটোটাল</span><span class="text-on-surface">৳{{ number_format($priced['subtotal'] / 100, 0) }}</span></div>
                    <div class="flex justify-between"><span class="text-on-surface-variant">ডেলিভারি</span><span class="text-on-surface">৳{{ number_format($priced['delivery_fee'] / 100, 0) }}</span></div>
                    @if ($priced['discount_total'] > 0)
                        <div class="flex justify-between text-primary"><span>ছাড়</span><span>-৳{{ number_format($priced['discount_total'] / 100, 0) }}</span></div>
                    @endif
                    <div class="flex justify-between border-t border-surface-container-high pt-2 font-title-md text-title-md font-bold text-on-surface"><span>মোট</span><span>৳{{ number_format($priced['grand_total'] / 100, 0) }}</span></div>

                    <button type="submit" class="mt-3 w-full py-3 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md">অর্ডার কনফার্ম করুন</button>
                </div>
            </form>
        @endif
    @endif
</div>
@endsection
