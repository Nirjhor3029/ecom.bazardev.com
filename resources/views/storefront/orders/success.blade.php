@extends('storefront.layout')

@section('title', 'অর্ডার সফল — Bazardev Ecom')

@section('content')
<div class="mx-auto max-w-lg px-4 py-16 text-center sm:px-6">
    <div class="text-5xl">🎉</div>
    <h1 class="mt-4 text-xl font-semibold text-stone-900">ধন্যবাদ! আপনার অর্ডার নেওয়া হয়েছে।</h1>
    <p class="mt-2 text-stone-600">অর্ডার নম্বর: <span class="font-mono font-semibold text-amber-800">{{ $order->order_no }}</span></p>
    <p class="mt-1 text-sm text-stone-500">মোট: ৳{{ number_format($order->grand_total / 100, 0) }} ({{ $order->payment_method === 'cod' ? 'ক্যাশ অন ডেলিভারি' : $order->payment_method }})</p>

    <div class="mt-8 rounded-xl border border-stone-200 bg-white p-6 text-left">
        <h2 class="font-medium text-stone-900">অর্ডার এর বিবরণ</h2>
        @foreach ($order->items as $item)
            <div class="mt-2 flex justify-between text-sm">
                <span>{{ $item->name_snapshot['bn'] ?? '' }} × {{ $item->qty }}</span>
                <span>৳{{ number_format($item->line_total / 100, 0) }}</span>
            </div>
        @endforeach
    </div>

    <a href="{{ route('orders.track', ['order_no' => $order->order_no]) }}" class="mt-6 inline-block text-amber-700 hover:underline">অর্ডার ট্র্যাক করুন →</a>
</div>
@endsection
