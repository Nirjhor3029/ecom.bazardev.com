@extends('storefront.layout')

@section('title', 'OTP যাচাই — Bazardev Ecom')

@section('content')
<div class="mx-auto max-w-sm px-4 py-16 sm:px-6">
    <h1 class="text-xl font-semibold text-stone-900">OTP যাচাই করুন</h1>
    <p class="mt-2 text-sm text-stone-500">{{ $phone }} নম্বরে একটি ৬ সংখ্যার কোড পাঠানো হয়েছে।</p>

    <form action="{{ route('checkout.otp.verify') }}" method="POST" class="mt-6 space-y-4">
        @csrf
        <input name="code" inputmode="numeric" maxlength="6" placeholder="৬ সংখ্যার কোড" required
            class="w-full rounded-md border border-stone-300 px-3 py-2 text-center text-lg tracking-widest">
        <button class="w-full rounded-full bg-amber-700 py-2.5 font-medium text-white hover:bg-amber-800">যাচাই করুন</button>
    </form>

    <form action="{{ route('checkout.otp.send') }}" method="POST" class="mt-3">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">
        <button class="text-sm text-amber-700 hover:underline">আবার কোড পাঠান</button>
    </form>
</div>
@endsection
