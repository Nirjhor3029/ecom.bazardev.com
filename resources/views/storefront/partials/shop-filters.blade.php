{{-- Filter sidebar (desktop) / bottom-sheet drawer (mobile). Shared by the shop and search pages.
     Needs: $formAction, $categories, $selectedCats, $bounds, $priceMin, $priceMax, $facets, $ratingCounts, $stats, $total. --}}
@php use App\Modules\Core\Support\Numerals as N; @endphp
<div id="shop-filters" class="w-full lg:w-72 shrink-0 lg:sticky lg:top-48" onclick="if(event.target===this)this.classList.remove('open')">
<form action="{{ $formAction }}" method="GET" class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col gap-space-md">
@if (request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
@if (request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
<div class="flex items-center justify-between pb-space-xs border-b border-surface-container">
<div class="flex items-center gap-space-xs font-title-md text-title-md text-on-surface font-semibold"><span class="material-symbols-outlined text-primary text-[20px]">tune</span><span>ফিল্টারসমূহ</span></div>
<a href="{{ $formAction }}{{ request('q') ? '?q='.urlencode(request('q')) : '' }}" class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors">রিসেট</a>
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
@if (request('rating_min'))<a href="{{ $formAction.'?'.http_build_query(\Illuminate\Support\Arr::except(request()->query(), ['rating_min', 'page'])) }}" class="font-label-sm text-label-sm text-primary underline">রেটিং ফিল্টার মুছুন</a>@endif
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
