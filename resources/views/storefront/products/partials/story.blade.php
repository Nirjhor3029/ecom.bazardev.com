{{-- Desktop section 4: editorial origin story (data: $product->content['story']). --}}
@php
    $s = ($product->content ?? [])['story'] ?? null;
    $img = ! empty($s['image']) ? \Illuminate\Support\Facades\Storage::disk('public')->url($s['image']) : null;
@endphp
@if ($s)
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin py-space-xl">
<div class="bg-surface-container rounded-2xl overflow-hidden shadow-sm grid grid-cols-1 lg:grid-cols-12 items-center">
<div class="lg:col-span-5 h-80 lg:h-full relative min-h-[380px]">
@if ($img)<div class="w-full h-full bg-cover bg-center" role="img" aria-label="{{ $s['title'] ?? '' }}" style="background-image: url('{{ $img }}')"></div>@else<div class="w-full h-full bg-surface-container-high"></div>@endif
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/80 via-transparent to-transparent lg:hidden"></div>
<div class="absolute bottom-4 left-4 right-4 lg:hidden text-on-primary">
<span class="font-label-sm text-label-sm uppercase tracking-widest text-primary-fixed">{{ $s['eyebrow'] ?? '' }}</span>
<h3 class="font-headline-sm text-headline-sm font-semibold">{{ $s['title'] ?? '' }}</h3>
</div>
</div>
<div class="lg:col-span-7 p-space-md lg:p-space-xl flex flex-col gap-space-md">
<div class="hidden lg:flex flex-col">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-widest font-bold">{{ $s['eyebrow'] ?? '' }}</span>
<h2 class="font-headline-md text-headline-md text-on-surface font-semibold mt-1">{{ $s['title'] ?? '' }}</h2>
</div>
@foreach ($s['paragraphs'] ?? [] as $p)<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">{{ $p }}</p>@endforeach
@if (! empty($s['place']) || ! empty($s['trade']))
<div class="bg-surface-container-lowest p-space-md rounded-xl flex flex-wrap items-center justify-between gap-space-sm shadow-sm">
@if (! empty($s['place']))
<div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-primary-container/20 flex items-center justify-center text-primary"><span class="material-symbols-outlined text-[20px]">explore</span></div>
<div class="flex flex-col"><span class="font-label-sm text-label-sm text-on-surface-variant">সংগ্রহের স্থান</span><span class="font-title-md text-title-md text-on-surface font-semibold">{{ $s['place'] }}</span></div></div>
@endif
@if (! empty($s['trade']))
<div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-secondary-fixed/40 flex items-center justify-center text-secondary"><span class="material-symbols-outlined text-[20px]">handshake</span></div>
<div class="flex flex-col"><span class="font-label-sm text-label-sm text-on-surface-variant">বাণিজ্য নীতি</span><span class="font-title-md text-title-md text-on-surface font-semibold">{{ $s['trade'] }}</span></div></div>
@endif
</div>
@endif
</div>
</div>
</section>
@endif
