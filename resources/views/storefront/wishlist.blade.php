@extends('storefront.layout')

@section('title', 'আমার উইশলিস্ট — মধুবন')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
    <div class="mb-8">
        <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">আপনার পছন্দ</span>
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold">আমার উইশলিস্ট</h1>
    </div>

    @if ($products->isEmpty())
        <div class="rounded-2xl bg-surface-container-low p-12 flex flex-col items-center text-center gap-3">
            <span class="material-symbols-outlined text-on-surface-variant text-[48px]">favorite_border</span>
            <h2 class="font-title-lg text-title-lg text-on-surface font-semibold">আপনার উইশলিস্ট খালি</h2>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-md">পণ্যের পাশে হার্ট আইকনে ক্লিক করে পছন্দের পণ্য এখানে যুক্ত করুন।</p>
            <a href="{{ route('products.index') }}" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md hover:bg-primary hover:text-on-primary transition-colors">
                পণ্য দেখুন
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($products as $product)
                <div class="flex flex-col gap-2">
                    @include('storefront.partials.product-card', ['product' => $product])
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm hover:bg-error-container hover:text-on-error-container transition-colors">
                            উইশলিস্ট থেকে সরান
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
