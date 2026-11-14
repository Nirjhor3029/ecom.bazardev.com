{{-- Desktop section 2: authenticity & sensory highlight cards (data: $product->content['highlights']). --}}
@php($h = ($product->content ?? [])['highlights'] ?? null)
@if ($h && ! empty($h['cards']))
@php($tones = ['primary' => ['bg-primary-fixed', 'text-primary'], 'tertiary' => ['bg-tertiary-fixed', 'text-tertiary'], 'secondary' => ['bg-secondary-fixed', 'text-secondary']])
<section class="w-full bg-surface-container-low py-space-xl my-space-lg">
<div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin">
<div class="flex flex-col md:flex-row md:items-end justify-between mb-space-lg">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">{{ $h['eyebrow'] ?? 'খাঁটি ও প্রাকৃতিক বৈশিষ্ট্য' }}</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-1">{{ $h['title'] ?? '' }}</h2>
</div>
@if (! empty($h['intro']))<p class="font-body-md text-body-md text-on-surface-variant max-w-md mt-2 md:mt-0">{{ $h['intro'] }}</p>@endif
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-md">
@foreach ($h['cards'] as $i => $card)
@php([$bg, $fg] = $tones[$card['tone'] ?? array_keys($tones)[$i % 3]] ?? $tones['primary'])
<div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm flex items-start gap-space-sm hover:shadow-md transition-shadow">
<div class="w-12 h-12 rounded-xl {{ $bg }} flex items-center justify-center shrink-0 {{ $fg }}"><span class="material-symbols-outlined text-[24px]">{{ $card['icon'] ?? 'eco' }}</span></div>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">{{ $card['label'] ?? '' }}</span>
<h3 class="font-title-md text-title-md text-on-surface font-semibold mt-0.5">{{ $card['title'] ?? '' }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $card['text'] ?? '' }}</p>
</div>
</div>
@endforeach
</div>
</div>
</section>
@endif
