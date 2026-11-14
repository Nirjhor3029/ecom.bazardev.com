{{-- Desktop section 3: tabbed info centre. Tabs with no data are omitted; shipping is always shown. --}}
@php
    $c = $product->content ?? [];
    $ov = $c['overview'] ?? null;
    $specs = $c['specs'] ?? [];
    $lab = $c['lab'] ?? null;
    $tabs = array_values(array_filter([
        $ov ? ['tab-taste-overview', 'বিবরণ ও স্বাদ (Overview)'] : null,
        $specs ? ['tab-specs-table', 'পণ্য পরিচিতি ও স্পেসিফিকেশন'] : null,
        $lab ? ['tab-purity-lab', 'বিশুদ্ধতা ও ল্যাব তথ্য (Lab Report)'] : null,
        ['tab-shipping-returns', 'ডেলিভারি ও রিটার্ন পলিসি'],
    ]));
@endphp
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-lg">
<div class="bg-surface-container-lowest rounded-xl p-space-md lg:p-space-lg shadow-sm">
<div class="flex items-center gap-space-sm overflow-x-auto pb-space-sm" id="pdp-tab-headers">
@foreach ($tabs as $i => [$id, $label])
<button type="button" class="tab-btn px-space-md py-2.5 rounded-full font-label-md text-label-md whitespace-nowrap transition-all {{ $i === 0 ? 'bg-primary-container text-on-primary-container font-bold shadow-sm active-tab' : 'bg-surface-container text-on-surface-variant hover:text-on-surface' }}" onclick="switchTab(this, '{{ $id }}')">{{ $label }}</button>
@endforeach
</div>

@if ($ov)
<div class="tab-pane pt-space-lg flex flex-col gap-space-lg {{ $tabs[0][0] === 'tab-taste-overview' ? '' : 'hidden' }}" id="tab-taste-overview">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
<div class="lg:col-span-7 flex flex-col gap-space-sm">
<h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">{{ $ov['title'] ?? '' }}</h3>
@foreach ($ov['paragraphs'] ?? [] as $p)<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">{{ $p }}</p>@endforeach
@if (! empty($ov['sensory']))
<div class="bg-surface-container-low p-space-md rounded-xl mt-space-xs flex flex-col gap-3">
<span class="font-label-md text-label-md text-on-surface font-bold">টেস্ট ও সেন্সরি প্রোফাইল:</span>
@foreach ($ov['sensory'] as $s)
<div>
<div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant mb-1"><span>{{ $s['label'] }}</span><span class="font-bold text-on-surface">{{ $s['note'] }}</span></div>
<div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden"><div class="bg-primary h-full rounded-full" style="width: {{ (int) $s['pct'] }}%;"></div></div>
</div>
@endforeach
</div>
@endif
</div>
@if (! empty($ov['usage']))
<div class="lg:col-span-5 bg-surface-container-low p-space-md rounded-xl flex flex-col gap-space-sm">
<h4 class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[20px]">local_cafe</span>{{ $ov['usage_title'] ?? 'সেবনবিধি ও পরামর্শ' }}</h4>
<ul class="flex flex-col gap-2.5 font-body-sm text-body-sm text-on-surface-variant">
@foreach ($ov['usage'] as $u)
<li class="flex items-start gap-2"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span><span><strong>{{ $u['title'] }}</strong> {{ $u['text'] }}</span></li>
@endforeach
@if (! empty($ov['warning']))
<li class="flex items-start gap-2 text-error"><span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">warning</span><span>{{ $ov['warning'] }}</span></li>
@endif
</ul>
</div>
@endif
</div>
</div>
@endif

@if ($specs)
<div class="tab-pane {{ $tabs[0][0] === 'tab-specs-table' ? '' : 'hidden' }} pt-space-lg" id="tab-specs-table">
<div class="overflow-x-auto"><table class="w-full text-left font-body-md text-body-md"><tbody>
@foreach ($specs as $i => $row)
<tr class="{{ $i % 2 === 0 ? 'bg-surface-container-low' : 'bg-surface-container-lowest' }}">
<td class="py-3.5 px-4 font-label-md text-label-md text-on-surface font-semibold w-1/3">{{ $row['label'] }}</td>
<td class="py-3.5 px-4 text-on-surface-variant">{{ $row['value'] }}</td>
</tr>
@endforeach
</tbody></table></div>
</div>
@endif

