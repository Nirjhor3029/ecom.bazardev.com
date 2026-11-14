{{-- Editorial gift banner shown between listing rows. Uses $giftCat from the shop page. --}}
<div class="col-span-full relative w-full rounded-2xl overflow-hidden bg-gradient-to-r from-inverse-surface via-secondary to-inverse-surface text-inverse-on-surface p-space-md lg:p-space-lg shadow-md flex flex-col md:flex-row items-center justify-between gap-space-md lg:gap-space-lg">
<div class="relative z-10 flex flex-col gap-space-xs max-w-xl">
<span class="inline-flex items-center gap-1.5 text-primary-fixed font-label-sm text-label-sm uppercase tracking-widest font-bold"><span class="material-symbols-outlined text-[16px]">redeem</span>মধুবন সিগনেচার গিফট কালেকশন</span>
<h2 class="font-headline-md text-headline-md text-inverse-on-surface font-semibold leading-snug">প্রিয়জনের সুস্বাস্থ্যের সেরা উৎসব উপহার</h2>
<p class="font-body-md text-body-md text-inverse-on-surface/80">অভিজাত কাঠের বক্সে প্রিমিয়াম কাঁচা মধু ও রোস্টেড বাদামের রাজকীয় আয়োজন। কর্পোরেট ও পারিবারিক উপহারের জন্য প্রস্তুত।</p>
<div class="pt-space-xs">
<a href="{{ $giftCat ? route('products.index', ['filter' => ['category' => $giftCat->id]]) : route('products.index') }}" class="inline-flex px-space-lg py-2.5 rounded-full bg-primary-container hover:bg-primary hover:text-on-primary text-on-primary-container font-label-md text-label-md font-bold shadow-sm transition-all hover:scale-105 items-center gap-2"><span>কম্বো ও উপহার দেখুন</span><span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
</div>
</div>
@if ($giftCat && ($giftImg = $giftCat->imageUrl()))
<div class="relative w-full md:w-72 aspect-[4/3] rounded-xl overflow-hidden shadow-lg shrink-0">
<img class="w-full h-full object-cover" alt="{{ $giftCat->name['bn'] ?? '' }}" src="{{ $giftImg }}" loading="lazy">
<div class="absolute inset-0 bg-gradient-to-t from-inverse-surface/60 to-transparent"></div>
<div class="absolute bottom-2 left-2 right-2 bg-surface/90 backdrop-blur-sm p-1.5 rounded-lg text-center font-label-sm text-label-sm text-on-surface font-bold">সীমিত স্টক • ফ্রি গিফট কার্ড অন্তর্ভুক্ত</div>
</div>
@endif
</div>
