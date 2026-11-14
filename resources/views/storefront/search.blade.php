@extends('storefront.layout')

@section('title', '“'.$term.'” এর ফলাফল — মধুবন')

@php
    use App\Modules\Core\Support\Numerals as N;
    $selectedCats = array_map('intval', \Illuminate\Support\Arr::wrap(request('filter.category')));
    $total = $products->total();
    $giftCat = $categories->firstWhere('slug', 'gift-box') ?? $categories->last();
    $sorts = ['relevance' => 'প্রাসঙ্গিকতা (Relevance)', 'popular' => 'জনপ্রিয়', 'price_asc' => 'মূল্য: কম থেকে বেশি', 'price_desc' => 'মূল্য: বেশি থেকে কম', 'rating' => 'সর্বোচ্চ রেটিংপ্রাপ্ত', 'newest' => 'নতুন সংযোজন'];
    $priceMin = (int) (request('price_min') ?: $bounds['min']);
    $priceMax = (int) (request('price_max') ?: $bounds['max']);
    $matchCats = $categories->filter(fn ($c) => $c->products_count > 0)->values();
    $allMatches = $matchCats->sum('products_count');
    $clearUrl = route('search', ['q' => $term]);
    $fmt = fn ($paisa) => '৳'.number_format($paisa / 100, 0);
@endphp

@section('content')
@include('storefront.partials.shop-styles')
<div class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pt-space-md pb-space-xl flex flex-col gap-space-md lg:gap-space-lg">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 font-label-md text-label-md text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="{{ route('home') }}">হোম</a>
<span class="material-symbols-outlined text-[16px] text-tertiary">chevron_right</span>
<a class="hover:text-primary transition-colors" href="{{ route('products.index') }}">শপ</a>
<span class="material-symbols-outlined text-[16px] text-tertiary">chevron_right</span>
<span class="text-on-surface font-semibold truncate">“{{ $term }}”</span>
</nav>

<!-- Results header -->
<header class="relative bg-surface-container-low p-space-md lg:p-space-lg rounded-xl shadow-sm">
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-md">
<div>
<div class="flex items-center gap-3 mb-2 flex-wrap">
@if ($allMatches > 0)
<span class="px-3 py-0.5 rounded-full bg-surface text-primary font-label-sm text-label-sm shadow-sm inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">search_check</span>সফল অনুসন্ধান</span>
<span class="px-3 py-0.5 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm font-semibold">{{ N::bn($allMatches) }}টি পণ্য পাওয়া গেছে</span>
@else
<span class="px-3 py-0.5 rounded-full bg-surface text-tertiary font-label-sm text-label-sm shadow-sm inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">search_off</span>অনুসন্ধান ফলাফল শূন্য</span>
@endif
</div>
<h1 class="font-headline-md lg:font-headline-lg text-headline-md lg:text-headline-lg text-primary tracking-tight">“{{ $term }}” <span class="text-on-surface">এর ফলাফল</span></h1>
<p class="font-body-sm lg:font-body-md text-body-sm lg:text-body-md text-on-surface-variant mt-1">আপনার অনুসন্ধানের সাথে মিলে যাওয়া শতভাগ খাঁটি, অপরিশোধিত ও পরীক্ষিত প্রাকৃতিক পণ্যসমূহ।</p>
</div>
</div>
@if ($matchCats->isNotEmpty())
<div class="flex items-center gap-2 mt-space-md overflow-x-auto pb-1" style="scrollbar-width:none">
<a href="{{ route('search', ['q' => $term]) }}" class="px-4 py-1.5 rounded-full font-label-md text-label-md shadow-sm whitespace-nowrap {{ empty($selectedCats) ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface hover:bg-surface-container-highest transition-colors' }}">সব ফলাফল ({{ N::bn($allMatches) }})</a>
@foreach ($matchCats as $cat)
<a href="{{ route('search', ['q' => $term, 'filter' => ['category' => $cat->id]]) }}" class="px-4 py-1.5 rounded-full font-label-md text-label-md shadow-sm whitespace-nowrap {{ in_array($cat->id, $selectedCats, true) ? 'bg-primary text-on-primary' : 'bg-surface text-on-surface hover:bg-surface-container-highest transition-colors' }}">{{ $cat->name['bn'] ?? '' }} ({{ N::bn($cat->products_count) }})</a>
@endforeach
</div>
@endif
</header>

