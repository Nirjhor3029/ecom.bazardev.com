@extends('storefront.layout')

@section('title', 'অর্ডার ট্র্যাক করুন — মধুবন')

@section('content')
<div class="max-w-lg mx-auto px-6 py-16">
    <div class="mb-6 text-center">
        <h1 class="font-headline-md text-headline-md text-on-surface font-bold">অর্ডার ট্র্যাক করুন</h1>
        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">অর্ডার নম্বর ও ফোন নম্বর দিয়ে খুঁজুন।</p>
    </div>

    <form method="GET" class="space-y-3 rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
        <input name="order_no" value="{{ request('order_no') }}" placeholder="ORD-2611-000123" required
            class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
        <input name="phone" value="{{ request('phone') }}" placeholder="01XXXXXXXXX" required
            class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-2.5 font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
        <button class="w-full py-2.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md">খুঁজুন</button>
    </form>

    @if ($notFound)
        <p class="mt-6 rounded-xl bg-error-container px-4 py-3 font-body-sm text-body-sm text-on-error-container">এই নম্বরে কোনো অর্ডার পাওয়া যায়নি।</p>
    @endif

    @if ($order)
        <div class="mt-6 rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
            <p class="font-title-md text-title-md text-primary font-bold">{{ $order->order_no }}</p>
            <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">বর্তমান অবস্থা: <strong class="text-on-surface">{{ $order->status }}</strong></p>

            <ul class="mt-4 space-y-2 border-l-2 border-primary-container pl-4 font-body-sm text-body-sm">
                @foreach ($order->statusHistory as $h)
                    <li>
                        <span class="font-bold text-on-surface">{{ $h->to }}</span>
                        <span class="text-on-surface-variant"> — {{ \Illuminate\Support\Carbon::parse($h->created_at)->format('d M, h:i A') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
