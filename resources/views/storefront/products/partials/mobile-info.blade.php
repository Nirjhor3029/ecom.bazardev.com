{{-- Mobile accordions + origin story + reviews, all driven by $product->content / $rating / $reviews. --}}
@php
    use App\Modules\Core\Support\Numerals as N;
    $c = $product->content ?? [];
    $ov = $c['overview'] ?? null;
    $specs = $c['specs'] ?? [];
    $lab = $c['lab'] ?? null;
    $desc = $ov['paragraphs'][0] ?? ($product->description['bn'] ?? $product->short_desc['bn'] ?? null);
    $story = $c['story'] ?? null;
    $storyImg = ! empty($story['image']) ? \Illuminate\Support\Facades\Storage::disk('public')->url($story['image']) : null;
    $mStars = function (float $v, int $size) {
        $o = '';
        for ($i = 1; $i <= 5; $i++) {
            $o .= '<span class="material-symbols-outlined text-['.$size.'px]" style="font-variation-settings: \'FILL\' '.($v >= $i - 0.5 ? 1 : 0).';">'.($v >= $i ? 'star' : ($v >= $i - 0.5 ? 'star_half' : 'star')).'</span>';
        }

        return $o;
    };
    $acc = fn ($open) => $open ? '' : 'hidden';
