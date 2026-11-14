{{-- Canonical Madhuban header — desktop (lg:block) + mobile (lg:hidden), shared by every
     storefront page via layout.blade.php. $navCategories/$cartItemCount/$cartSubtotal/
     $wishlistCount are shared globally via the View::composer in AppServiceProvider. --}}
<div class="hidden lg:block">
<header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
<div class="bg-primary-container text-on-primary-container px-gutter py-1.5">
<div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-1 text-center md:text-left">
<div class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">local_shipping</span><span class="font-label-sm text-label-sm">বিশুদ্ধ প্রাকৃতিক মধু ও বাদাম এখন আপনার হাতেই | সারা দেশে ৩-৫ দিনে ক্যাশ অন ডেলিভারি | ১০০০ টাকার অর্ডারে ফ্রি ডেলিভারি!</span></div>
<div class="flex items-center gap-4 font-label-sm text-label-sm"><div class="flex items-center gap-1"><span class="material-symbols-outlined text-[15px]">call</span><span>+880 1712-345678</span></div></div>
</div>
</div>
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-3 flex items-center justify-between gap-4">
<a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
<div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary-container text-[24px]">hive</span></div>
<div class="flex flex-col"><span class="font-headline-sm text-headline-sm text-primary leading-tight font-bold">মধুবন</span><span class="font-label-sm text-label-sm text-secondary tracking-wide">প্রাকৃতিক মধু ও বাদাম</span></div>
</a>
<form action="{{ route('products.index') }}" class="hidden md:flex flex-1 max-w-xl mx-4">
<div class="relative w-full flex items-center">
<input type="text" name="q" value="{{ request('q') }}" class="w-full bg-surface-container-lowest rounded-full py-2.5 pl-5 pr-11 text-on-surface placeholder:text-on-surface-variant/70 text-body-sm font-body-sm shadow-[0_2px_12px_rgba(56,39,25,0.04)] focus:outline-none focus:ring-1 focus:ring-primary-container" placeholder="আপনি কী খুঁজছেন? (যেমন: সুন্দরবনের খলিশা মধু, কাজুবাদাম...)">
<button type="submit" class="absolute right-1.5 p-1.5 rounded-full bg-primary-container text-on-primary-container hover:bg-primary hover:text-on-primary transition-colors flex items-center justify-center"><span class="material-symbols-outlined text-[18px]">search</span></button>
</div>
</form>
<div class="flex items-center gap-3 lg:gap-4 shrink-0">
<a href="{{ route('wishlist.index') }}" aria-label="Wishlist" class="relative p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center">
<span class="material-symbols-outlined text-[22px]">favorite</span>
@if ($wishlistCount > 0)<span class="absolute top-1 right-1 bg-primary text-on-primary text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $wishlistCount }}</span>@endif
</a>
<a href="{{ route('orders.track') }}" class="hidden sm:flex items-center gap-1.5 text-on-surface-variant hover:text-on-surface px-2.5 py-1.5 rounded-full hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined text-[20px]">account_circle</span><span class="font-label-md text-label-md">{{ auth('customer')->check() ? auth('customer')->user()->phone : 'প্রবেশ / একাউন্ট' }}</span>
</a>
<a href="{{ route('cart.show') }}" class="flex items-center gap-2 bg-primary-container text-on-primary-container px-3.5 py-2 rounded-full hover:bg-primary hover:text-on-primary transition-all shadow-[0_4px_16px_rgba(217,154,39,0.2)]">
<span class="material-symbols-outlined text-[20px]">shopping_bag</span>
<span class="font-label-md text-label-md whitespace-nowrap">ঝুড়ি ({{ $cartItemCount }}) - ৳{{ number_format($cartSubtotal / 100, 0) }}</span>
</a>
</div>
</div>
<div class="bg-surface-container-low">
<div class="max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between">
<nav class="flex items-center gap-1 py-1.5 overflow-x-auto">
<a href="{{ route('home') }}" class="px-3.5 py-1.5 whitespace-nowrap transition-colors rounded-full font-label-md text-label-md {{ request()->routeIs('home') ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">হোম</a>
<a href="{{ route('products.index') }}" class="px-3.5 py-1.5 whitespace-nowrap transition-colors rounded-full font-label-md text-label-md {{ request()->routeIs('products.index') && ! request('filter.category') ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">সব পণ্য</a>
@foreach ($navCategories as $navCat)
<a href="{{ route('products.index', ['filter' => ['category' => $navCat->id]]) }}" class="px-3.5 py-1.5 whitespace-nowrap transition-colors rounded-full font-label-md text-label-md {{ (int) request('filter.category') === $navCat->id ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' }}">{{ $navCat->name['bn'] ?? '' }}</a>
@endforeach
</nav>
<div class="hidden lg:flex items-center gap-1.5 text-primary font-label-sm text-label-sm"><span class="material-symbols-outlined text-[16px]">verified</span><span>১০০% খাঁটি ও প্রত্যয়িত</span></div>
</div>
</div>
</header>
</div>

<div class="lg:hidden">
<header class="fixed top-0 w-full z-50 pt-safe bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
<div class="bg-primary text-on-primary text-center py-1 px-4 text-label-sm font-label-sm tracking-wider flex items-center justify-center gap-1.5"><span class="material-symbols-outlined text-[14px]">local_shipping</span><span>বিশুদ্ধ মধু ও বাদাম | ১০০০ টাকার অর্ডারে ফ্রি ডেলিভারি</span></div>
<div class="h-28 px-margin-mobile flex flex-col justify-center gap-2">
<div class="flex items-center justify-between gap-2">
<a href="{{ route('products.index') }}" aria-label="Menu" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface hover:bg-surface-variant/40 transition-colors"><span class="material-symbols-outlined text-[24px]">menu</span></a>
<a href="{{ route('home') }}" class="flex items-center gap-2">
<div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center"><span class="material-symbols-outlined text-on-primary-container text-[18px]">hive</span></div>
<span class="font-headline-sm text-headline-sm text-primary font-bold tracking-tight">মধুবন</span>
</a>
<div class="flex items-center gap-1">
<a href="{{ route('wishlist.index') }}" aria-label="উইশলিস্ট" class="relative w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined text-[22px]">favorite</span>
@if ($wishlistCount > 0)<span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] bg-primary-container text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow-sm">{{ $wishlistCount }}</span>@endif
</a>
<a href="{{ route('cart.show') }}" aria-label="কার্ট" class="relative w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:text-primary transition-colors">
<span class="material-symbols-outlined text-[22px]">shopping_bag</span>
@if ($cartItemCount > 0)<span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] bg-primary-container text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow-sm">{{ $cartItemCount }}</span>@endif
</a>
<a href="{{ route('orders.track') }}" class="w-8 h-8 rounded-full bg-primary flex items-center justify-center ml-1"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></a>
</div>
</div>
<form action="{{ route('products.index') }}" class="w-full">
<div class="relative flex items-center">
<span class="absolute left-3.5 material-symbols-outlined text-[18px] text-outline pointer-events-none">search</span>
<input type="search" name="q" value="{{ request('q') }}" class="w-full h-10 pl-10 pr-4 bg-surface-container-lowest rounded-full font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-1 focus:ring-primary shadow-[0_2px_12px_rgba(56,39,25,0.04)]" placeholder="আপনি কী খুঁজছেন?...">
</div>
</form>
</div>
</header>
</div>
