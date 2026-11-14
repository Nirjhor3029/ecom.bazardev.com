{{-- Canonical Madhuban footer — desktop (lg:block) + mobile (lg:hidden) with bottom-nav,
     shared by every storefront page via layout.blade.php. --}}
<div class="hidden lg:block">
<footer class="w-full bg-surface-container-low pt-16 pb-8 shadow-[0_-1px_12px_rgba(0,0,0,0.03)]">
<div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
<div>
<div class="flex items-center gap-3 mb-4">
<div class="w-9 h-9 rounded-full bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary-container text-[20px]">hive</span></div>
<div class="flex flex-col"><span class="font-headline-sm text-headline-sm text-primary leading-tight font-bold">মধুবন</span><span class="font-label-sm text-label-sm text-secondary">প্রাকৃতিক মধু ও বাদাম</span></div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-6 leading-relaxed">মধুবন বাংলাদেশের প্রত্যন্ত অঞ্চল ও সুন্দরবনের মৌয়ালদের থেকে সরাসরি সংগৃহীত খাঁটি মধু ও প্রিমিয়াম গ্রেডের কাজু, কাঠবাদাম ও আখরোট সরবরাহ করে।</p>
<div class="flex items-center gap-2">
<a aria-label="Facebook" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">public</span></a>
<a aria-label="Instagram" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">photo_camera</span></a>
<a aria-label="YouTube" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">play_circle</span></a>
<a aria-label="WhatsApp" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="https://wa.me/"><span class="material-symbols-outlined text-[18px]">chat</span></a>
</div>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-4 font-bold">প্রয়োজনীয় লিংক</h3>
<ul class="space-y-2.5 font-body-sm text-body-sm">
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="{{ route('home') }}">হোম</a></li>
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="{{ route('products.index') }}">সব পণ্য</a></li>
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="{{ route('wishlist.index') }}">উইশলিস্ট</a></li>
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="{{ route('orders.track') }}">অর্ডার ট্র্যাক করুন</a></li>
</ul>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-4 font-bold">কাস্টমার সাপোর্ট</h3>
<ul class="space-y-2.5 font-body-sm text-body-sm">
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">সচরাচর জিজ্ঞাসা (FAQ)</a></li>
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">ডেলিভারি ও চার্জ</a></li>
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">রিটার্ন ও রিফান্ড পলিসি</a></li>
<li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">টার্মস ও প্রাইভেসি পলিসি</a></li>
</ul>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-4 font-bold">যোগাযোগ ও ঠিকানা</h3>
<ul class="space-y-3 font-body-sm text-body-sm text-on-surface-variant">
<li class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">call</span><div><span class="block font-bold text-on-surface">হটলাইন:</span><span>+880 1712-345678</span></div></li>
<li class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">mail</span><div><span class="block font-bold text-on-surface">ইমেইল:</span><span>support@madhuban.com.bd</span></div></li>
<li class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">location_on</span><div><span class="block font-bold text-on-surface">ঠিকানা:</span><span>বাড়ি #২৪, রোড #১০, বনানী, ঢাকা-১২১৩, বাংলাদেশ</span></div></li>
</ul>
</div>
</div>
<div class="max-w-7xl mx-auto px-6 lg:px-12 pt-6 border-t border-outline-variant/30 flex flex-col md:flex-row items-center justify-between gap-4">
<p class="font-body-sm text-body-sm text-on-surface-variant text-center md:text-left">Copyright © {{ now()->year }} মধুবন (Madhuban Artisan Provisions). সর্বস্বত্ব সংরক্ষিত.</p>
<div class="flex flex-wrap items-center justify-center gap-2 font-label-sm text-label-sm text-on-surface-variant">
<span class="px-2 py-1 bg-surface-container rounded">SSLCommerz</span><span class="px-2 py-1 bg-surface-container rounded font-bold text-error">bKash</span><span class="px-2 py-1 bg-surface-container rounded font-bold text-primary">Nagad</span><span class="px-2 py-1 bg-surface-container rounded">Rocket</span><span class="px-2 py-1 bg-surface-container rounded font-bold">VISA</span><span class="px-2 py-1 bg-surface-container rounded font-bold">Mastercard</span>
</div>
</div>
</footer>
</div>