@if ($lab)
<div class="tab-pane {{ $tabs[0][0] === 'tab-purity-lab' ? '' : 'hidden' }} pt-space-lg flex flex-col gap-space-lg" id="tab-purity-lab">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-center">
<div class="lg:col-span-7 flex flex-col gap-space-sm">
<div class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[28px]">verified</span><h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">{{ $lab['title'] ?? 'ল্যাব পরীক্ষিত বিশুদ্ধতার নিশ্চয়তা' }}</h3></div>
@if (! empty($lab['intro']))<p class="font-body-md text-body-md text-on-surface-variant">{{ $lab['intro'] }}</p>@endif
<div class="grid grid-cols-2 gap-space-sm my-space-xs">
@foreach ($lab['tests'] ?? [] as $t)
<div class="p-3 bg-surface-container-low rounded-lg">
<span class="font-label-sm text-label-sm text-on-surface-variant">{{ $t['label'] }}</span>
<div class="font-headline-sm text-headline-sm text-primary font-bold">{{ $t['value'] }}</div>
<span class="text-[11px] text-on-surface-variant">{{ $t['note'] ?? '' }}</span>
</div>
@endforeach
</div>
</div>
<div class="lg:col-span-5 bg-surface-container-low p-space-md rounded-xl flex flex-col items-center text-center gap-space-sm">
<div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center text-primary"><span class="material-symbols-outlined text-[32px]">description</span></div>
<h4 class="font-title-lg text-title-lg text-on-surface font-semibold">ল্যাব টেস্ট রিপোর্ট</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">ব্যাচ {{ $c['batch'] ?? '' }} এর টেস্ট রিপোর্টের কপি পেতে আমাদের হটলাইনে যোগাযোগ করুন অথবা পণ্যের লেবেলের QR কোড স্ক্যান করুন।</p>
@if (! empty($lab['file_note']))<span class="text-[11px] text-on-surface-variant">{{ $lab['file_note'] }}</span>@endif
</div>
</div>
</div>
@endif

<div class="tab-pane {{ $tabs[0][0] === 'tab-shipping-returns' ? '' : 'hidden' }} pt-space-lg" id="tab-shipping-returns">
<div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
<div class="bg-surface-container-low p-space-md rounded-xl flex flex-col gap-space-sm">
<h4 class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[20px]">local_shipping</span>ডেলিভারি চার্জ ও সময়সীমা</h4>
<ul class="flex flex-col gap-2 font-body-sm text-body-sm text-on-surface-variant">
<li class="flex justify-between py-1"><span>ঢাকা সিটির ভেতরে:</span><span class="font-bold text-on-surface">৳৬০ (২৪ থেকে ৪৮ ঘণ্টা)</span></li>
<li class="flex justify-between py-1"><span>ঢাকা সিটির বাইরে ও সারাদেশে:</span><span class="font-bold text-on-surface">৳১২০ (৪৮ থেকে ৭২ ঘণ্টা)</span></li>
<li class="flex justify-between py-1 text-primary font-bold"><span>ফ্রি ডেলিভারি অফার:</span><span>১,০০০ টাকার অধিক যেকোনো অর্ডারে ডেলিভারি চার্জ ফ্রি!</span></li>
<li class="pt-2">পণ্য পৌঁছানোর পূর্বে আমাদের কুরিয়ার প্রতিনিধি আপনার সাথে ফোনে যোগাযোগ করবেন।</li>
</ul>
</div>
<div class="bg-surface-container-low p-space-md rounded-xl flex flex-col gap-space-sm">
<h4 class="font-title-md text-title-md text-on-surface font-semibold flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[20px]">sync</span>রিটার্ন ও রিফান্ড নীতি</h4>
<ul class="flex flex-col gap-2 font-body-sm text-body-sm text-on-surface-variant">
@foreach (['ডেলিভারিম্যানের উপস্থিতিতে পণ্য চেক করে গ্রহণ করুন। ক্ষতিগ্রস্ত বা ভুল পণ্য এলে তাৎক্ষণিক ফেরত দিতে পারবেন।', 'প্যাকেজের মূল সিল অক্ষত থাকলে পণ্য প্রাপ্তির ৩ দিনের মধ্যে নিঃশর্ত রিটার্ন গ্রহণযোগ্য।', 'অনলাইন পেমেন্ট করা থাকলে রিটার্ন নিশ্চিতকরণের ৪৮ ঘণ্টার মধ্যে বিকাশ/নগদ বা ব্যাংকে রিফান্ড করা হয়।'] as $line)
<li class="flex items-start gap-2"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span><span>{{ $line }}</span></li>
@endforeach
</ul>
</div>
</div>
</div>
</div>
</section>
