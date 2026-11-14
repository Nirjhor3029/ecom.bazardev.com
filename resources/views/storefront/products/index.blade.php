@extends('storefront.layout')

@section('title', 'সব পণ্য — Bazardev Ecom')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
    <form class="mb-6 flex gap-2 sm:hidden">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="খুঁজুন…" class="flex-1 rounded-full border border-amber-200 px-4 py-2 text-sm">
    </form>

    <div class="grid grid-cols-1 gap-8 sm:grid-cols-4">
        <!-- Filters -->
        <aside class="sm:col-span-1">
            <h2 class="font-medium text-stone-900">ক্যাটাগরি</h2>
            <ul class="mt-2 space-y-1 text-sm">
                <li><a href="{{ route('products.index') }}" class="{{ request('filter.category') ? 'text-stone-600' : 'font-medium text-amber-700' }} hover:text-amber-700">সব</a></li>
                @foreach ($categories as $category)
                    <li><a href="{{ route('products.index', ['filter' => ['category' => $category->id]]) }}"
                        class="{{ request('filter.category') == $category->id ? 'font-medium text-amber-700' : 'text-stone-600' }} hover:text-amber-700">
                        {{ $category->name['bn'] ?? '' }}
                    </a></li>
                @endforeach
            </ul>

            @foreach ($facets as $facet)
                @if (count($facet['options']))
                    <h2 class="mt-6 font-medium text-stone-900">{{ $facet['label']['bn'] ?? $facet['code'] }}</h2>
                    <ul class="mt-2 space-y-1 text-sm">
                        @foreach ($facet['options'] as $opt)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->query(), ['filter' => array_merge(request('filter', []), [$facet['code'] => $opt['value']])])) }}"
                                   class="text-stone-600 hover:text-amber-700">
                                    {{ $opt['value'] }} <span class="text-stone-400">({{ $opt['count'] }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </aside>

        <!-- Grid -->
        <div class="sm:col-span-3">
            <p class="mb-4 text-sm text-stone-500">{{ $products->total() }} টি পণ্য পাওয়া গেছে</p>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                @forelse ($products as $product)
                    @include('storefront.partials.product-card', ['product' => $product])
                @empty
                    <p class="col-span-full py-12 text-center text-stone-400">কোনো পণ্য পাওয়া যায়নি।</p>
                @endforelse
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        </div>
    </div>
</div>
@endsection
