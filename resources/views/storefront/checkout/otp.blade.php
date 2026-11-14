@extends('storefront.layout')

@section('title', 'OTP যাচাই — মধুবন')

@section('content')
<div class="max-w-sm mx-auto px-6 py-16">
    <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm">
        <h1 class="font-title-lg text-title-lg text-on-surface font-bold">OTP যাচাই করুন</h1>
        <p class="mt-2 font-body-sm text-body-sm text-on-surface-variant">{{ $phone }} নম্বরে একটি ৬ সংখ্যার কোড পাঠানো হয়েছে।</p>

        <form action="{{ route('checkout.otp.verify') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <input name="code" inputmode="numeric" maxlength="6" placeholder="৬ সংখ্যার কোড" required
                class="w-full rounded-full border border-outline-variant bg-surface-container-lowest px-4 py-3 text-center font-title-lg text-title-lg tracking-widest focus:outline-none focus:ring-1 focus:ring-primary-container">
            <button class="w-full py-2.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md">যাচাই করুন</button>
        </form>

        <form action="{{ route('checkout.otp.send') }}" method="POST" class="mt-3 text-center">
            @csrf
            <input type="hidden" name="phone" value="{{ $phone }}">
            <button class="font-label-sm text-label-sm text-primary hover:underline">আবার কোড পাঠান</button>
        </form>
    </div>
</div>
@endsection
