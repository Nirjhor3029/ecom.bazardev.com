{{-- Distraction-free checkout header: back to cart, logo, security note, helpline. --}}
<header class="fixed top-0 left-0 right-0 w-full z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
<div class="h-16 sm:h-20 max-w-7xl mx-auto px-gutter-mobile lg:px-margin flex items-center justify-between gap-gutter">
<div class="flex items-center gap-space-md">
<a href="{{ route('cart.show') }}" class="inline-flex items-center gap-space-xs text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined text-[20px]">arrow_back</span><span class="font-label-md text-label-md hidden sm:inline">কার্টে ফিরুন</span></a>
<div class="h-5 w-px bg-outline-variant/60 hidden sm:block"></div>
<a href="{{ route('home') }}" class="flex items-center gap-space-sm">
<div class="w-9 h-9 rounded-full bg-secondary-container flex items-center justify-center"><span class="material-symbols-outlined text-primary text-[22px]">local_florist</span></div>
<div class="flex flex-col"><span class="font-headline-sm text-headline-sm text-primary tracking-tight leading-none">মধুবন</span><span class="font-label-sm text-[10px] sm:text-label-sm text-on-surface-variant uppercase tracking-wider">Artisan Provisions</span></div>
</a>
</div>
<div class="hidden lg:flex items-center gap-space-xs px-space-md py-space-xs rounded-full bg-surface-container-low"><span class="material-symbols-outlined text-primary text-[18px]">verified_user</span><span class="font-label-md text-label-md text-on-surface-variant">১০০% সুরক্ষিত এনক্রিপ্টেড চেকআউট</span></div>
<div class="flex items-center gap-space-md">
<a href="tel:+8801712345678" class="hidden md:flex items-center gap-space-xs text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined text-[18px] text-primary">support_agent</span><div class="flex flex-col text-right"><span class="font-label-sm text-label-sm text-on-surface-variant">সহায়তা হটলাইন</span><span class="font-label-md text-label-md text-on-surface font-semibold">০১৭১২-৩৪৫৬৭৮</span></div></a>
<span class="lg:hidden inline-flex items-center gap-1 text-primary font-label-sm text-label-sm"><span class="material-symbols-outlined text-[16px]">lock</span>নিরাপদ</span>
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div>
</div>
</div>
</header>