@if ($allMatches > 0)
<!-- Smart suggestions -->
<section class="p-space-md lg:p-space-lg rounded-xl bg-surface-container-lowest shadow-md" id="suggest-panel">
<div class="flex items-center justify-between pb-3 border-b border-surface-container">
<div class="flex items-center gap-2 flex-wrap"><span class="material-symbols-outlined text-primary text-[20px]">manage_search</span><span class="font-title-md text-title-md text-on-surface">স্মার্ট সার্চ সাজেশন</span><span class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-[10px]">খোঁজা শব্দ: “{{ $term }}”</span></div>
<button type="button" class="text-on-surface-variant hover:text-on-surface flex items-center gap-1 font-label-sm text-label-sm" onclick="var b=document.getElementById('suggest-body');b.classList.toggle('hidden');this.querySelector('.material-symbols-outlined').textContent=b.classList.contains('hidden')?'expand_more':'expand_less'">লুকান <span class="material-symbols-outlined text-[16px]">expand_less</span></button>
</div>
<div id="suggest-body" class="grid grid-cols-1 lg:grid-cols-12 gap-space-md mt-4">
<div class="lg:col-span-8 space-y-2.5">
<div class="font-label-sm text-label-sm uppercase tracking-wider text-tertiary">শীর্ষ প্রস্তাবিত পণ্য</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
@foreach ($top as $hit)
@php $range = $hit->priceRange(); @endphp
<a href="{{ route('products.show', $hit->slug) }}" class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container-low hover:bg-surface-container transition-colors group {{ $loop->last && $top->count() % 2 === 1 ? 'md:col-span-2' : '' }}">
@if ($img = $hit->primaryImageUrl())<img class="w-12 h-12 rounded-lg object-cover shrink-0 shadow-sm" alt="" src="{{ $img }}" loading="lazy">@else<div class="w-12 h-12 rounded-lg bg-surface-container shrink-0 flex items-center justify-center">🍯</div>@endif
<div class="flex-1 min-w-0">
<p class="font-title-md text-body-md text-on-surface font-semibold group-hover:text-primary truncate">{{ $hit->name['bn'] ?? '' }}</p>
<div class="flex items-center gap-2 font-label-sm text-label-sm text-on-surface-variant"><span class="text-primary font-bold">{{ $fmt($range['min']) }}</span><span>•</span><span class="truncate">{{ $hit->category?->name['bn'] ?? '' }}</span></div>
</div>
<span class="material-symbols-outlined text-outline group-hover:text-primary text-[18px]">arrow_forward</span>
</a>
@endforeach
</div>
</div>
<div class="lg:col-span-4 bg-surface-container-low p-4 rounded-xl flex flex-col justify-between">
<div>
<div class="font-label-sm text-label-sm uppercase tracking-wider text-tertiary mb-3">ক্যাটাগরি মিল</div>
<div class="flex flex-col gap-2">
@foreach ($matchCats as $cat)
<a class="flex items-center justify-between p-2 rounded-lg bg-surface hover:bg-surface-container-highest transition-colors" href="{{ route('search', ['q' => $term, 'filter' => ['category' => $cat->id]]) }}"><span class="font-body-md text-body-md text-on-surface">{{ $cat->name['bn'] ?? '' }}</span><span class="font-label-sm text-label-sm text-on-surface-variant">{{ N::bn($cat->products_count) }}টি পণ্য</span></a>
@endforeach
</div>
</div>
<a href="#results" class="mt-4 w-full py-2.5 px-4 rounded-full bg-primary hover:bg-secondary text-on-primary text-center font-label-md text-label-md transition-all shadow-sm flex items-center justify-center gap-2"><span>সকল {{ N::bn($allMatches) }}টি ফলাফল দেখুন</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
</div>
<div class="lg:col-span-12 flex items-center gap-2 flex-wrap">
<span class="font-label-sm text-label-sm text-on-surface-variant">জনপ্রিয়:</span>
@foreach ($popularTerms as $pt)<a href="{{ route('search', ['q' => $pt]) }}" class="px-3 py-1 rounded-full bg-surface-container hover:bg-surface-container-high text-on-surface font-label-sm text-label-sm transition-colors">{{ $pt }}</a>@endforeach
</div>
</div>
</section>
@endif

