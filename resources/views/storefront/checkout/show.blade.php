@extends('storefront.layout')

@section('title', 'চেকআউট — Bazardev Ecom')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6">
    <h1 class="text-xl font-semibold text-stone-900">চেকআউট</h1>

    @if ($cart->items->isEmpty())
        <p class="mt-6 text-stone-500">আপনার কার্ট খালি। <a href="{{ route('products.index') }}" class="text-amber-700 hover:underline">কেনাকাটা করুন</a></p>
    @elseif (! $customer)
        <!-- Step 1: phone verification (CHK-R1) -->
        <div class="mt-6 max-w-sm rounded-xl border border-stone-200 bg-white p-6">
            <p class="text-sm text-stone-600">অর্ডার করতে প্রথমে আপনার মোবাইল নাম্বার verify করুন।</p>
            <form action="{{ route('checkout.otp.send') }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <input name="phone" placeholder="01XXXXXXXXX" required
                    class="w-full rounded-md border border-stone-300 px-3 py-2 text-sm">
                <button class="w-full rounded-full bg-amber-700 py-2.5 font-medium text-white hover:bg-amber-800">OTP পাঠান</button>
            </form>
        </div>
    @else
        <div class="mt-6 grid grid-cols-1 gap-8 sm:grid-cols-3">
            <form action="{{ route('checkout.show') }}" method="GET" class="sm:col-span-2 space-y-4 rounded-xl border border-stone-200 bg-white p-6">
                {{-- GET form just to recompute the delivery quote; the real place-order form is below --}}
                <h2 class="font-medium text-stone-900">ডেলিভারি তথ্য</h2>

                <div>
                    <label class="block text-sm text-stone-700">জেলা</label>
                    <input name="district" value="{{ $district }}" required placeholder="ঢাকা"
                        class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm">
                </div>
                <button type="submit" class="rounded-md border border-stone-300 px-4 py-2 text-sm hover:bg-stone-50">ডেলিভারি চার্জ হিসাব করুন</button>
            </form>
        </div>

        @if ($error)
            <p class="mt-4 rounded-md bg-red-50 px-4 py-2 text-sm text-red-700">{{ $error }}</p>
        @endif

        @if ($priced && $district)
            <form action="{{ route('checkout.place') }}" method="POST" class="mt-6 grid grid-cols-1 gap-8 sm:grid-cols-3">
                @csrf
                <input type="hidden" name="district" value="{{ $district }}">
                <input type="hidden" name="expected_total" value="{{ $priced['grand_total'] }}">

                <div class="sm:col-span-2 space-y-4 rounded-xl border border-stone-200 bg-white p-6">
                    <h2 class="font-medium text-stone-900">ঠিকানা</h2>
                    <div><label class="block text-sm text-stone-700">নাম</label>
                        <input name="name" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm text-stone-700">ফোন</label>
                        <input name="phone" value="{{ $customer->phone }}" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm text-stone-700">এলাকা</label>
                        <input name="area" class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm"></div>
                    <div><label class="block text-sm text-stone-700">সম্পূর্ণ ঠিকানা</label>
                        <textarea name="address_line" required class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-sm"></textarea></div>

                    <h2 class="pt-2 font-medium text-stone-900">পেমেন্ট</h2>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="radio" name="payment_method" value="cod" checked> ক্যাশ অন ডেলিভারি (COD)
                    </label>
                </div>

                <div class="space-y-2 rounded-xl border border-stone-200 bg-white p-6 text-sm">
                    <h2 class="font-medium text-stone-900">অর্ডার সামারি</h2>
                    <div class="flex justify-between"><span>সাবটোটাল</span><span>৳{{ number_format($priced['subtotal'] / 100, 0) }}</span></div>
                    <div class="flex justify-between"><span>ডেলিভারি</span><span>৳{{ number_format($priced['delivery_fee'] / 100, 0) }}</span></div>
                    @if ($priced['discount_total'] > 0)
                        <div class="flex justify-between text-green-700"><span>ছাড়</span><span>-৳{{ number_format($priced['discount_total'] / 100, 0) }}</span></div>
                    @endif
                    <div class="flex justify-between border-t border-stone-100 pt-2 font-semibold"><span>মোট</span><span>৳{{ number_format($priced['grand_total'] / 100, 0) }}</span></div>

                    <button type="submit" class="mt-3 w-full rounded-full bg-amber-700 py-2.5 font-medium text-white hover:bg-amber-800">অর্ডার কনফার্ম করুন</button>
                </div>
            </form>
        @endif
    @endif
</div>
@endsection
