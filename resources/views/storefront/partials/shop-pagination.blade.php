{{-- Numbered pagination with progress bar + load-more link. Needs $products, $total. --}}
@php use App\Modules\Core\Support\Numerals as N; @endphp
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
