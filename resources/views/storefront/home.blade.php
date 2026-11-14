@extends('storefront.layout')

@section('title', 'Bazardev Ecom — সুন্দরবনের খাঁটি মধু ও বাদাম')

@section('content')
<!-- Hero -->
<section class="bg-gradient-to-b from-amber-50 to-stone-50">
    <div class="mx-auto max-w-6xl px-4 py-16 text-center sm:px-6 sm:py-24">
        <h1 class="font-serif text-3xl font-bold tracking-tight text-amber-900 sm:text-5xl">সুন্দরবনের খাঁটি মধু,<br>আপনার দরজায়</h1>
        <p class="mx-auto mt-4 max-w-xl text-stone-600">ল্যাব-টেস্টেড, ১০০% প্রাকৃতিক মধু ও বাদাম — ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে।</p>
        <a href="{{ route('products.index') }}" class="mt-8 inline-block rounded-full bg-amber-700 px-8 py-3 font-medium text-white hover:bg-amber-800">কেনাকাটা শুরু করুন</a>
    </div>
</section>

<!-- Categories -->
@if ($categories->isNotEmpty())
<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <h2 class="text-lg font-semibold text-stone-900">ক্যাটাগরি</h2>
    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-6">
        @foreach ($categories as $category)
            <a href="{{ route('products.index', ['filter' => ['category' => $category->id]]) }}"
               class="flex flex-col items-center gap-2 rounded-xl border border-amber-100 bg-white p-4 text-center hover:border-amber-300">
                <span class="text-2xl">🍯</span>
                <span class="text-sm font-medium text-stone-700">{{ $category->name['bn'] ?? '' }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- Products -->
<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-stone-900">নতুন পণ্য</h2>
        <a href="{{ route('products.index') }}" class="text-sm text-amber-700 hover:underline">সব দেখুন →</a>
    </div>
    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
        @foreach ($products as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>

<!-- Our story -->
<section class="bg-amber-50">
    <div class="mx-auto max-w-4xl px-4 py-14 text-center sm:px-6">
        <h2 class="font-serif text-2xl font-semibold text-amber-900">আমাদের গল্প</h2>
        <p class="mx-auto mt-3 max-w-2xl text-stone-600">
            আমরা সরাসরি সুন্দরবনের মৌয়ালদের থেকে মধু সংগ্রহ করি — কোনো মধ্যস্বত্বভোগী নেই, কোনো ভেজাল নেই।
            প্রতিটি ব্যাচ ল্যাবে পরীক্ষা করা হয় যাতে আপনি পান সত্যিকারের খাঁটি মধু।
        </p>
    </div>
</section>
@endsection
