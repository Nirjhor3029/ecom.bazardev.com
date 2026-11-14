@extends('storefront.layout')

@section('title', 'সব পণ্য — মধুবন')

@php
    use App\Modules\Core\Support\Numerals as N;
    $selectedCats = array_map('intval', \Illuminate\Support\Arr::wrap(request('filter.category')));
    $onlyCat = count($selectedCats) === 1 ? $categories->firstWhere('id', $selectedCats[0]) : null;
    $total = $products->total();
    $giftCat = $categories->firstWhere('slug', 'gift-box') ?? $categories->last();
    $sorts = ['popular' => 'জনপ্রিয় ও রেকমেন্ডেড', 'price_asc' => 'মূল্য: কম থেকে বেশি', 'price_desc' => 'মূল্য: বেশি থেকে কম', 'rating' => 'সর্বোচ্চ রেটিংপ্রাপ্ত', 'newest' => 'নতুন সংযোজন'];
    $priceMin = (int) (request('price_min') ?: $bounds['min']);
    $priceMax = (int) (request('price_max') ?: $bounds['max']);
    $hasFilters = count($chips) > 0;
@endphp

@section('content')
<style>
  .shop-card .card-media { transition: width .2s; }
  .list-mode { grid-template-columns: 1fr !important; }
  .list-mode .shop-card { flex-direction: row; gap: 1rem; }
  .list-mode .shop-card .card-media { width: 11rem; flex-shrink: 0; margin-bottom: 0; }
  .list-mode .shop-card > div:last-child { flex: 1; }
  .dual-range { position: relative; height: 1.25rem; }
  .dual-range input[type=range] { position: absolute; inset: 0; width: 100%; margin: 0; background: none; pointer-events: none; -webkit-appearance: none; appearance: none; }
  .dual-range input[type=range]::-webkit-slider-thumb { pointer-events: auto; -webkit-appearance: none; width: 1rem; height: 1rem; border-radius: 9999px; background: #fff; border: 2px solid #805600; box-shadow: 0 1px 4px rgba(0,0,0,.2); cursor: pointer; }
  .dual-range input[type=range]::-moz-range-thumb { pointer-events: auto; width: .75rem; height: .75rem; border-radius: 9999px; background: #fff; border: 2px solid #805600; cursor: pointer; }
  .dual-range input[type=range]::-webkit-slider-runnable-track { background: transparent; }
  .dual-range input[type=range]::-moz-range-track { background: transparent; }
  @media (max-width: 1023px) {
    #shop-filters { display: none; position: fixed; inset: 0; z-index: 90; background: rgba(31,27,21,.55); align-items: flex-end; }
    #shop-filters.open { display: flex; }
    #shop-filters > form { width: 100%; max-height: 88vh; overflow-y: auto; border-radius: 1.25rem 1.25rem 0 0; }
  }
</style>
<div class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pt-space-md pb-space-xl flex flex-col gap-space-md lg:gap-space-lg">
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="flex items-center gap-space-xs font-label-md text-label-md text-on-surface-variant">
<a class="hover:text-primary transition-colors flex items-center gap-1" href="{{ route('home') }}"><span class="material-symbols-outlined text-[16px]">home</span><span>হোম</span></a>
<span class="text-outline-variant text-[12px] font-bold">/</span>
@if ($onlyCat)
<a class="hover:text-primary transition-colors" href="{{ route('products.index') }}">শপ</a>
<span class="text-outline-variant text-[12px] font-bold">/</span>
<span class="text-on-surface font-semibold">{{ $onlyCat->name['bn'] ?? '' }}</span>
@else
<span class="text-on-surface font-semibold">শপ ও ক্যাটালগ (Shop)</span>
@endif
</nav>

<!-- Hero banner -->
<div class="relative w-full rounded-xl bg-gradient-to-r from-secondary-container via-surface-container to-primary-fixed/40 overflow-hidden shadow-sm flex flex-col md:flex-row items-center justify-between p-space-md lg:p-space-lg gap-space-md">
<div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-primary-container/20 blur-3xl pointer-events-none"></div>
<div class="absolute left-1/3 -bottom-10 w-48 h-48 rounded-full bg-tertiary-fixed/30 blur-2xl pointer-events-none"></div>
<div class="relative z-10 flex flex-col gap-space-xs max-w-2xl text-center md:text-left">
<div class="inline-flex items-center gap-space-xs bg-surface/80 backdrop-blur-sm self-center md:self-start px-space-sm py-1 rounded-full shadow-sm">
<span class="material-symbols-outlined text-primary text-[15px]" style="font-variation-settings: 'FILL' 1;">verified</span>
<span class="font-label-sm text-label-sm text-on-surface">১০০% ল্যাব টেস্টেড ও খাঁটি উৎস | ক্যাশ অন ডেলিভারি</span>
</div>
<h1 class="font-headline-md text-headline-md text-on-surface font-semibold tracking-tight">{{ $onlyCat ? ($onlyCat->name['bn'] ?? '') : 'প্রাকৃতিক ও খাঁটি খাবারের সংগ্রহ' }}</h1>
<p class="font-body-sm md:font-body-md text-body-sm md:text-body-md text-on-surface-variant line-clamp-2 md:line-clamp-none">আপনার পরিবারের জন্য বেছে নিন স্বাস্থ্যসম্মত প্রাকৃতিক কাঁচা মধু, প্রিমিয়াম ক্রিস্পি বাদাম ও বিশেষ রাজকীয় গিফট প্যাক।</p>
</div>
<div class="relative z-10 hidden md:flex items-center gap-space-md bg-surface-container-lowest/90 backdrop-blur-md px-space-md py-space-sm rounded-xl shadow-sm self-stretch md:self-auto justify-around">
<div class="flex flex-col items-center text-center"><span class="font-headline-sm text-headline-sm text-primary font-bold">{{ N::bn($stats['total']) }}+</span><span class="font-label-sm text-label-sm text-on-surface-variant">প্রাকৃতিক ভ্যারাইটি</span></div>
<div class="w-px h-8 bg-surface-variant"></div>
<div class="flex flex-col items-center text-center"><span class="font-headline-sm text-headline-sm text-primary font-bold">০%</span><span class="font-label-sm text-label-sm text-on-surface-variant">ভেজাল বা কেমিক্যাল</span></div>
<div class="w-px h-8 bg-surface-variant"></div>
<div class="flex flex-col items-center text-center"><span class="font-headline-sm text-headline-sm text-primary font-bold">{{ $stats['avg'] ? N::bn(number_format($stats['avg'], 1)).'★' : '—' }}</span><span class="font-label-sm text-label-sm text-on-surface-variant">গ্রাহক সন্তুষ্টি</span></div>
</div>
</div>

<!-- Category tiles -->
<div class="w-full flex items-center gap-space-sm overflow-x-auto pb-space-xs -mx-gutter-mobile px-gutter-mobile lg:mx-0 lg:px-0" style="scrollbar-width:none">
@php $allActive = empty($selectedCats); @endphp
<a href="{{ route('products.index', array_filter(['q' => request('q'), 'sort' => request('sort')])) }}" class="flex items-center gap-space-sm px-space-md py-space-xs rounded-xl shadow-sm shrink-0 transition-transform hover:-translate-y-0.5 text-left {{ $allActive ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container' }}">
<div class="w-10 h-10 rounded-lg shrink-0 flex items-center justify-center {{ $allActive ? 'bg-primary-container' : 'bg-surface-container' }}"><span class="material-symbols-outlined text-[22px] {{ $allActive ? 'text-on-primary' : 'text-primary' }}">grid_view</span></div>
<div class="flex flex-col"><span class="font-title-md text-title-md leading-tight">সকল পণ্য</span><span class="font-label-sm text-label-sm {{ $allActive ? 'text-on-primary/80' : 'text-on-surface-variant' }}">{{ N::bn($stats['total']) }}টি আইটেম</span></div>
</a>
@foreach ($categories as $cat)
@php $on = in_array($cat->id, $selectedCats, true); @endphp
<a href="{{ route('products.index', array_filter(['filter' => ['category' => $cat->id], 'q' => request('q'), 'sort' => request('sort')])) }}" class="flex items-center gap-space-sm px-space-md py-space-xs rounded-xl shadow-sm shrink-0 transition-all hover:-translate-y-0.5 text-left {{ $on ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container' }}">
<div class="w-10 h-10 rounded-lg overflow-hidden shrink-0 bg-surface-container">@if ($img = $cat->imageUrl())<img class="w-full h-full object-cover" alt="" src="{{ $img }}" loading="lazy">@else<div class="w-full h-full flex items-center justify-center">🍯</div>@endif</div>
<div class="flex flex-col"><span class="font-title-md text-title-md leading-tight">{{ $cat->name['bn'] ?? '' }}</span><span class="font-label-sm text-label-sm {{ $on ? 'text-on-primary/80' : 'text-on-surface-variant' }}">{{ N::bn($cat->products_count) }}টি পণ্য</span></div>
</a>
@endforeach
</div>

<!-- Toolbar -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-space-sm lg:gap-space-md bg-surface-container-lowest p-space-sm lg:p-space-md rounded-xl shadow-sm">
<div class="flex flex-col sm:flex-row sm:items-center gap-space-sm">
<div class="flex items-baseline gap-space-xs flex-wrap">
<span class="font-title-lg text-title-lg text-on-surface font-semibold">সব পণ্য (Catalog)</span>
<span class="font-label-md text-label-md text-on-surface-variant">({{ N::bn($total) }}টি পণ্য প্রদর্শিত হচ্ছে)</span>
</div>
@if ($hasFilters)
<div class="flex items-center flex-wrap gap-space-xs">
@foreach ($chips as $chip)
<a href="{{ $chip['url'] }}" class="inline-flex items-center gap-1 bg-surface-container-high text-on-surface px-space-sm py-1 rounded-full font-label-sm text-label-sm hover:bg-surface-variant transition-colors">{{ $chip['label'] }}<span class="material-symbols-outlined text-[14px]">close</span></a>
@endforeach
<a href="{{ route('products.index', array_filter(['sort' => request('sort')])) }}" class="font-label-sm text-label-sm text-primary hover:text-secondary underline transition-colors">সব মুছুন</a>
</div>
@endif
</div>
<div class="flex items-center gap-space-sm justify-between lg:justify-end">
<button type="button" id="open-filters" class="lg:hidden flex items-center gap-1.5 bg-surface-container-low px-space-md py-space-xs rounded-full font-label-md text-label-md text-on-surface shadow-sm"><span class="material-symbols-outlined text-[18px] text-primary">tune</span>ফিল্টার@if (count($chips))<span class="w-5 h-5 rounded-full bg-primary text-on-primary text-[11px] flex items-center justify-center">{{ N::bn(count($chips)) }}</span>@endif</button>
<div class="hidden sm:flex items-center bg-surface-container p-0.5 rounded-lg">
<button type="button" id="view-grid" aria-label="গ্রিড ভিউ" class="w-8 h-8 rounded bg-surface-container-lowest text-primary shadow-sm flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">grid_view</span></button>
<button type="button" id="view-list" aria-label="লিস্ট ভিউ" class="w-8 h-8 rounded text-on-surface-variant hover:text-on-surface flex items-center justify-center transition-colors"><span class="material-symbols-outlined text-[18px]">view_agenda</span></button>
</div>
<form action="{{ route('products.index') }}" method="GET" class="relative flex-1 lg:flex-none">
@foreach (request()->except(['sort', 'page']) as $k => $v)
@if (is_array($v))@foreach (\Illuminate\Support\Arr::dot([$k => $v]) as $dk => $dv)<input type="hidden" name="{{ preg_replace('/\.(\w+)/', '[$1]', $dk) }}" value="{{ $dv }}">@endforeach @else<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
@endforeach
<select name="sort" onchange="this.form.submit()" class="w-full appearance-none bg-surface-container-low text-on-surface font-label-md text-label-md pl-space-md pr-8 py-space-xs rounded-full cursor-pointer focus:outline-none focus:ring-1 focus:ring-primary shadow-sm">
@foreach ($sorts as $key => $text)<option value="{{ $key }}" {{ $sort === $key ? 'selected' : '' }}>সাজান: {{ $text }}</option>@endforeach
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px] pointer-events-none">expand_more</span>
</form>
</div>
</div>

<!-- Sidebar + grid -->
<div class="w-full flex flex-col lg:flex-row gap-space-lg items-start">
<div id="shop-filters" class="w-full lg:w-72 shrink-0 lg:sticky lg:top-48" onclick="if(event.target===this)this.classList.remove('open')">
<form action="{{ route('products.index') }}" method="GET" class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-space-md">
@if (request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
@if (request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
<div class="flex items-center justify-between pb-space-xs border-b border-surface-container">
<div class="flex items-center gap-space-xs font-title-md text-title-md text-on-surface font-semibold"><span class="material-symbols-outlined text-primary text-[20px]">tune</span><span>ফিল্টারসমূহ</span></div>
<a href="{{ route('products.index') }}" class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors">রিসেট</a>
</div>
<div class="flex flex-col gap-space-xs">
<h4 class="font-label-md text-label-md text-on-surface font-bold uppercase tracking-wider">ক্যাটাগরি</h4>
<div class="flex flex-col gap-1.5 font-body-sm text-body-sm text-on-surface-variant">
@foreach ($categories as $cat)
<label class="flex items-center justify-between cursor-pointer hover:text-on-surface py-0.5">
<span class="flex items-center gap-2"><input type="checkbox" name="filter[category][]" value="{{ $cat->id }}" class="w-4 h-4 rounded accent-primary cursor-pointer" {{ in_array($cat->id, $selectedCats, true) ? 'checked' : '' }}><span>{{ $cat->name['bn'] ?? '' }}</span></span>
<span class="font-label-sm text-label-sm bg-surface-container px-2 py-0.5 rounded-full">{{ N::bn($cat->products_count) }}</span>
</label>
@endforeach
</div>
</div>
<div class="w-full h-px bg-surface-container"></div>
<div class="flex flex-col gap-space-xs">
<div class="flex items-center justify-between"><h4 class="font-label-md text-label-md text-on-surface font-bold uppercase tracking-wider">মূল্য পরিসীমা (৳)</h4><span class="font-label-sm text-label-sm text-primary font-semibold" id="price-label">৳{{ $priceMin }} - ৳{{ $priceMax }}</span></div>
<div class="relative w-full h-2 bg-surface-container rounded-full my-2"><div id="price-fill" class="absolute h-full bg-primary rounded-full"></div></div>
<div class="dual-range -mt-5 mb-1">
<input type="range" id="range-min" min="{{ $bounds['min'] }}" max="{{ $bounds['max'] }}" step="10" value="{{ $priceMin }}">
<input type="range" id="range-max" min="{{ $bounds['min'] }}" max="{{ $bounds['max'] }}" step="10" value="{{ $priceMax }}">
</div>
<div class="flex items-center gap-space-xs">
<input type="number" name="price_min" id="price-min" value="{{ request('price_min') }}" placeholder="{{ $bounds['min'] }}" class="w-1/2 bg-surface-container px-space-xs py-1 rounded text-center font-label-md text-label-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary">
<span class="text-on-surface-variant text-body-sm font-semibold">-</span>
<input type="number" name="price_max" id="price-max" value="{{ request('price_max') }}" placeholder="{{ $bounds['max'] }}" class="w-1/2 bg-surface-container px-space-xs py-1 rounded text-center font-label-md text-label-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary">
</div>
</div>
@foreach ($facets as $facet)
@if (count($facet['options']))
<div class="w-full h-px bg-surface-container"></div>
<div class="flex flex-col gap-space-xs">
<h4 class="font-label-md text-label-md text-on-surface font-bold uppercase tracking-wider">{{ $facet['label']['bn'] ?? $facet['code'] }}</h4>
<div class="flex flex-col gap-1.5 font-body-sm text-body-sm text-on-surface-variant">
@foreach ($facet['options'] as $opt)
<label class="flex items-center justify-between cursor-pointer hover:text-on-surface py-0.5">
<span class="flex items-center gap-2"><input type="radio" name="filter[{{ $facet['code'] }}]" value="{{ $opt['value'] }}" class="w-4 h-4 accent-primary cursor-pointer" {{ request('filter.'.$facet['code']) === (string) $opt['value'] ? 'checked' : '' }}><span>{{ $opt['value'] }}</span></span>
<span class="font-label-sm text-label-sm bg-surface-container px-2 py-0.5 rounded-full">{{ N::bn($opt['count']) }}</span>
</label>
@endforeach
</div>
</div>
@endif
@endforeach
<div class="w-full h-px bg-surface-container"></div>
<div class="flex flex-col gap-space-xs">
<h4 class="font-label-md text-label-md text-on-surface font-bold uppercase tracking-wider">গ্রাহক রেটিং</h4>
<div class="flex flex-col gap-1.5 font-body-sm text-body-sm text-on-surface-variant">
@foreach ([5 => '৫ তারকা', 4 => '৪ তারকা ও তদূর্ধ্ব'] as $r => $t)
<label class="flex items-center justify-between cursor-pointer hover:text-on-surface py-0.5">
<span class="flex items-center gap-1.5"><input type="radio" name="rating_min" value="{{ $r }}" class="w-4 h-4 accent-primary cursor-pointer" {{ request('rating_min') == $r ? 'checked' : '' }}><span class="text-primary font-bold">{{ str_repeat('★', $r).str_repeat('☆', 5 - $r) }}</span><span>{{ $t }}</span></span>
<span class="font-label-sm text-label-sm bg-surface-container px-2 py-0.5 rounded-full">{{ N::bn($ratingCounts[$r]) }}</span>
</label>
@endforeach
@if (request('rating_min'))<a href="{{ route('products.index', \Illuminate\Support\Arr::except(request()->query(), ['rating_min', 'page'])) }}" class="font-label-sm text-label-sm text-primary underline">রেটিং ফিল্টার মুছুন</a>@endif
</div>
</div>
<div class="w-full h-px bg-surface-container"></div>
<div class="flex flex-col gap-space-xs">
<h4 class="font-label-md text-label-md text-on-surface font-bold uppercase tracking-wider">প্রাপ্যতা</h4>
<label class="flex items-center justify-between cursor-pointer hover:text-on-surface py-0.5 font-body-sm text-body-sm text-on-surface-variant">
<span class="flex items-center gap-2"><input type="checkbox" name="in_stock" value="1" class="w-4 h-4 rounded accent-primary cursor-pointer" {{ request()->boolean('in_stock') ? 'checked' : '' }}><span>ইন স্টক (প্রস্তুত পণ্য)</span></span>
<span class="font-label-sm text-label-sm bg-surface-container px-2 py-0.5 rounded-full">{{ N::bn($stats['in_stock']) }}</span>
</label>
</div>
<button type="submit" class="w-full py-2.5 rounded-full bg-primary hover:bg-secondary text-on-primary font-label-md text-label-md shadow-sm transition-transform hover:-translate-y-0.5 mt-space-xs">ফলাফল দেখুন ({{ N::bn($total) }})</button>
</form>
</div>

<!-- Grid -->
<div class="flex-1 w-full min-w-0 flex flex-col gap-space-lg">
@if ($products->isEmpty())
<div class="bg-surface-container-lowest rounded-xl shadow-sm p-space-xl text-center flex flex-col items-center gap-space-sm">
<span class="material-symbols-outlined text-[48px] text-on-surface-variant">search_off</span>
<h3 class="font-title-lg text-title-lg text-on-surface font-semibold">কোনো পণ্য পাওয়া যায়নি</h3>
<p class="font-body-md text-body-md text-on-surface-variant">ফিল্টার বা সার্চ পরিবর্তন করে আবার চেষ্টা করুন।</p>
<a href="{{ route('products.index') }}" class="px-space-lg py-2 rounded-full bg-primary text-on-primary font-label-md text-label-md">সব ফিল্টার মুছুন</a>
</div>
@else
<div id="shop-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-space-sm sm:gap-space-md">
@foreach ($products as $product)
@include('storefront.partials.shop-card', ['product' => $product])
@if ($loop->iteration === 6 && $products->count() > 6)
@include('storefront.products.partials.shop-banner')
@endif
@endforeach
@if ($products->count() <= 6)
@include('storefront.products.partials.shop-banner')
@endif
@unless ($products->hasMorePages())
<div class="flex flex-col items-center justify-center text-center bg-surface-container/60 rounded-xl p-space-md border border-dashed border-outline-variant">
<div class="w-12 h-12 rounded-full bg-primary-container/30 flex items-center justify-center text-primary mb-space-sm"><span class="material-symbols-outlined text-[26px]">inventory_2</span></div>
<h4 class="font-title-md text-title-md text-on-surface font-semibold">কাস্টম উপহার বা হোলসেল চাই?</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 mb-space-md">বিয়ে, করপোরেট ইভেন্ট বা বড় অর্ডারের জন্য বিশেষ প্যাকেজিং সুবিধা উপলব্ধ।</p>
<a href="tel:+8801712345678" class="px-space-md py-1.5 rounded-full bg-surface-container-lowest text-on-surface font-label-sm text-label-sm font-bold shadow-sm hover:bg-surface-container-high transition-colors">সরাসরি যোগাযোগ করুন</a>
</div>
@endunless
</div>

<!-- Pagination -->
@if ($products->lastPage() > 1)
@php
    $cur = $products->currentPage(); $last = $products->lastPage();
    $pages = collect([1, $last, $cur - 1, $cur, $cur + 1])->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
@endphp
<div class="w-full flex flex-col items-center gap-space-md pt-space-md pb-space-sm border-t border-surface-container">
<div class="flex flex-col items-center gap-1.5 w-full max-w-xs">
<span class="font-label-sm text-label-sm text-on-surface-variant">{{ N::bn($products->firstItem()) }} - {{ N::bn($products->lastItem()) }} (মোট {{ N::bn($total) }}টি পণ্যের মধ্যে প্রদর্শিত)</span>
<div class="w-full h-1.5 bg-surface-container rounded-full overflow-hidden"><div class="h-full bg-primary rounded-full" style="width: {{ round($products->lastItem() / max($total, 1) * 100) }}%"></div></div>
</div>
<div class="flex items-center gap-1">
@if ($products->onFirstPage())<span class="w-9 h-9 rounded-full bg-surface-container-lowest text-on-surface flex items-center justify-center shadow-sm opacity-40"><span class="material-symbols-outlined text-[18px]">chevron_left</span></span>
@else<a href="{{ $products->previousPageUrl() }}" aria-label="আগের পাতা" class="w-9 h-9 rounded-full bg-surface-container-lowest text-on-surface hover:bg-surface-container flex items-center justify-center shadow-sm"><span class="material-symbols-outlined text-[18px]">chevron_left</span></a>@endif
@php $prev = 0; @endphp
@foreach ($pages as $p)
@if ($p - $prev > 1)<span class="px-2 text-on-surface-variant font-bold">...</span>@endif
<a href="{{ $products->url($p) }}" class="w-9 h-9 rounded-full flex items-center justify-center font-label-md text-label-md shadow-sm transition-colors {{ $p === $cur ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container' }}">{{ N::bn($p) }}</a>
@php $prev = $p; @endphp
@endforeach
@if ($products->hasMorePages())<a href="{{ $products->nextPageUrl() }}" aria-label="পরের পাতা" class="w-9 h-9 rounded-full bg-surface-container-lowest text-on-surface hover:bg-surface-container flex items-center justify-center shadow-sm"><span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
@else<span class="w-9 h-9 rounded-full bg-surface-container-lowest text-on-surface flex items-center justify-center shadow-sm opacity-40"><span class="material-symbols-outlined text-[18px]">chevron_right</span></span>@endif
</div>
@if ($products->hasMorePages())
<a href="{{ $products->nextPageUrl() }}" class="px-space-xl py-2.5 rounded-full bg-surface-container-lowest hover:bg-surface-container text-on-surface font-label-md text-label-md shadow-sm transition-all hover:-translate-y-0.5 flex items-center gap-2"><span class="material-symbols-outlined text-[18px] text-primary">expand_more</span><span>আরও পণ্য দেখুন ({{ N::bn($total - $products->lastItem()) }}টি অবশিষ্ট)</span></a>
@endif
</div>
@endif
@endif
</div>
</div>

<!-- Trust bar -->
<div class="w-full mt-space-md bg-surface-container-low rounded-2xl p-space-md lg:p-space-lg shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
@foreach ([
    ['spa', 'bg-primary-container/25 text-primary', '১০০% খাঁটি ও কাঁচা মধু', 'সরাসরি মৌচাক থেকে কোনো হিট বা চিনি ছাড়া'],
    ['science', 'bg-tertiary-container/25 text-tertiary', 'বিএসটিআই ও ল্যাব টেস্ট', 'আন্তর্জাতিক মানসম্পন্ন বিশুদ্ধতার সনদপ্রাপ্ত'],
    ['local_shipping', 'bg-secondary-container/35 text-secondary', '৬৪ জেলায় দ্রুত ডেলিভারি', 'ক্যাশ অন ডেলিভারি ও নিরাপদ হ্যান্ডলিং'],
    ['verified_user', 'bg-surface-container-high text-on-surface', 'সিল্ড প্যাক ও সহজ রিটার্ন', 'পণ্য অপছন্দ হলে শতভাগ মূল্য ফেরত গ্যারান্টি'],
] as [$icon, $tone, $title, $sub])
<div class="flex items-center gap-space-sm">
<div class="w-12 h-12 rounded-xl {{ $tone }} flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[24px]">{{ $icon }}</span></div>
<div class="flex flex-col"><span class="font-title-md text-title-md text-on-surface font-semibold">{{ $title }}</span><span class="font-body-sm text-body-sm text-on-surface-variant">{{ $sub }}</span></div>
</div>
@endforeach
</div>
</div>

<script>
(function () {
  // Weight pills: switch the variant the card submits + its price.
  document.querySelectorAll('.shop-card').forEach(function (card) {
    card.querySelectorAll('.shop-pill').forEach(function (pill) {
      pill.addEventListener('click', function () {
        if (pill.disabled) return;
        card.querySelector('.shop-variant-input').value = pill.dataset.id;
        card.querySelectorAll('.shop-pill').forEach(function (p) { p.classList.remove('bg-primary', 'text-on-primary'); p.classList.add('bg-surface-container', 'text-on-surface'); });
        pill.classList.add('bg-primary', 'text-on-primary'); pill.classList.remove('bg-surface-container', 'text-on-surface');
        card.querySelector('.shop-price').textContent = pill.dataset.price;
        var d = parseInt(pill.dataset.discount || '0', 10), o = card.querySelector('.shop-original'), b = card.querySelector('.shop-discount');
        o.textContent = pill.dataset.original; b.textContent = String(d).replace(/\d/g, function (x) { return '০১২৩৪৫৬৭৮৯'[x]; }) + '% ছাড়';
        o.classList.toggle('hidden', !d); b.classList.toggle('hidden', !d);
      });
    });
  });
  // Grid / list view
  var grid = document.getElementById('shop-grid'), g = document.getElementById('view-grid'), l = document.getElementById('view-list');
  function setView(list) {
    if (!grid) return;
    grid.classList.toggle('list-mode', list);
    [[g, !list], [l, list]].forEach(function (x) {
      x[0].classList.toggle('bg-surface-container-lowest', x[1]); x[0].classList.toggle('text-primary', x[1]); x[0].classList.toggle('shadow-sm', x[1]);
      x[0].classList.toggle('text-on-surface-variant', !x[1]);
    });
    try { localStorage.setItem('shopView', list ? 'list' : 'grid'); } catch (e) {}
  }
  if (g && l) { g.onclick = function () { setView(false); }; l.onclick = function () { setView(true); }; try { if (localStorage.getItem('shopView') === 'list') setView(true); } catch (e) {} }
  // Mobile filter drawer
  var drawer = document.getElementById('shop-filters'), openBtn = document.getElementById('open-filters');
  if (openBtn) openBtn.onclick = function () { drawer.classList.add('open'); };
  // Dual price range
  var rmin = document.getElementById('range-min'), rmax = document.getElementById('range-max'), nmin = document.getElementById('price-min'), nmax = document.getElementById('price-max'), fill = document.getElementById('price-fill'), label = document.getElementById('price-label');
  function paint(fromRange) {
    var lo = +rmin.value, hi = +rmax.value, a = +rmin.min, b = +rmin.max;
    if (lo > hi) { if (fromRange === rmin) { lo = hi; rmin.value = lo; } else { hi = lo; rmax.value = hi; } }
    fill.style.left = ((lo - a) / (b - a) * 100) + '%'; fill.style.right = (100 - (hi - a) / (b - a) * 100) + '%';
    label.textContent = '৳' + lo + ' - ৳' + hi;
    if (fromRange) { nmin.value = lo > a ? lo : ''; nmax.value = hi < b ? hi : ''; }
  }
  if (rmin) {
    rmin.oninput = function () { paint(rmin); }; rmax.oninput = function () { paint(rmax); };
    nmin.oninput = function () { rmin.value = nmin.value || rmin.min; paint(); }; nmax.oninput = function () { rmax.value = nmax.value || rmax.max; paint(); };
    paint();
  }
})();
</script>
@endsection
