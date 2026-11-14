@extends('storefront.layout')

@section('title', 'অর্ডার সফল — মধুবন')

@section('content')
<div class="max-w-lg mx-auto px-6 py-16 text-center">
    <div class="w-16 h-16 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center mx-auto mb-4">
        <span class="material-symbols-outlined text-[32px]">check_circle</span>
    </div>
    <h1 class="font-headline-md text-headline-md text-on-surface font-bold">ধন্যবাদ! আপনার অর্ডার নেওয়া হয়েছে।</h1>
    <p class="mt-2 font-body-md text-body-md text-on-surface-variant">অর্ডার নম্বর: <span class="font-bold text-primary">{{ $order->order_no }}</span></p>
    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">মোট: ৳{{ number_format($order->grand_total / 100, 0) }} ({{ $order->payment_method === 'cod' ? 'ক্যাশ অন ডেলিভারি' : $order->payment_method }})</p>

    <div class="mt-8 rounded-2xl bg-surface-container-lowest p-6 text-left shadow-sm">
        <h2 class="font-title-md text-title-md text-on-surface font-bold mb-2">অর্ডার এর বিবরণ</h2>
        @foreach ($order->items as $item)
            <div class="mt-2 flex justify-between font-body-sm text-body-sm text-on-surface-variant">
                <span>{{ $item->name_snapshot['bn'] ?? '' }} × {{ $item->qty }}</span>
                <span class="text-on-surface">৳{{ number_format($item->line_total / 100, 0) }}</span>
            </div>
        @endforeach
    </div>

    <a href="{{ route('orders.track', ['order_no' => $order->order_no]) }}" class="mt-6 inline-flex items-center gap-2 text-primary font-label-md text-label-md hover:underline">
        অর্ডার ট্র্যাক করুন <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
    </a>
</div>
@endsection
