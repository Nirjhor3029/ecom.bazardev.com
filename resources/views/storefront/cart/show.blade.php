@extends('storefront.layout')

@section('title', 'আপনার কার্ট — মধুবন')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-12 py-10">
    <div class="mb-8">
        <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">কেনাকাটা</span>
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold">আপনার কার্ট</h1>
    </div>

    @if ($cart->items->isEmpty())
        <div class="rounded-2xl bg-surface-container-low p-12 flex flex-col items-center text-center gap-3">
            <span class="material-symbols-outlined text-on-surface-variant text-[48px]">shopping_bag</span>
            <h2 class="font-title-lg text-title-lg text-on-surface font-semibold">আপনার কার্ট খালি</h2>
            <a href="{{ route('products.index') }}" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md hover:bg-primary hover:text-on-primary transition-colors">
                কেনাকাটা শুরু করুন <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    @else
        <div class="rounded-2xl bg-surface-container-lowest divide-y divide-surface-container-high overflow-hidden">
            @foreach ($cart->items as $item)
                <div class="flex items-center gap-4 p-4">
                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl bg-surface-container">
                        @if ($image = $item->variant->product->primaryImageUrl())
                            <img src="{{ $image }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-2xl">🍯</div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-title-md text-title-md text-on-surface truncate">{{ $item->variant->product->name['bn'] ?? '' }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $item->variant->options['weight'] ?? $item->variant->sku }} · ৳{{ number_format($item->variant->effectivePrice() / 100, 0) }}</p>
                    </div>
                    <form action="{{ route('cart.items.update', $item->id) }}" method="POST" class="flex items-center">
                        @csrf @method('PATCH')
                        <input type="number" name="qty" value="{{ $item->qty }}" min="0" max="10"
                            onchange="this.form.submit()" class="w-16 rounded-full border border-outline-variant bg-surface-container-lowest px-3 py-1.5 text-center font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                    </form>
                    <p class="w-24 text-right font-title-md text-title-md text-on-surface font-bold">৳{{ number_format($item->variant->effectivePrice() * $item->qty / 100, 0) }}</p>
                    <form action="{{ route('cart.items.destroy', $item->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="w-9 h-9 rounded-full flex items-center justify-center text-on-surface-variant hover:bg-error-container hover:text-on-error-container transition-colors"><span class="material-symbols-outlined text-[18px]">close</span></button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                @csrf
                <input name="code" value="{{ $cart->coupon_code }}" placeholder="কুপন কোড" class="rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
                <button class="px-5 py-2.5 rounded-full bg-surface-container text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-md text-label-md">প্রয়োগ করুন</button>
            </form>

            <div class="w-full sm:w-64 rounded-2xl bg-surface-container-low p-5 font-body-sm text-body-sm space-y-2">
                <div class="flex justify-between"><span class="text-on-surface-variant">সাবটোটাল</span><span class="text-on-surface">৳{{ number_format($priced['subtotal'] / 100, 0) }}</span></div>
                @if ($priced['discount_total'] > 0)
                    <div class="flex justify-between text-primary"><span>ছাড়</span><span>-৳{{ number_format($priced['discount_total'] / 100, 0) }}</span></div>
                @endif
                <div class="flex justify-between border-t border-surface-container-high pt-2 font-title-md text-title-md font-bold text-on-surface"><span>মোট</span><span>৳{{ number_format(($priced['subtotal'] - $priced['discount_total']) / 100, 0) }}</span></div>
                <p class="text-on-surface-variant/70">+ ডেলিভারি চার্জ (চেকআউটে দেখানো হবে)</p>
            </div>
        </div>

        <a href="{{ route('checkout.show') }}" class="mt-6 flex items-center justify-center gap-2 w-full py-3.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md shadow-[0_4px_16px_rgba(217,154,39,0.2)]">
            চেকআউট করুন <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    @endif
</div>
@endsection