<!-- Active filters bar -->
<div class="flex flex-wrap items-center justify-between gap-3 p-space-sm lg:p-4 rounded-xl bg-surface-container-lowest shadow-sm">
<div class="flex flex-wrap items-center gap-2">
<span class="font-label-sm text-label-sm text-tertiary uppercase mr-1 tracking-wider">সক্রিয় ফিল্টার:</span>
<a href="{{ route('products.index') }}" class="px-3 py-1 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm hover:bg-surface-container-highest flex items-center gap-1.5 transition-colors"><span>অনুসন্ধান: {{ $term }}</span><span class="material-symbols-outlined text-[14px]">close</span></a>
@foreach ($chips as $chip)
<a href="{{ $chip['url'] }}" class="px-3 py-1 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm hover:bg-surface-container-highest flex items-center gap-1.5 transition-colors"><span>{{ $chip['label'] }}</span><span class="material-symbols-outlined text-[14px]">close</span></a>
@endforeach
</div>
@if (count($chips))
<a href="{{ $clearUrl }}" class="text-primary hover:text-secondary font-label-md text-label-md underline flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">filter_alt_off</span><span>সব মুছুন (Clear All)</span></a>
@endif
</div>

<div class="w-full flex flex-col lg:flex-row gap-space-lg items-start" id="results">
@include('storefront.partials.shop-filters')
<div class="flex-1 w-full min-w-0 flex flex-col gap-space-md">
<!-- Toolbar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm bg-surface-container-lowest p-space-sm lg:p-4 rounded-xl shadow-sm">
<div class="font-title-md text-title-md text-on-surface">{{ N::bn($total) }}টি পণ্য প্রদর্শিত হচ্ছে <span class="hidden sm:inline font-label-md text-on-surface-variant">| পৃষ্ঠা {{ N::bn($products->currentPage()) }}/{{ N::bn(max($products->lastPage(), 1)) }}</span></div>
<div class="flex items-center gap-space-sm w-full sm:w-auto">
<button type="button" id="open-filters" class="lg:hidden flex items-center gap-1.5 bg-surface-container-low px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface shadow-sm"><span class="material-symbols-outlined text-[18px] text-primary">tune</span>ফিল্টার@if (count($chips))<span class="w-5 h-5 rounded-full bg-primary text-on-primary text-[11px] flex items-center justify-center">{{ N::bn(count($chips)) }}</span>@endif</button>
<form action="{{ route('search') }}" method="GET" class="relative flex-1 min-w-0 sm:flex-none">
@foreach (request()->except(['sort', 'page']) as $k => $v)
@if (is_array($v))@foreach (\Illuminate\Support\Arr::dot([$k => $v]) as $dk => $dv)<input type="hidden" name="{{ preg_replace('/\.(\w+)/', '[$1]', $dk) }}" value="{{ $dv }}">@endforeach @else<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
@endforeach
<select name="sort" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low text-on-surface font-label-md text-label-md pl-space-md pr-8 py-space-xs rounded-full cursor-pointer focus:outline-none focus:ring-1 focus:ring-primary shadow-sm">
@foreach ($sorts as $key => $text)<option value="{{ $key }}" {{ $sort === $key ? 'selected' : '' }}>সাজান: {{ $text }}</option>@endforeach
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px] pointer-events-none">expand_more</span>
</form>
<div class="hidden sm:flex items-center bg-surface-container p-0.5 rounded-lg">
<button type="button" id="view-grid" aria-label="গ্রিড ভিউ" class="w-8 h-8 rounded bg-surface-container-lowest text-primary shadow-sm flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">grid_view</span></button>
<button type="button" id="view-list" aria-label="লিস্ট ভিউ" class="w-8 h-8 rounded text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[18px]">view_agenda</span></button>
</div>
</div>
</div>

@if ($products->isEmpty())
<section class="p-space-lg lg:p-space-xl rounded-2xl bg-surface-container-low shadow-sm text-center">
<div class="max-w-xl mx-auto flex flex-col items-center">
<div class="w-20 h-20 rounded-full bg-surface-container-highest flex items-center justify-center text-primary mb-6"><span class="material-symbols-outlined text-[40px]">travel_explore</span></div>
<span class="px-3 py-1 rounded-full bg-surface text-tertiary font-label-sm text-label-sm mb-3">অনুসন্ধান ফলাফল শূন্য</span>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">কোনো পণ্য খুঁজে পাওয়া যায়নি</h3>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed mb-6">‘<span class="text-primary font-semibold">{{ $term }}</span>’ দিয়ে আমাদের ক্যাটালগে কোনো পণ্য মেলেনি। দয়া করে বানানের সঠিকতা পরীক্ষা করুন অথবা নিচে জনপ্রিয় ক্যাটাগরিগুলো ব্রাউজ করুন।</p>
<div class="flex flex-wrap items-center justify-center gap-2 mb-8">
@foreach ($categories as $cat)<a href="{{ route('products.index', ['filter' => ['category' => $cat->id]]) }}" class="px-4 py-2 rounded-full bg-surface hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors shadow-sm">{{ $cat->name['bn'] ?? '' }}</a>@endforeach
</div>
<a href="{{ $allMatches > 0 ? $clearUrl : route('products.index') }}" class="px-6 py-2.5 rounded-full bg-primary text-on-primary hover:bg-secondary font-label-md text-label-md transition-all shadow-sm flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">replay</span><span>{{ $allMatches > 0 ? 'ফিল্টার মুছে ফিরে যান' : 'সব পণ্য দেখুন' }}</span></a>
</div>
</section>
@else
<div id="shop-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-space-sm sm:gap-space-md">
@foreach ($products as $product)
@include('storefront.partials.shop-card', ['product' => $product])
@endforeach
</div>