<div class="lg:hidden">
<footer class="w-full bg-surface-container-low px-margin-mobile pt-space-lg pb-space-lg mt-space-xl">
<div class="flex flex-col gap-6">
<div class="flex flex-col gap-2">
<div class="flex items-center gap-2">
<div class="w-7 h-7 rounded-full bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary-container text-[16px]">hive</span></div>
<span class="font-headline-sm text-headline-sm text-primary font-bold">মধুবন</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">সুন্দরবনের খাঁটি মধু ও পাহাড়ি অর্গানিক বাদামের নির্ভরযোগ্য অনলাইন ঠিকানা। শতভাগ বিশুদ্ধতা ও স্বাস্থ্য সুরক্ষার নিশ্চয়তা।</p>
</div>
<div class="grid grid-cols-2 gap-3 py-2">
<div class="flex items-center gap-2 p-3 bg-surface-container-lowest rounded-lg shadow-[0_2px_8px_rgba(56,39,25,0.03)]"><span class="material-symbols-outlined text-primary text-[22px]">verified_user</span><div><p class="font-title-md text-[13px] leading-tight text-on-surface font-semibold">১০০% খাঁটি পণ্য</p><p class="font-body-sm text-[11px] text-on-surface-variant">ল্যাব টেস্ট সার্টিফাইড</p></div></div>
<div class="flex items-center gap-2 p-3 bg-surface-container-lowest rounded-lg shadow-[0_2px_8px_rgba(56,39,25,0.03)]"><span class="material-symbols-outlined text-primary text-[22px]">local_shipping</span><div><p class="font-title-md text-[13px] leading-tight text-on-surface font-semibold">দ্রুত ডেলিভারি</p><p class="font-body-sm text-[11px] text-on-surface-variant">সারাদেশে হোম সার্ভিস</p></div></div>
</div>
<div class="flex flex-col gap-2.5">
<p class="font-label-md text-label-md text-on-surface uppercase font-bold">পেমেন্ট পার্টনার</p>
<div class="flex flex-wrap items-center gap-2">
<span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">bKash</span><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">Nagad</span><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">Debit/Credit Card</span><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">ক্যাশ অন ডেলিভারি</span>
</div>
</div>
<div class="flex flex-col gap-2 pt-2">
<p class="font-label-md text-label-md text-on-surface uppercase font-bold">যোগাযোগ করুন</p>
<div class="flex gap-3">
<a class="flex-1 flex items-center justify-center gap-1.5 h-11 bg-primary text-on-primary rounded-full font-label-md text-label-md" href="tel:+8801700000000"><span class="material-symbols-outlined text-[18px]">call</span><span>কল করুন</span></a>
<a class="flex-1 flex items-center justify-center gap-1.5 h-11 bg-surface-container-lowest text-on-surface rounded-full font-label-md text-label-md shadow-[0_1px_6px_rgba(0,0,0,0.05)]" href="https://wa.me/"><span class="material-symbols-outlined text-[18px] text-primary">chat</span><span>হোয়াটসঅ্যাপ</span></a>
</div>
</div>
<div class="text-center pt-4"><p class="font-body-sm text-label-sm text-outline">© {{ now()->year }} মধুবন অর্গানিক ফুডস। সর্বস্বত্ব সংরক্ষিত।</p></div>
</div>
</footer>
<nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(56,39,25,0.06)]">
<div class="flex justify-around items-center h-16 px-gutter-mobile">
<a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 transition-colors {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-primary' }}" href="{{ route('home') }}"><span class="material-symbols-outlined text-[22px]">home</span><span class="font-label-sm text-label-sm">হোম</span></a>
<a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 transition-colors {{ request()->routeIs('products.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-primary' }}" href="{{ route('products.index') }}"><span class="material-symbols-outlined text-[22px]">storefront</span><span class="font-label-sm text-label-sm">ক্যাটালগ</span></a>
<a class="relative flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 transition-colors {{ request()->routeIs('cart.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-primary' }}" href="{{ route('cart.show') }}"><span class="material-symbols-outlined text-[22px]">shopping_bag</span>@if ($cartItemCount > 0)<span class="absolute top-1 right-2 min-w-[15px] h-[15px] bg-primary-container text-on-primary text-[9px] font-bold rounded-full flex items-center justify-center px-0.5">{{ $cartItemCount }}</span>@endif<span class="font-label-sm text-label-sm">কার্ট</span></a>
<a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-primary' }}" href="{{ route('wishlist.index') }}"><span class="material-symbols-outlined text-[22px]">favorite</span><span class="font-label-sm text-label-sm">উইশলিস্ট</span></a>
<a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 transition-colors {{ request()->routeIs('orders.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-primary' }}" href="{{ route('orders.track') }}"><span class="material-symbols-outlined text-[22px]">person</span><span class="font-label-sm text-label-sm">প্রোফাইল</span></a>
</div>
</nav>
</div>
