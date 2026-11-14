@extends('storefront.layout')

@section('title', 'কার্ট — Bazardev Ecom')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6">
    <h1 class="text-xl font-semibold text-stone-900">আপনার কার্ট</h1>

    @if ($cart->items->isEmpty())
        <div class="mt-10 text-center text-stone-400">
            <p>আপনার কার্ট খালি।</p>
            <a href="{{ route('products.index') }}" class="mt-3 inline-block text-amber-700 hover:underline">কেনাকাটা শুরু করুন →</a>
        </div>
    @else
        <div class="mt-6 divide-y divide-stone-100 rounded-xl border border-stone-100 bg-white">
            @foreach ($cart->items as $item)
                <div class="flex items-center gap-4 p-4">
                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-amber-50">
                        @if ($image = $item->variant->product->primaryImageUrl())
                            <img src="{{ $image }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-2xl">🍯</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-stone-900">{{ $item->variant->product->name['bn'] ?? '' }}</p>
                        <p class="text-sm text-stone-500">{{ $item->variant->options['weight'] ?? $item->variant->sku }} · ৳{{ number_format($item->variant->effectivePrice() / 100, 0) }}</p>
                    </div>
                    <form action="{{ route('cart.items.update', $item->id) }}" method="POST" class="flex items-center">
                        @csrf @method('PATCH')
                        <input type="number" name="qty" value="{{ $item->qty }}" min="0" max="10"
                            onchange="this.form.submit()" class="w-16 rounded-md border border-stone-300 px-2 py-1 text-sm">
                    </form>
                    <p class="w-24 text-right font-medium text-stone-900">৳{{ number_format($item->variant->effectivePrice() * $item->qty / 100, 0) }}</p>
                    <form action="{{ route('cart.items.destroy', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-stone-400 hover:text-red-600">✕</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-between gap-6">
            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                @csrf
                <input name="code" value="{{ $cart->coupon_code }}" placeholder="কুপন কোড" class="rounded-md border border-stone-300 px-3 py-2 text-sm">
                <button class="rounded-md border border-stone-300 px-4 py-2 text-sm hover:bg-stone-50">প্রয়োগ করুন</button>
            </form>

            <div class="w-56 space-y-1 text-sm">
                <div class="flex justify-between"><span>সাবটোটাল</span><span>৳{{ number_format($priced['subtotal'] / 100, 0) }}</span></div>
                @if ($priced['discount_total'] > 0)
                    <div class="flex justify-between text-green-700"><span>ছাড়</span><span>-৳{{ number_format($priced['discount_total'] / 100, 0) }}</span></div>
                @endif
                <div class="flex justify-between border-t border-stone-100 pt-1 font-semibold"><span>মোট</span><span>৳{{ number_format(($priced['subtotal'] - $priced['discount_total']) / 100, 0) }}</span></div>
                <p class="text-xs text-stone-400">+ ডেলিভারি চার্জ (চেকআউটে দেখানো হবে)</p>
            </div>
        </div>

        <a href="{{ route('checkout.show') }}" class="mt-6 block rounded-full bg-amber-700 py-3 text-center font-medium text-white hover:bg-amber-800">চেকআউট করুন →</a>
    @endif
</div>
@endsection
