{{-- Desktop section 5: rating breakdown + verified review cards. Data: $rating, $reviews. --}}
@php
    use App\Modules\Core\Support\Numerals as N;
    $avatarTones = ['bg-secondary-fixed text-on-secondary-fixed', 'bg-primary-fixed text-on-primary-fixed', 'bg-tertiary-fixed text-on-tertiary-fixed'];
    $stars = function (float $value, int $size) {
        $out = '';
        for ($i = 1; $i <= 5; $i++) {
            $icon = $value >= $i ? 'star' : ($value >= $i - 0.5 ? 'star_half' : 'star');
            $fill = $value >= $i - 0.5 ? 1 : 0;
            $out .= '<span class="material-symbols-outlined text-['.$size.'px]" style="font-variation-settings: \'FILL\' '.$fill.';">'.$icon.'</span>';
        }

        return $out;
    };
@endphp
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-lg" id="customer-reviews">
<div class="flex flex-col gap-space-lg">
<div class="bg-surface-container-lowest p-space-md lg:p-space-lg rounded-2xl shadow-sm">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
<div class="lg:col-span-4 flex flex-col items-center lg:items-start text-center lg:text-left gap-1">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">গ্রাহক পর্যালোচনা</span>
@if ($rating['count'] > 0)
<div class="flex items-baseline gap-2 mt-1"><span class="font-display-hero text-display-hero text-on-surface font-bold">{{ N::bn($rating['avg']) }}</span><span class="font-title-lg text-title-lg text-on-surface-variant">/ ৫.০</span></div>
<div class="flex items-center text-primary-container my-1">{!! $stars($rating['avg'], 22) !!}</div>
<span class="font-body-sm text-body-sm text-on-surface-variant">{{ N::bn($rating['count']) }} জন যাচাইকৃত ক্রেতার মতামতের ভিত্তিতে</span>
@else
<span class="font-title-lg text-title-lg text-on-surface font-semibold mt-1">এখনো কোনো রিভিউ নেই</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">এই পণ্যের প্রথম ক্রেতা হয়ে অভিজ্ঞতা শেয়ার করুন।</span>
@endif
</div>
<div class="lg:col-span-5 flex flex-col gap-1.5">
@foreach ($rating['dist'] as $star => $percent)
<div class="flex items-center gap-3 text-body-sm font-body-sm">
<span class="w-12 text-on-surface-variant">{{ N::bn($star) }} স্টার</span>
<div class="flex-1 bg-surface-container-high h-2.5 rounded-full overflow-hidden"><div class="bg-primary h-full rounded-full" style="width: {{ $percent }}%;"></div></div>
<span class="w-10 text-right text-on-surface font-semibold">{{ N::bn($percent) }}%</span>
</div>
@endforeach
</div>
<div class="lg:col-span-3 flex flex-col items-center justify-center p-space-sm rounded-xl bg-surface-container-low text-center gap-2">
<span class="font-title-md text-title-md text-on-surface font-semibold">আপনার অভিজ্ঞতা কেমন?</span>
<p class="font-body-sm text-[12px] text-on-surface-variant">আপনার সৎ মতামত অন্য ক্রেতাদের সঠিক পণ্য বেছে নিতে সাহায্য করবে।</p>
<button type="button" class="w-full py-2.5 px-space-md rounded-full bg-on-surface text-surface font-label-md text-label-md hover:bg-primary transition-all mt-1 shadow-sm" onclick="document.getElementById('review-info').classList.remove('hidden')">রিভিউ লিখুন</button>
</div>
</div>
</div>
@if ($reviews->isNotEmpty())
<div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
@foreach ($reviews as $review)
@php($name = $review->customer?->name ?? 'ক্রেতা')
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex flex-col justify-between gap-space-sm hover:shadow-md transition-shadow">
<div class="flex flex-col gap-2">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-9 h-9 rounded-full {{ $avatarTones[$loop->index % 3] }} flex items-center justify-center font-bold font-label-md">{{ mb_substr($name, 0, 2) }}</div>
<div class="flex flex-col"><span class="font-title-md text-title-md text-on-surface font-semibold">{{ $name }}</span><span class="font-label-sm text-[11px] text-on-surface-variant">{{ $review->location }}</span></div>
</div>
@if ($review->is_verified)<span class="inline-flex items-center gap-1 font-label-sm text-[11px] text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full"><span class="material-symbols-outlined text-[13px]">verified</span> ভেরিফাইড</span>@endif
</div>
<div class="flex items-center text-primary-container">{!! $stars((float) $review->rating, 16) !!}<span class="font-label-sm text-label-sm text-on-surface-variant ml-2">{{ N::ago($review->created_at) }}</span></div>
@if ($review->title)<span class="font-title-md text-title-md text-on-surface font-semibold">{{ $review->title }}</span>@endif
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">"{{ $review->body }}"</p>
</div>
@if ($review->photoUrls())
<div class="flex gap-2 mt-2">@foreach ($review->photoUrls() as $url)<div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container"><img class="w-full h-full object-cover" alt="ক্রেতার তোলা ছবি" src="{{ $url }}"></div>@endforeach</div>
@endif
</div>
@endforeach
</div>
@endif
</div>
</section>
<div id="review-info" class="hidden fixed inset-0 z-[100] bg-on-surface/70 backdrop-blur-sm flex items-center justify-center p-6" onclick="if(event.target===this)this.classList.add('hidden')">
<div class="bg-surface rounded-2xl shadow-2xl max-w-md w-full p-space-lg flex flex-col gap-space-sm text-center">
<span class="material-symbols-outlined text-primary text-[36px]">rate_review</span>
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">রিভিউ দিতে পারবেন ডেলিভারির পর</h3>
<p class="font-body-md text-body-md text-on-surface-variant">শুধুমাত্র যারা এই পণ্যটি কিনেছেন, তাদের রিভিউ গ্রহণ করা হয়। পণ্য হাতে পাওয়ার পর আমরা আপনাকে রিভিউ দেওয়ার লিংক পাঠাবো।</p>
<button type="button" class="mt-2 py-2.5 rounded-full bg-primary text-on-primary font-label-md text-label-md" onclick="document.getElementById('review-info').classList.add('hidden')">ঠিক আছে</button>
</div>
</div>
