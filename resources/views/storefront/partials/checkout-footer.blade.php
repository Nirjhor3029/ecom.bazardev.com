{{-- Slim checkout footer: payment partners, security note, policy links. --}}
<footer class="w-full bg-surface-container-low pt-space-lg pb-28 lg:pb-space-md">
<div class="max-w-7xl mx-auto px-gutter-mobile lg:px-margin flex flex-col gap-space-md">
<div class="flex flex-col md:flex-row items-center justify-between gap-space-sm">
<div class="flex flex-col sm:flex-row items-center gap-space-sm"><span class="font-label-sm text-label-sm text-on-surface-variant">অনুমোদিত পেমেন্ট পার্টনার:</span>
<div class="flex items-center flex-wrap justify-center gap-space-xs">@foreach (['bKash', 'Nagad', 'Rocket', 'Visa', 'Mastercard', 'Cash on Delivery'] as $m)<span class="bg-surface-container-lowest px-space-sm py-1 rounded font-label-sm text-label-sm text-on-surface-variant shadow-sm">{{ $m }}</span>@endforeach</div></div>
<div class="flex items-center gap-space-sm"><span class="inline-flex items-center gap-1.5 px-space-sm py-1 rounded-full bg-surface-container-lowest font-label-sm text-label-sm text-primary shadow-sm"><span class="material-symbols-outlined text-[16px]">lock</span>256-Bit SSL Secured</span><span class="hidden sm:inline-flex items-center gap-1.5 px-space-sm py-1 rounded-full bg-surface-container-lowest font-label-sm text-label-sm text-primary shadow-sm"><span class="material-symbols-outlined text-[16px]">published_with_changes</span>সহজ রিটার্ন গ্যারান্টি</span></div>
</div>
<div class="border-t border-outline-variant/30 pt-space-sm flex flex-col md:flex-row items-center justify-between gap-2 font-body-sm text-body-sm text-on-surface-variant">
<span>© {{ now()->year }} মধুবন (Madhuban)। সর্বস্বত্ব সংরক্ষিত।</span>
<div class="flex items-center gap-space-md"><a href="#" class="hover:text-primary">গোপনীয়তা নীতি</a><a href="#" class="hover:text-primary">ব্যবহারের শর্তাবলী</a><a href="#" class="hover:text-primary">রিটার্ন ও রিফান্ড নীতি</a></div>
</div>
</div>
</footer>
