{{-- Desktop section 8: FAQ accordion (data: $product->content['faq'] = [[q, a], …]). --}}
@php($faq = ($product->content ?? [])['faq'] ?? [])
@if ($faq)
<section class="max-w-3xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-xl">
<div class="text-center mb-space-lg">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">জিজ্ঞাসা</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-1">{{ $product->name['bn'] ?? '' }} সম্পর্কে সচরাচর প্রশ্ন ও উত্তর</h2>
</div>
<div class="flex flex-col gap-space-sm">
@foreach ($faq as $i => $item)
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
<button type="button" class="w-full flex items-center justify-between gap-4 p-space-md text-left font-title-md text-title-md text-on-surface font-semibold hover:bg-surface-container-low transition-colors" onclick="toggleFaq(this)">
<span>{{ \App\Modules\Core\Support\Numerals::bn($i + 1) }}. {{ $item['q'] ?? $item[0] }}</span>
<span class="material-symbols-outlined text-primary shrink-0 transition-transform">expand_more</span>
</button>
<div class="faq-content hidden px-space-md pb-space-md text-body-md font-body-md text-on-surface-variant">{{ $item['a'] ?? $item[1] }}</div>
</div>
@endforeach
</div>
</section>
@endif