<!-- Promo banner -->
<div class="rounded-xl overflow-hidden bg-primary text-on-primary p-space-md lg:p-space-lg shadow-md relative flex flex-col md:flex-row items-center justify-between gap-space-md">
<div class="space-y-2 max-w-xl z-10">
<span class="font-label-sm text-label-sm px-3 py-1 rounded-full bg-on-primary/10 text-on-primary border border-on-primary/20 inline-block uppercase tracking-wider">মৌয়ালদের সরাসরি অংশীদারিত্ব</span>
<h3 class="font-headline-sm lg:font-headline-md text-headline-sm lg:text-headline-md tracking-tight">প্রাকৃতিক মধুর বিশুদ্ধ স্বাদ — সুন্দরবনের গহীন অরণ্য থেকে সরাসরি আপনার টেবিলে</h3>
<p class="font-body-md text-body-md text-on-primary/80 hidden sm:block">কোনো প্রকার উত্তাপ বা পরিশোধনের ছোঁয়া ছাড়া সংগৃহীত কাঁচা বুনো মধু। প্রকৃতির আসল পুষ্টিগুণ অক্ষত প্রতিটি বোতলে।</p>
</div>
<a href="{{ route('products.index') }}" class="shrink-0 px-6 py-3 rounded-full bg-surface text-primary hover:bg-surface-container-high font-label-md text-label-md shadow-sm transition-all flex items-center gap-2"><span>আমাদের সংগ্রহ দেখুন</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
</div>
@include('storefront.partials.shop-pagination')
@endif
</div>
</div>

<!-- Discovery -->
@if ($discover->isNotEmpty())
<section class="mt-space-lg pt-space-md border-t border-surface-container">
<div class="flex items-center justify-between mb-space-md">
<div><span class="font-label-sm text-label-sm uppercase tracking-wider text-tertiary">সঙ্গী পণ্য</span><h2 class="font-headline-sm lg:font-headline-md text-headline-sm lg:text-headline-md text-on-surface">জনপ্রিয় ও প্রায়শই খোঁজা পণ্য</h2></div>
<a href="{{ route('products.index') }}" class="text-primary hover:underline font-label-md text-label-md flex items-center gap-1"><span>সব পণ্য দেখুন</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
@foreach ($discover as $d)
@php $dv = $d->defaultVariant(); $dr = $d->priceRange(); @endphp
<div class="flex items-center gap-4 p-4 rounded-xl bg-surface-container-lowest shadow-sm hover:shadow-md transition-all group">
<a href="{{ route('products.show', $d->slug) }}" class="shrink-0">@if ($img = $d->primaryImageUrl())<img class="w-20 h-20 rounded-lg object-cover" alt="" src="{{ $img }}" loading="lazy">@else<div class="w-20 h-20 rounded-lg bg-surface-container flex items-center justify-center text-3xl">🍯</div>@endif</a>
<div class="min-w-0 flex-1">
<h4 class="font-title-md text-title-md text-on-surface group-hover:text-primary truncate"><a href="{{ route('products.show', $d->slug) }}">{{ $d->name['bn'] ?? '' }}</a></h4>
<span class="font-label-sm text-label-sm text-on-surface-variant block truncate">{{ $dv?->weight_grams ? N::bn($dv->weight_grams).' গ্রাম • ' : '' }}{{ $d->category?->name['bn'] ?? '' }}</span>
<div class="flex items-center justify-between mt-2">
<span class="font-title-md text-primary font-bold">{{ $fmt($dr['min']) }}</span>
<form action="{{ route('cart.items.store') }}" method="POST">@csrf<input type="hidden" name="variant_id" value="{{ $dv?->id }}"><input type="hidden" name="qty" value="1"><button type="submit" aria-label="কার্টে যোগ করুন" class="w-8 h-8 rounded-full bg-surface-container hover:bg-primary hover:text-on-primary flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[16px]">add_shopping_cart</span></button></form>
</div>
</div>
</div>
@endforeach
</div>
</section>
@endif
</div>
@include('storefront.partials.shop-scripts')
@endsection