@endphp
<!-- 7. Accordions -->
<section class="px-gutter-mobile pt-space-lg flex flex-col gap-2.5">
@if ($desc || $ov)
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5"><span class="material-symbols-outlined text-primary text-[20px]">menu_book</span><span class="font-title-md text-title-md text-on-surface font-semibold">পণ্যের বিবরণ ও ব্যবহার</span></div>
<span class="material-symbols-outlined text-outline transition-transform duration-200 rotate-180">keyboard_arrow_down</span>
</button>
<div class="accordion-content px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<p class="leading-relaxed">{{ $desc }}</p>
@if (! empty($ov['usage']))
<div class="mt-3 p-3 bg-surface-container-low rounded-lg">
<h4 class="font-label-md text-label-md text-on-surface font-bold mb-1">{{ $ov['usage_title'] ?? 'সেবনবিধি' }}:</h4>
<ul class="font-body-sm text-body-sm space-y-1 text-on-surface-variant list-disc pl-4">@foreach ($ov['usage'] as $u)<li><strong>{{ $u['title'] }}</strong> {{ $u['text'] }}</li>@endforeach</ul>
</div>
@endif
</div>
</div>
@endif
@if ($specs)
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5"><span class="material-symbols-outlined text-primary text-[20px]">fact_check</span><span class="font-title-md text-title-md text-on-surface font-semibold">পণ্যের বৈশিষ্ট্য ও স্পেসিফিকেশন</span></div>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<div class="space-y-2">
@foreach ($specs as $row)
<div class="flex justify-between gap-3 py-1.5 bg-surface-container-low px-3 rounded-lg"><span class="text-on-surface-variant font-body-sm text-body-sm">{{ $row['label'] }}</span><span class="font-label-md text-label-md text-on-surface font-semibold text-right">{{ $row['value'] }}</span></div>
@endforeach
</div>
</div>
</div>
@endif
@if ($lab)
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5"><span class="material-symbols-outlined text-primary text-[20px]">science</span><span class="font-title-md text-title-md text-on-surface font-semibold">বিশুদ্ধতা ও কোয়ালিটি গ্যারান্টি</span></div>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<div class="p-3 bg-secondary-container/40 rounded-xl space-y-2">
@if (! empty($lab['intro']))<div class="flex items-start gap-2"><span class="material-symbols-outlined text-primary text-[20px] mt-0.5">verified_user</span><p class="font-body-sm text-body-sm text-on-surface">{{ $lab['intro'] }}</p></div>@endif
@foreach ($lab['tests'] ?? [] as $t)
<div class="flex items-start justify-between gap-3 font-body-sm text-body-sm"><span>{{ $t['label'] }}</span><span class="font-bold text-primary text-right">{{ $t['value'] }}</span></div>
@endforeach
</div>
</div>
</div>
@endif
<div class="rounded-xl bg-surface-container-lowest shadow-sm overflow-hidden transition-all">
<button class="w-full min-h-[52px] px-space-md py-3.5 flex items-center justify-between text-left active:bg-surface-container-low transition-colors" onclick="toggleAccordion(this)" type="button">
<div class="flex items-center gap-2.5"><span class="material-symbols-outlined text-primary text-[20px]">local_shipping</span><span class="font-title-md text-title-md text-on-surface font-semibold">ডেলিভারি ও রিটার্ন পলিসি</span></div>
<span class="material-symbols-outlined text-outline transition-transform duration-200">keyboard_arrow_down</span>
</button>
<div class="accordion-content hidden px-space-md pb-space-md text-on-surface-variant font-body-md text-body-md">
<ul class="font-body-sm text-body-sm space-y-2">
@foreach ([['schedule', 'ঢাকা সিটির ভেতরে: ২৪-৪৮ ঘণ্টার মধ্যে হোম ডেলিভারি (চার্জ ৳৬০)।'], ['travel_explore', 'ঢাকার বাইরে সারাদেশে: ২-৪ কার্যদিবস (চার্জ ৳১২০)।'], ['payments', 'ক্যাশ অন ডেলিভারি (COD) সুবিধা উপলব্ধ। পণ্য দেখে মূল্য পরিশোধ করুন।'], ['replay', 'প্যাকেজে ভাঙা বা ভিন্ন পণ্য পেলে ৩ দিনের মধ্যে রিটার্ন।']] as [$i, $t])
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[18px]">{{ $i }}</span><span>{{ $t }}</span></li>
@endforeach
</ul>
</div>
</div>
</section>
@if ($story)
<!-- 8. Origin story -->
<section class="px-gutter-mobile pt-space-lg">
<div class="relative rounded-2xl overflow-hidden bg-surface-container-low shadow-sm">
@if ($storyImg)
<div class="w-full h-36 relative">
<img class="w-full h-full object-cover" alt="{{ $story['title'] ?? '' }}" src="{{ $storyImg }}"/>
<div class="absolute inset-0 bg-gradient-to-t from-surface-container-low via-transparent to-transparent"></div>
<span class="absolute top-2.5 left-2.5 bg-inverse-surface/80 backdrop-blur-sm text-inverse-on-surface px-2.5 py-0.5 rounded-full font-label-sm text-label-sm">{{ $story['eyebrow'] ?? '' }}</span>
</div>
@endif
<div class="p-space-md">
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold mb-1">{{ $story['title'] ?? '' }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $story['paragraphs'][0] ?? '' }}</p>
@if (! empty($story['trade']))<div class="mt-3 flex items-center gap-2 pt-2"><div class="w-7 h-7 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">M</div><span class="font-label-sm text-label-sm text-on-surface font-medium">{{ $story['trade'] }}</span></div>@endif
</div>
</div>
</section>
@endif
<!-- 9. Reviews -->
<section class="px-gutter-mobile pt-space-lg" id="customer-reviews">
<div class="p-space-md rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col gap-3">
@if ($rating['count'] > 0)
<div class="flex items-center gap-4">
<div class="flex flex-col items-center"><span class="font-display-hero-mobile text-display-hero-mobile font-bold text-on-surface">{{ N::bn($rating['avg']) }}</span><div class="flex text-primary-container">{!! $mStars($rating['avg'], 14) !!}</div><span class="font-label-sm text-[11px] text-on-surface-variant mt-0.5">{{ N::bn($rating['count']) }}টি রিভিউ</span></div>
<div class="flex-1 flex flex-col gap-1">
@foreach ($rating['dist'] as $star => $pc)
<div class="flex items-center gap-2 text-[11px] font-body-sm"><span class="w-4 text-on-surface-variant">{{ N::bn($star) }}★</span><div class="flex-1 bg-surface-container-high h-1.5 rounded-full overflow-hidden"><div class="bg-primary h-full rounded-full" style="width: {{ $pc }}%;"></div></div><span class="w-8 text-right text-on-surface-variant">{{ N::bn($pc) }}%</span></div>
@endforeach
</div>
</div>
@else
<div class="flex flex-col items-center text-center gap-2"><span class="material-symbols-outlined text-on-surface-variant text-[32px]">rate_review</span><h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">এখনো কোনো রিভিউ নেই</h3><p class="font-body-sm text-body-sm text-on-surface-variant">এই পণ্যের প্রথম ক্রেতা হয়ে আপনার অভিজ্ঞতা শেয়ার করুন।</p></div>
@endif
</div>
@if ($reviews->isNotEmpty())
<div class="flex gap-3 overflow-x-auto snap-x snap-mandatory pt-3 pb-1 -mx-gutter-mobile px-gutter-mobile">
@foreach ($reviews as $review)
@php($rn = $review->customer?->name ?? 'ক্রেতা')
<div class="snap-center shrink-0 w-[85%] bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col gap-2">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2"><div class="w-8 h-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center font-bold font-label-md">{{ mb_substr($rn, 0, 2) }}</div><div class="flex flex-col"><span class="font-title-md text-[14px] text-on-surface font-semibold">{{ $rn }}</span><span class="font-label-sm text-[11px] text-on-surface-variant">{{ $review->location }}</span></div></div>
@if ($review->is_verified)<span class="inline-flex items-center gap-1 text-[11px] text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full"><span class="material-symbols-outlined text-[13px]">verified</span> ভেরিফাইড</span>@endif
</div>
<div class="flex items-center text-primary-container">{!! $mStars((float) $review->rating, 14) !!}<span class="font-label-sm text-[11px] text-on-surface-variant ml-2">{{ N::ago($review->created_at) }}</span></div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">"{{ $review->body }}"</p>
@if ($review->photoUrls())<div class="flex gap-2">@foreach ($review->photoUrls() as $url)<div class="w-14 h-14 rounded-lg overflow-hidden bg-surface-container"><img class="w-full h-full object-cover" alt="ক্রেতার তোলা ছবি" src="{{ $url }}"></div>@endforeach</div>@endif
</div>
@endforeach
</div>
@endif
</section>
