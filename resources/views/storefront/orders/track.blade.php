@extends('storefront.layout')

@section('title', 'অর্ডার ট্র্যাক করুন — Bazardev Ecom')

@section('content')
<div class="mx-auto max-w-lg px-4 py-16 sm:px-6">
    <h1 class="text-xl font-semibold text-stone-900">অর্ডার ট্র্যাক করুন</h1>
    <p class="mt-1 text-sm text-stone-500">অর্ডার নম্বর ও ফোন নম্বর দিয়ে খুঁজুন।</p>

    <form method="GET" class="mt-6 space-y-3">
        <input name="order_no" value="{{ request('order_no') }}" placeholder="ORD-2611-000123" required
            class="w-full rounded-md border border-stone-300 px-3 py-2 text-sm">
        <input name="phone" value="{{ request('phone') }}" placeholder="01XXXXXXXXX" required
            class="w-full rounded-md border border-stone-300 px-3 py-2 text-sm">
        <button class="w-full rounded-full bg-amber-700 py-2.5 font-medium text-white hover:bg-amber-800">খুঁজুন</button>
    </form>

    @if ($notFound)
        <p class="mt-6 rounded-md bg-red-50 px-4 py-2 text-sm text-red-700">এই নম্বরে কোনো অর্ডার পাওয়া যায়নি।</p>
    @endif

    @if ($order)
        <div class="mt-8 rounded-xl border border-stone-200 bg-white p-6">
            <p class="font-mono font-semibold text-amber-800">{{ $order->order_no }}</p>
            <p class="mt-1 text-sm text-stone-600">বর্তমান অবস্থা: <strong>{{ $order->status }}</strong></p>

            <ul class="mt-4 space-y-2 border-l-2 border-amber-200 pl-4 text-sm">
                @foreach ($order->statusHistory as $h)
                    <li>
                        <span class="font-medium text-stone-800">{{ $h->to }}</span>
                        <span class="text-stone-400"> — {{ \Illuminate\Support\Carbon::parse($h->created_at)->format('d M, h:i A') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
