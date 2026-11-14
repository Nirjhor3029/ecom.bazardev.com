@extends('storefront.layout')

@section('title', 'সব পণ্য — মধুবন')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
    <div class="mb-8">
        <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">সংগ্রহশালা</span>
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold">সব পণ্য</h1>
    </div>

    <form class="mb-6 lg:hidden" action="{{ route('products.index') }}">
        <div class="relative w-full flex items-center">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="খুঁজুন…" class="w-full bg-surface-container-lowest rounded-full py-2.5 pl-5 pr-11 text-on-surface placeholder:text-on-surface-variant/70 font-body-sm text-body-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-primary-container">
            <button type="submit" class="absolute right-1.5 p-1.5 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">search</span></button>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Filters --}}
        <aside class="lg:col-span-1">
            <div class="rounded-2xl bg-surface-container-low p-5">
                <h2 class="font-title-md text-title-md text-on-surface font-bold mb-3">ক্যাটাগরি</h2>
                <ul class="space-y-1 font-body-sm text-body-sm">
                    <li>
                        <a href="{{ route('products.index') }}" class="block px-3 py-1.5 rounded-full transition-colors {{ request('filter.category') ? 'text-on-surface-variant hover:bg-surface-container-high' : 'bg-primary-container text-on-primary-container font-bold' }}">সব</a>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('products.index', ['filter' => ['category' => $category->id]]) }}" class="block px-3 py-1.5 rounded-full transition-colors {{ request('filter.category') == $category->id ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' }}">{{ $category->name['bn'] ?? '' }}</a>
                        </li>
                    @endforeach
                </ul>

                @foreach ($facets as $facet)
                    @if (count($facet['options']))
                        <h2 class="font-title-md text-title-md text-on-surface font-bold mt-6 mb-3">{{ $facet['label']['bn'] ?? $facet['code'] }}</h2>
                        <ul class="space-y-1 font-body-sm text-body-sm">
                            @foreach ($facet['options'] as $opt)
                                <li>
                                    <a href="{{ route('products.index', array_merge(request()->query(), ['filter' => array_merge(request('filter', []), [$facet['code'] => $opt['value']])])) }}" class="flex items-center justify-between px-3 py-1.5 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors">
                                        <span>{{ $opt['value'] }}</span>
                                        <span class="text-on-surface-variant/70">({{ $opt['count'] }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </div>
        </aside>

        {{-- Grid --}}
        <div class="lg:col-span-3">
            <p class="mb-4 font-body-sm text-body-sm text-on-surface-variant">{{ $products->total() }} টি পণ্য পাওয়া গেছে</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @forelse ($products as $product)
                    @include('storefront.partials.product-card', ['product' => $product])
                @empty
                    <p class="col-span-full text-center font-body-md text-body-md text-on-surface-variant py-12">কোনো পণ্য পাওয়া যায়নি।</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        </div>
    </div>
</div>
@endsection
