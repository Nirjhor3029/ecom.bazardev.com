@extends('storefront.layout')

@section('title', 'মধুবন — Madhuban Artisanal Provisions')

@section('bare-chrome', 'yes')

@section('content')
<!-- ================= DESKTOP (lg and up) — ported verbatim from home_code.html ================= -->
<div class="hidden lg:block">
<header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]"><div class="bg-primary-container text-on-primary-container px-gutter py-1.5"><div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-1 text-center md:text-left"><div class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">local_shipping</span><span class="font-label-sm text-label-sm">বিশুদ্ধ প্রাকৃতিক মধু ও বাদাম এখন আপনার হাতেই | সারা দেশে ৩-৫ দিনে ক্যাশ অন ডেলিভারি | ১০০০ টাকার অর্ডারে ফ্রি ডেলিভারি!</span></div><div class="flex items-center gap-4 font-label-sm text-label-sm"><div class="flex items-center gap-1"><span class="material-symbols-outlined text-[15px]">call</span><span>+880 1712-345678</span></div><div class="flex items-center gap-1 font-bold"><button class="hover:text-on-primary" type="button">বাং</button><span>|</span><button class="hover:text-on-primary" type="button">EN</button></div></div></div></div><div class="max-w-7xl mx-auto px-6 lg:px-12 py-3 flex items-center justify-between gap-4"><div class="flex items-center gap-3 shrink-0"><img alt="Madhuban Artisanal Logo" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFIFNGzXweVjOqxHaPw7vl4mRqYapFexb7sTEax30b6KddO6J55BazqkZwtgLDTkmQEFTrk1YSc60_IRHqZUmc9KpdGBJCl2y-uKh1sPuQypVOcHyEJ09Mc362OEEMkF5VpdNL3y0EjdiCOikFdMQDA5BPL3Qr0W1RXvUNakkvmyukpng3n5U1bhFQpGi6Nt_VC35KHVWXv6LK3Wdh1h_SPqqd1pOMT1tXLI8F5AVv"/><div class="flex flex-col"><span class="font-headline-sm text-headline-sm text-primary leading-tight font-bold">মধুবন</span><span class="font-label-sm text-label-sm text-secondary tracking-wide">প্রাকৃতিক মধু ও বাদাম</span></div></div><div class="hidden md:flex flex-1 max-w-xl mx-4"><div class="relative w-full flex items-center"><input class="w-full bg-surface-container-lowest rounded-full py-2.5 pl-5 pr-11 text-on-surface placeholder:text-on-surface-variant/70 text-body-sm font-body-sm shadow-[0_2px_12px_rgba(56,39,25,0.04)] focus:outline-none focus:ring-1 focus:ring-primary-container" placeholder="আপনি কী খুঁজছেন? (যেমন: সুন্দরবনের খলিশা মধু, কাজুবাদাম...)" type="text"/><button class="absolute right-1.5 p-1.5 rounded-full bg-primary-container text-on-primary-container hover:bg-primary hover:text-on-primary transition-colors flex items-center justify-center" type="button"><span class="material-symbols-outlined text-[18px]">search</span></button></div></div><div class="flex items-center gap-3 lg:gap-4 shrink-0"><button aria-label="Wishlist" class="relative p-2 text-on-surface-variant hover:text-on-surface rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center" type="button"><span class="material-symbols-outlined text-[22px]">favorite</span><span class="absolute top-1 right-1 bg-primary text-on-primary text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span></button><div class="flex items-center gap-2"><button class="hidden sm:flex items-center gap-1.5 text-on-surface-variant hover:text-on-surface px-2.5 py-1.5 rounded-full hover:bg-surface-container-high transition-colors" type="button"><span class="material-symbols-outlined text-[20px]">account_circle</span><span class="font-label-md text-label-md">প্রবেশ / একাউন্ট</span></button><div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div></div><button class="flex items-center gap-2 bg-primary-container text-on-primary-container px-3.5 py-2 rounded-full hover:bg-primary hover:text-on-primary transition-all shadow-[0_4px_16px_rgba(217,154,39,0.2)]" type="button"><span class="material-symbols-outlined text-[20px]">shopping_bag</span><span class="font-label-md text-label-md whitespace-nowrap">ঝুড়ি (৩) - ৳১,৭৮০</span></button></div></div><div class="bg-surface-container-low"><div class="max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between"><nav class="flex items-center gap-1 py-1.5 overflow-x-auto" data-active-classes="bg-primary-container text-on-primary-container rounded-full font-bold"><a aria-current="page" class="px-3.5 py-1.5 whitespace-nowrap transition-colors bg-primary-container text-on-primary-container rounded-full font-bold" data-path="home" href="#">হোম</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="shop" href="#">সব পণ্য</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="honey-collection" href="#">মধু</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="nuts-and-seeds" href="#">বাদাম</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="gift-boxes" href="#">কম্বো ও গিফট বক্স</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="purity-and-lab" href="#">ল্যাব টেস্ট ও বিশুদ্ধতা</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="about-us" href="#">আমাদের গল্প</a><a class="px-3.5 py-1.5 font-label-md text-label-md whitespace-nowrap text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-full transition-colors" data-path="contact" href="#">যোগাযোগ</a></nav><div class="hidden lg:flex items-center gap-1.5 text-primary font-label-sm text-label-sm"><span class="material-symbols-outlined text-[16px]">verified</span><span>১০০% খাঁটি ও প্রত্যয়িত</span></div></div></div></header><main class="w-full pt-36 bg-surface min-h-screen"><div class="flex flex-col w-full">
<!-- 1. Hero Section -->
<section class="relative w-full overflow-hidden bg-surface-container-low py-12 md:py-20 lg:py-24">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
<!-- Left Content -->
<div class="lg:col-span-7 flex flex-col items-start z-10">
<div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm uppercase tracking-wider mb-6">
<span class="w-2 h-2 rounded-full bg-primary-container"></span>
            PURE • NATURAL • AUTHENTIC
          </div>
<h1 class="font-display-hero text-display-hero text-on-surface mb-6 leading-tight">
            প্রকৃতির বিশুদ্ধতা, <br/>
<span class="italic text-primary">প্রতিটি ফোঁটায়</span>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl mb-8 leading-relaxed">
            বিশুদ্ধ প্রাকৃতিক মধু ও পুষ্টিকর বাদাম সংগ্রহ করুন সরাসরি বিশ্বস্ত উৎস থেকে। সুন্দরবনের গভীর অরণ্য ও বাছাইকৃত বাগানের সেরা উপাদান।
          </p>
<div class="flex flex-wrap items-center gap-4 mb-10 w-full sm:w-auto">
<a class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-all duration-300 font-label-md text-label-md shadow-md hover:-translate-y-0.5" href="#featured-products">
<span class="material-symbols-outlined text-[20px]">shopping_bag</span>
<span>এখনই কিনুন</span>
</a>
<a class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-surface-container text-on-surface hover:bg-secondary-container transition-all duration-200 font-label-md text-label-md" href="#brand-story">
<span>আমাদের গল্প জানুন</span>
<span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<!-- Trust Badges Under Hero CTA -->
<div class="flex flex-wrap items-center gap-y-3 gap-x-6 pt-6 border-t-0">
<div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
<span class="material-symbols-outlined text-primary text-[18px]">verified</span>
<span>BSTI মানসম্পন্ন</span>
</div>
<div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
<span class="material-symbols-outlined text-primary text-[18px]">science</span>
<span>ল্যাব টেস্টে ১০০% খাঁটি</span>
</div>
<div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
<span class="material-symbols-outlined text-primary text-[18px]">local_shipping</span>
<span>৩ দিনে সারা দেশে ডেলিভারি</span>
</div>
</div>
</div>
<!-- Right Visual Showcase -->
<div class="lg:col-span-5 relative flex justify-center">
<div class="relative w-full max-w-md lg:max-w-none">
<!-- Decorative atmospheric warmth circle -->
<div class="absolute -top-10 -right-10 w-72 h-72 rounded-full bg-primary-fixed-dim/20 blur-3xl pointer-events-none"></div>
<div class="relative rounded-2xl overflow-hidden shadow-xl bg-surface-container-lowest">
<img class="w-full h-[460px] object-cover hover:scale-105 transition-transform duration-700 ease-out" alt="Close-up of a premium hexagonal glass jar filled with rich amber natural honey, wooden honey dipper drizzling thick golden honey, accompanied by fresh raw honeycomb chunks and crisp whole California almonds and cashew nuts scattered over a rustic weathered wooden surface bathed in gentle morning sunlight" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA447l7iHQc5nDpH_EvNNRuxvePyA7jecbFBrUb8jfPzCqo874mXWFz8CrxOzSS1Z5j_V5b0sYCxffkvROD_dyGQokjzw6Uymc3vWXvcTo87CZsi_Le0XYByhG2bfwEiFEoJygawvRLqi3YfUCm0VedJGNwe212kz1_WXJ_agpGS0RkggOZQbrppPiIOR-Mq63OaSuLVyMg2nkp9QedwKtaUK9h3M-uJ2Tx_CPjnZy6"/>
<div class="absolute bottom-4 left-4 right-4 p-4 rounded-xl bg-surface/90 backdrop-blur-md shadow-sm flex items-center justify-between">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase block">Sundarban Wild Reserve</span>
<span class="font-headline-sm text-headline-sm text-on-surface block">১০০% র খলিশা মধু</span>
</div>
<div class="text-right">
<span class="font-label-sm text-label-sm text-on-surface-variant line-through block">৳৮৫০</span>
<span class="font-title-lg text-title-lg text-primary font-bold">৳৭৫০</span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- 2. Trust Indicators Bar -->
<section class="w-full bg-surface-container py-10">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex items-start gap-4">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center shrink-0 text-on-primary-fixed">
<span class="material-symbols-outlined text-[24px]">eco</span>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-1">১০০% প্রাকৃতিক উপাদান</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">কোনো রাসায়নিক বা চিনি মিশ্রণ ছাড়া সরাসরি প্রাকৃতিক উৎস থেকে।</p>
</div>
</div>
<div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex items-start gap-4">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center shrink-0 text-on-primary-fixed">
<span class="material-symbols-outlined text-[24px]">chips</span>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-1">বিশুদ্ধতা ও মান</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">প্রতি ব্যাচের ল্যাব রিপোর্ট ও সঠিক আদ্রতা নিশ্চিতকরণ।</p>
</div>
</div>
<div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex items-start gap-4">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center shrink-0 text-on-primary-fixed">
<span class="material-symbols-outlined text-[24px]">inventory_2</span>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-1">নিরাপদ প্যাকেজিং</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">ফুড-গ্রেড এয়ারটাইট কাচের জার ও প্রিমিয়াম প্রটেক্টিভ সিল।</p>
</div>
</div>
<div class="p-6 rounded-xl bg-surface-container-lowest shadow-sm flex items-start gap-4">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center shrink-0 text-on-primary-fixed">
<span class="material-symbols-outlined text-[24px]">local_shipping</span>
</div>
<div>
<h3 class="font-title-md text-title-md text-on-surface mb-1">সারা দেশে ডেলিভারি</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">দ্রুত হোম ডেলিভারি ও সহজে ক্যাশ অন ডেলিভারি (COD)।</p>
</div>
</div>
</div>
</div>
</section>
<!-- 3. Category Section -->
<section class="w-full py-20 bg-surface">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="text-center max-w-2xl mx-auto mb-14">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">সংগ্রহশালা</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-3">আমাদের পণ্যসমূহ</h2>
<p class="font-body-md text-body-md text-on-surface-variant">আপনার পছন্দের প্রাকৃতিক পণ্য খুঁজে নিন সরাসরি সংগৃহীত সেরা মান থেকে</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Category 1 -->
<a class="group relative rounded-2xl overflow-hidden shadow-sm bg-surface-container-low flex flex-col h-96" href="#honey">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Artisanal glass bottles of Sundarban, mustard, and litchi blossom raw honey glowing warmly under soft light with wild flora and wooden spoons nearby in warm golden tones" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7CIeX_lKoqkm9TUOwFj6UwZMA4IQ-co6znWZUmgT8F5CU-17pKTOFd73h8smp4cGFOE10RxbExhV7iS-0trdCVxKdOaUXSOE3y4eQg1y8bUhWOJtD9uCYd7ZkN0BlfMZNlaDigREzn8ReBuMJz8R26qyCCYBB20xAuk8XTj1c0cKdPkoQN-sLcCmIspSiPzbdh3irx-31qgbgZQ7BJgA9eSAvo3YEzW5CiiE-Vx1a"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent flex flex-col justify-end p-6">
<span class="px-3 py-1 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm w-fit mb-2">প্রাকৃতিক মধু</span>
<h3 class="font-headline-sm text-headline-sm text-surface mb-1">খাঁটি মধু</h3>
<p class="font-body-sm text-body-sm text-surface/80 mb-3">সুন্দরবন, সরিষা ও লিচু ফুলের র ভ্যারাইটি</p>
<span class="inline-flex items-center gap-1.5 font-label-md text-label-md text-primary-fixed group-hover:translate-x-1 transition-transform">
              অন্বেষণ করুন <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</span>
</div>
</a>
<!-- Category 2 -->
<a class="group relative rounded-2xl overflow-hidden shadow-sm bg-surface-container-low flex flex-col h-96" href="#nuts">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Assorted premium roasted nuts including golden cashews, whole California almonds, walnuts, and green pistachios in handcrafted ceramic bowls against natural jute cloth" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCROuSzNGYxKKqiUIfAIlQSs2Z3LrwrbmIQ9OMYpeuAKUKQ2ddEgS_y9tbywcOds2kgfaUjbdRXtMSWd5Rt9xCjAU1AZGBBOJ--wGrXeVy6ogJ74no8Z_Tedh0IHq9B8C-gbzoKMZAfT-J_9UIbxP0sF7KgveSxldVW7dapNvIypvZ57XQ2d01AIKI4KOVdO0_O2sqG0ZvNRBcB6v-3-nsjbvlWOhsGCicoN9jTsbVi"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent flex flex-col justify-end p-6">
<span class="px-3 py-1 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm w-fit mb-2">পুষ্টিকর বাদাম</span>
<h3 class="font-headline-sm text-headline-sm text-surface mb-1">প্রিমিয়াম বাদাম</h3>
<p class="font-body-sm text-body-sm text-surface/80 mb-3">রোস্টেড কাজু, কাঠবাদাম, আখরোট ও পেস্তা</p>
<span class="inline-flex items-center gap-1.5 font-label-md text-label-md text-primary-fixed group-hover:translate-x-1 transition-transform">
              অন্বেষণ করুন <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</span>
</div>
</a>
<!-- Category 3 -->
<a class="group relative rounded-2xl overflow-hidden shadow-sm bg-surface-container-low flex flex-col h-96" href="#combos">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Nutritional combo package of honey jar alongside assorted nut mixes and dried fruit seed containers displayed on a warm stone kitchen table with morning ambient light" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjo5RbySKaE1xAVMLUGPlnpDG1k39J8Ms1mxwxDeyrEtIAAe0za4x_C1J-hwnDogeVMqWzPmZKpYSGTRxY_3QZgWPCObfJ4-yWdA1LMfmQgXJnrWTTQ4mUg2WwO9jk7_hiOK8TmLyWE9OpqceOnujjdv13gWn_n-Bt9aThWet4Pi8_-iKThn2VZL1Vjd0JCrv0k4MLChfrwAfSPX7vY8pmoNAkR69L79S9yxBB7evZ"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent flex flex-col justify-end p-6">
<span class="px-3 py-1 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm w-fit mb-2">স্বাস্থ্য প্যাক</span>
<h3 class="font-headline-sm text-headline-sm text-surface mb-1">হানি কম্বো</h3>
<p class="font-body-sm text-body-sm text-surface/80 mb-3">পারিবারিক সুস্থতায় বিশেষ সমন্বয় প্যাক</p>
<span class="inline-flex items-center gap-1.5 font-label-md text-label-md text-primary-fixed group-hover:translate-x-1 transition-transform">
              অন্বেষণ করুন <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</span>
</div>
</a>
<!-- Category 4 -->
<a class="group relative rounded-2xl overflow-hidden shadow-sm bg-surface-container-low flex flex-col h-96" href="#gifts">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Luxury engraved wooden gift box with satin ribbon featuring artisanal honey jars, a brass honey wand, and premium glass jars of dry fruits on an opulent warm table" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTBGZGlLMHJAHevYsreKQdQlEjmuZrNsh9l7GeIu8F7l6nEI4HumwydNzBEgcl6impwnxSMS2NhYp7FTpq1jszvXoHdEVE0VtmIGwHld5MehJdqyae2aetKewNBDUgrke1PygCP2yKS9XxVhnLvZqZetya5AABNhakG-4uymcd98DzqTddUKQ-3gx87x9P47vnlU1tuuS-vCvKRdvg1NlQfowO1hsJZySXVkDuZy3B"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent flex flex-col justify-end p-6">
<span class="px-3 py-1 rounded-full bg-primary-container text-on-primary-container font-label-sm text-label-sm w-fit mb-2">উপহারের সমাহার</span>
<h3 class="font-headline-sm text-headline-sm text-surface mb-1">গিফট বক্স</h3>
<p class="font-body-sm text-body-sm text-surface/80 mb-3">উৎসব ও করপোরেট উপহারের বিশেষ হ্যাম্পার</p>
<span class="inline-flex items-center gap-1.5 font-label-md text-label-md text-primary-fixed group-hover:translate-x-1 transition-transform">
              অন্বেষণ করুন <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</span>
</div>
</a>
</div>
</div>
</section>
<!-- 4. Featured Products Grid -->
<section class="w-full py-20 bg-surface-container-low" id="featured-products">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">সেরা পছন্দ</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface">জনপ্রিয় পণ্য</h2>
</div>
<a class="inline-flex items-center gap-2 font-label-md text-label-md text-primary hover:text-on-surface transition-colors self-start md:self-auto" href="#shop-all">
<span>সব পণ্য দেখুন</span>
<span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Product 1 -->
<div class="group rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between relative">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Studio photograph of Sundarban Khalisha honey in an artisanal glass jar with cork finish, deep amber liquid with rich viscosity, isolated on soft warm cream background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC5QNnTNE0Frg4a7_6alGZsMUWSX23joA7WHcc7cOnFelmTdzrotMEQBmmKNlM2c7cIZGy60MfeqtPxWt882q6k3n8sEMdbmXb6-Lk-iyilB1fYWGXUer4XVwS6xY93US3Xh_OUKahH5B2u66jOmuHO2a_fLyeE0glRfyVa71kIIrKmLfVuDyAP_VmM5FR_v39AFTaKJv0mYZc7YFgJRP8ndsTSRHps19IvFbK3i_qR"/>
<div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
<span class="px-2.5 py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-label-sm">বেস্ট সেলার</span>
<span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">১২% ছাড়</span>
</div>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div class="flex flex-col flex-1">
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৮</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(১২৮ রিভিউ)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">৫০০ গ্রাম গ্লাস জার</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3 group-hover:text-primary transition-colors">সুন্দরবনের খলিশা মধু</h3>
<div class="mt-auto flex items-center justify-between pt-3">
<div>
<span class="font-body-sm text-body-sm text-on-surface-variant line-through block">৳৮৫০</span>
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৭৫০</span>
</div>
<button class="px-4 py-2 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors flex items-center gap-1.5 font-label-md text-label-md">
<span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
<span>যুক্ত করুন</span>
</button>
</div>
</div>
</div>
<!-- Product 2 -->
<div class="group rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between relative">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Pure golden mustard blossom honey in an elegant glass packaging showing delicate crystallization texture, resting on rustic wood with dried yellow mustard flowers nearby" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB0m1NFXafiUWRRc7bfLKvqUS9HyyWjWdbm9to49mRX2GiwfRAE2dfD1_hpd8lwxLlc_IP2AIlfan3VxmHeBqtdpmjRtE9ZvRfu5ROe5K-OGxvSPAuiadHJx7uqktx3woYkdshKvzcpFsaG-5yGJLcVj5VJWAYWjPs4y2CFUsCCeIFfTN7E2vDiynEvHjitNyWktw9yWNHLwunhyjlumwjPfaMjW8_vaE3W8NFBCB2-"/>
<div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
<span class="px-2.5 py-0.5 rounded-full bg-tertiary-container text-on-tertiary-container font-label-sm text-label-sm">অর্গানিক সার্টিফাইড</span>
<span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">১১% ছাড়</span>
</div>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div class="flex flex-col flex-1">
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৭</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(৯৪ রিভিউ)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">৫০০ গ্রাম গ্লাস জার</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3 group-hover:text-primary transition-colors">সরিষা ফুলের প্রাকৃতিক মধু</h3>
<div class="mt-auto flex items-center justify-between pt-3">
<div>
<span class="font-body-sm text-body-sm text-on-surface-variant line-through block">৳৬৫০</span>
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৫৮০</span>
</div>
<button class="px-4 py-2 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors flex items-center gap-1.5 font-label-md text-label-md">
<span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
<span>যুক্ত করুন</span>
</button>
</div>
</div>
</div>
<!-- Product 3 -->
<div class="group rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between relative">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Crispy oven-roasted whole cashew nuts grade W240 inside a premium minimalist kraft vacuum pouch with transparent window showing golden toasted nuts" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJfK1oXC0yAEmrGm7XECS580nTStjX_EcKO4DSOwNVtNBGezSUf-UxPU1bFT3rAM111rP-AhjB8KQu8II6kD16vSP6ZbTY5cz0j84Z11PHTLf2qyC9KwklQoKALI8pElByH-ypm21m-4XHDKDCJigMSKH6pPIe_-5Orxp07JaZMOe07wMa38lEgkmSHPSGcJKnUyMsSHxRbaYfuKH0Axip0-KQzurYwPbUgYxCrNHh"/>
<div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
<span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">W240 গ্রেড</span>
</div>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div class="flex flex-col flex-1">
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৯</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(২০৪ রিভিউ)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">২৫০ গ্রাম ভ্যাকুয়াম ফয়েল পাউচ</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3 group-hover:text-primary transition-colors">প্রিমিয়াম রোস্টেড কাজুবাদাম</h3>
<div class="mt-auto flex items-center justify-between pt-3">
<div>
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৪৫০</span>
</div>
<button class="px-4 py-2 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors flex items-center gap-1.5 font-label-md text-label-md">
<span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
<span>যুক্ত করুন</span>
</button>
</div>
</div>
</div>
<!-- Product 4 -->
<div class="group rounded-2xl bg-surface-container-lowest p-4 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col justify-between relative">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Top-grade California raw almonds neatly presented in an eco-friendly food container, uniform size and natural reddish-brown skin visible in warm studio lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnXqDHdqulVEOs3va3JY0QCqt6qCFlp1IHva7devltgnxib_oiOX-0Y0ZPxfQUTTlIuRox_K8jff_nER9N8LCn9aC6gt4Bhpa1sbFpT63oOJskoEpXYiYFzqvz7KkHTXxpadJ1td7mL6YBgKrDlCbbgu2p7BpEb5IxoYMr1GLNMmZj4jea0c6qePGySicd6w70iZ-VdUa6Zn7aZlN5YSO_T2PZIDfJp2Y6YU1nIr7V"/>
<div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5">
<span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">আমদানি করা ফ্রেশ</span>
</div>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div class="flex flex-col flex-1">
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৮</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(১৫৬ রিভিউ)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">২৫০ গ্রাম ভ্যাকুয়াম প্যাক</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3 group-hover:text-primary transition-colors">ক্যালিফোর্নিয়া প্রিমিয়াম কাঠবাদাম</h3>
<div class="mt-auto flex items-center justify-between pt-3">
<div>
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৩৮০</span>
</div>
<button class="px-4 py-2 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors flex items-center gap-1.5 font-label-md text-label-md">
<span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
<span>যুক্ত করুন</span>
</button>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- 5. Brand Story Section -->
<section class="w-full py-20 bg-surface" id="brand-story">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
<!-- Left: Image Showcase -->
<div class="lg:col-span-6 relative">
<div class="relative rounded-2xl overflow-hidden shadow-lg">
<img class="w-full h-[520px] object-cover" alt="Traditional Sundarban honey collector Mouyal carefully harvesting wild honeycomb inside the lush green mangrove forest, carrying a traditional woven basket with gentle natural smoke rising around" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB0_VfA9g5tjAJON9lrZ995r3lPeSPoMVGcneF037Z6dFN7KTs2-XFVvrICwGfhF3cxJgm2f3U9UH9PvzhAjVI5FZ4qnzYJYaGR9uqamY2oJLwrin4XXH4FBT8ClwEODc1NlKAV7-EATHqLdIv-tjRt1DiZx82wsuLQZjiiNI0qQFidjvsJKn7vg10d_B6Tw_ZsalW0mS3K2Z9RcKeWr9_JWhxFFc7zdoYlFa3_2CDA"/>
</div>
<!-- Overlapping badge card -->
<div class="absolute -bottom-6 -right-4 md:right-8 p-5 rounded-xl bg-surface-container-lowest shadow-xl flex items-center gap-4 max-w-xs">
<div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center shrink-0 text-on-primary-fixed">
<span class="material-symbols-outlined text-[24px]">workspace_premium</span>
</div>
<div>
<span class="font-title-md text-title-md text-on-surface font-bold block">ন্যায্য মূল্য ও মর্যাদা</span>
<span class="font-body-sm text-body-sm text-on-surface-variant block">সুন্দরবনের মৌয়ালদের সাথে সরাসরি অংশীদারিত্ব</span>
</div>
</div>
</div>
<!-- Right: Story Narrative -->
<div class="lg:col-span-6 flex flex-col items-start lg:pl-6">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">আমাদের ঐতিহ্য</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-6 leading-tight">
            প্রকৃতির কাছ থেকে, <br/>
<span class="italic text-primary">আপনার পরিবারের জন্য</span>
</h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-4 leading-relaxed">
            মধুবনের যাত্রা শুরু হয়েছিল একটি সহজ বিশ্বাস থেকে—খাদ্য হওয়া উচিত খাঁটি, অকৃত্রিম এবং প্রকৃতির স্পর্শে সমৃদ্ধ। আমরা কোনো কৃত্রিম প্রিজারভেটিভ, চিনি মিশ্রণ বা অতিরিক্ত তাপে মধু প্রক্রিয়াজাত করি না।
          </p>
<p class="font-body-md text-body-md text-on-surface-variant mb-6 leading-relaxed">
            আমাদের মধু সুন্দরবনের গভীর ম্যানগ্রোভ অরণ্য থেকে ঐতিহ্যবাহী মৌয়ালদের মাধ্যমে টেকসই উপায়ে সংগ্রহ করা হয়। এতে মধুর স্বাভাবিক এনজাইম, পরাগরেণু এবং ভেষজ গুণাবলি শতভাগ অক্ষুণ্ণ থাকে। একই সাথে আমরা স্থানীয় মৌয়ালদের ন্যায্য মজুরি প্রদান নিশ্চিত করি।
          </p>
<div class="grid grid-cols-2 gap-4 w-full mb-8">
<div class="p-4 rounded-xl bg-surface-container-low">
<span class="font-headline-md text-headline-md text-primary font-bold block">০%</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">কৃত্রিম প্রসেসিং বা চিনি</span>
</div>
<div class="p-4 rounded-xl bg-surface-container-low">
<span class="font-headline-md text-headline-md text-primary font-bold block">১০০%</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">সক্রিয় প্রাকৃতিক পরাগ</span>
</div>
</div>
<div class="flex items-center justify-between w-full pt-4">
<a class="px-7 py-3 rounded-full bg-on-surface text-surface hover:bg-primary transition-colors font-label-md text-label-md" href="#about">
              আমাদের সম্পর্কে জানুন
            </a>
<div class="text-right">
<span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest block">মধুবন কালেকশন</span>
<span class="font-label-md text-label-md text-on-surface font-bold">স্থাপিত ২০২৩ • বাংলাদেশ</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- 6. Special Promotional Banner -->
<section class="w-full py-16 bg-surface-container-high">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="relative rounded-3xl overflow-hidden bg-surface-container-lowest shadow-md">
<div class="grid grid-cols-1 lg:grid-cols-12 items-center">
<div class="lg:col-span-7 p-8 md:p-14 flex flex-col items-start">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm mb-4">
<span class="material-symbols-outlined text-[16px]">alarm</span>
<span>সীমিত সময়ের স্পেশাল অফার | ফ্রি ডেলিভারি</span>
</div>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-4 leading-tight">
              প্রিয়জনের জন্য প্রাকৃতিক <br/>উপহারের আয়োজন
            </h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-lg mb-8 leading-relaxed">
              আমাদের বিশেষ হানি ও নাটস কম্বো কালেকশন — বিয়ে, উৎসব কিংবা প্রিয়জনের সুস্থতায় দিন স্বাস্থ্যকর ও রুচিশীল উপহার। প্রতিটি বাক্সে থাকছে প্রিমিয়াম ফিনিশ।
            </p>
<div class="flex flex-wrap items-center gap-4">
<a class="px-8 py-3.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md shadow-sm" href="#combo-collection">
                কম্বো কালেকশন দেখুন
              </a>
<span class="font-label-md text-label-md text-primary font-bold">৳১০০০+ অর্ডারে ডেলিভারি ফ্রি</span>
</div>
</div>
<div class="lg:col-span-5 relative h-72 lg:h-full min-h-[380px]">
<img class="w-full h-full object-cover" alt="Handcrafted artisanal pine wood gift hamper box featuring bottles of wildflower honey, brass honey spoon, roasted almonds, and sealed cashew jars surrounded by golden dried flowers and burlap ribbon" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA0jaCoYXnkQ4I85p5p1W3ZmZ61etSNWS1E_02Uw0xLQAyC2R6Z15R8vbUqt1OyIlMxLlNqgnbST8K4VP1n-ta_XeuPs8q4N2ZASmA4o_2nEiuzGoAzN-isNZujLN5xndMUNvsnFN_7DbnU01a6D5aUsvlg4Pk_clP3Z41n2RL70c5cd4VQKjMEwDbned6lTxX7MwRpuDSdpQVzS9xZi8_bpTcScoHbwwluGpdx-hK-"/>
</div>
</div>
</div>
</div>
</section>
<!-- 7. Curated Tabs Section -->
<section class="w-full py-20 bg-surface">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
<div>
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">বিশেষ বাছাই</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface">আর্টিসানাল কালেকশন</h2>
</div>
<!-- Interactive Tabs -->
<div class="flex items-center gap-2 bg-surface-container p-1 rounded-full self-start md:self-auto">
<button class="tab-btn px-5 py-2 rounded-full font-label-md text-label-md bg-primary-container text-on-primary-container transition-all" data-tab="best-seller" type="button">
            বেস্ট সেলার
          </button>
<button class="tab-btn px-5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-tab="new-arrivals" type="button">
            নতুন এসেছে
          </button>
<button class="tab-btn px-5 py-2 rounded-full font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-all" data-tab="special-offers" type="button">
            বিশেষ অফার
          </button>
</div>
</div>
<!-- Tab Content Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Tab Item 1 -->
<div class="group rounded-2xl bg-surface-container-low p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container-high mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Golden light litchi flower natural raw honey jar surrounded by blooming fresh litchi orchard foliage in natural outdoor setting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJiEWpdwtewQDbquX8E30bjkeWRTaVPn2HzDVGZTViAv2OiK-okTGoeqEGGNfjXI5wrUkQmFt6qg_Menjq9sjUQ9q4h17CzNfEMxSrjzgBbp9yL8XxsXwW4wWP2p6oER1E1wNeA3_RpO9z6ulyvJtu2h77MbyNx7sowoBw5GRiQiA2iV4SPejAc6j8B6luKmJNH3DTsSVMQvscxWTLmMvO63Lqj4JCJmgI1PYrCwWc"/>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div>
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৮</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(৭৮)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">৫০০ গ্রাম কাচের জার</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3">লিচু ফুলের খাঁটি মধু</h3>
<div class="flex items-center justify-between pt-2">
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৬২০</span>
<button class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center hover:bg-on-surface hover:text-surface transition-colors">
<span class="material-symbols-outlined text-[18px]">add</span>
</button>
</div>
</div>
</div>
<!-- Tab Item 2 -->
<div class="group rounded-2xl bg-surface-container-low p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container-high mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Whole premium jumbo organic walnuts in raw shells and cracked walnut kernels arranged on handcrafted artisan earthenware pottery" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCmp_TvsZzaOMOZrSIMG71fAczzzZstYc_QCUPoyardfsXJBfymCWfB4nIBJtHkliPikHr1gpO31dRrMTvJdNzGKhcA79SO6aAvtWJ_qHf_iZIp0z25StOjMqYTofbgrkfTuIcC8cWg3EKDQW_qGp_-fi2RbLwOa0SXWRXtjzImJH-Ys4HjQBvusIHnla3Srs_bFIEuRRcWFz1mCvdgjBmjzpmAe90xmM0B-tXHNvlh"/>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div>
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৯</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(১১২)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">২৫০ গ্রাম প্রিমিয়াম প্যাক</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3">প্রিমিয়াম আস্ত আখরোট</h3>
<div class="flex items-center justify-between pt-2">
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৫৫০</span>
<button class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center hover:bg-on-surface hover:text-surface transition-colors">
<span class="material-symbols-outlined text-[18px]">add</span>
</button>
</div>
</div>
</div>
<!-- Tab Item 3 -->
<div class="group rounded-2xl bg-surface-container-low p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container-high mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Curated bundle pack featuring 500g Sundarban wild raw honey jar paired with 250g premium roasted cashews wrapped with natural twine string" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDvHCXoywx48lg_pEGPNP3MtjUE9pAIosfGSlVF2C3jz8puIpIndsNEARjfng9fq4hp12e850CCkEIF4I-g-LDmlGsGi9YmandogreVYIOoaeJdSHpMwVIYRUYItar4AjQ3LPLciuIXOv0z3-ukXrneqnwn7Dcuq13Xgiy9RLeAJZFvdkS_BcqTHHKOgZNNIc2mNNsaMllx5oaq758YT3bau4J8grRvlFdSsRv4Rbt1"/>
<div class="absolute top-2.5 left-2.5">
<span class="px-2.5 py-0.5 rounded-full bg-secondary-container text-on-secondary-fixed font-label-sm text-label-sm">বিশেষ ছাড়</span>
</div>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div>
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৫.০</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(৬৩)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">খলিশা মধু ৫০০g + কাজু ২৫০g</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3">র স্পেশাল কম্বো প্যাক</h3>
<div class="flex items-center justify-between pt-2">
<div>
<span class="font-body-sm text-body-sm text-on-surface-variant line-through block">৳১,৩০০</span>
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳১,১৫০</span>
</div>
<button class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center hover:bg-on-surface hover:text-surface transition-colors">
<span class="material-symbols-outlined text-[18px]">add</span>
</button>
</div>
</div>
</div>
<!-- Tab Item 4 -->
<div class="group rounded-2xl bg-surface-container-low p-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
<div class="relative w-full aspect-square rounded-xl overflow-hidden bg-surface-container-high mb-4">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" alt="Gourmet five seeds mix containing pumpkin seeds, sunflower seeds, chia seeds, flax seeds, and melon seeds placed in a minimalist clear jar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDNGbPlv8hOQzseLFaWTHn2rgJuzry_RKeShic8mJk2UPaT86wlosXrxPqOyQw1VkgzcbvHjVI0PLytS8iiV1VSig1_JW7vYQ5qM_zhQgeNkderKrORjTZY-vByyI5hGI8z5l3EymFAp6JpK9-odnxp2E_s2INrHMV54Oj16VapXnb64vwjK6jVNkJi6Qd6WpzipbKk9eUTAMcxYRG1Xp7M-gpixTmvfj3-rlHvcLNs"/>
<button class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-center text-on-surface-variant hover:text-error transition-colors">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
</div>
<div>
<div class="flex items-center gap-1.5 text-primary mb-1">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-sm text-label-sm font-bold text-on-surface">৪.৭</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">(৮৯)</span>
</div>
<span class="font-label-sm text-label-sm text-secondary mb-1">৩০০ গ্রাম রোস্টেড মিক্স</span>
<h3 class="font-title-md text-title-md text-on-surface mb-3">প্রকৃতির পঞ্চরত্ন সিডস মিক্স</h3>
<div class="flex items-center justify-between pt-2">
<span class="font-title-lg text-title-lg text-on-surface font-bold">৳৪২০</span>
<button class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center hover:bg-on-surface hover:text-surface transition-colors">
<span class="material-symbols-outlined text-[18px]">add</span>
</button>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- 8. Quality & Purity Section -->
<section class="w-full py-20 bg-surface-container">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="text-center max-w-2xl mx-auto mb-16">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">বিশুদ্ধতার প্রতিশ্রুতি</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-3">আমরা যেভাবে গুণমান নিশ্চিত করি</h2>
<p class="font-body-md text-body-md text-on-surface-variant">কোনো আপস ছাড়া প্রতিটি ফোঁটায় আন্তর্জাতিক মানের বিশুদ্ধতা নিশ্চিত করার প্রক্রিয়া</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
<div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col justify-between">
<div>
<div class="w-12 h-12 rounded-xl bg-primary-fixed text-on-primary-fixed flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[26px]">handshake</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">সরাসরি মৌয়াল ও বাগান</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">কোনো মধ্যস্বত্বভোগী ছাড়া সততার সাথে সংগৃহীত, যা নিশ্চিত করে সেরা তাজা কাঁচামাল।</p>
</div>
<span class="font-label-sm text-label-sm text-primary font-bold mt-6 block">ধাপ ০১</span>
</div>
<div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col justify-between">
<div>
<div class="w-12 h-12 rounded-xl bg-primary-fixed text-on-primary-fixed flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[26px]">water_drop</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">আদ্রতা ও পোলেন টেস্ট</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">আন্তর্জাতিক মানদণ্ড অনুযায়ী ১৯% এর নিচে আদ্রতা ও সক্রিয় পোলেন উপস্থিতি পরীক্ষা।</p>
</div>
<span class="font-label-sm text-label-sm text-primary font-bold mt-6 block">ধাপ ০২</span>
</div>
<div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col justify-between">
<div>
<div class="w-12 h-12 rounded-xl bg-primary-fixed text-on-primary-fixed flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[26px]">thermostat</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">এইচএমএফ ও পিএইচ পরীক্ষণ</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">কোনো ধরনের ক্ষতিকর কৃত্রিম উত্তাপ ছাড়া স্বাভাবিক তাপমাত্রায় বোতলজাতকরণ।</p>
</div>
<span class="font-label-sm text-label-sm text-primary font-bold mt-6 block">ধাপ ০৩</span>
</div>
<div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col justify-between">
<div>
<div class="w-12 h-12 rounded-xl bg-primary-fixed text-on-primary-fixed flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[26px]">sanitizer</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">ফুড গ্রেড কাচের জার</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">প্লাস্টিকের রাসায়নিক ক্ষতিকর প্রভাবমুক্ত নিরাপদ এবং শতভাগ হাইজিন প্যাকেজিং।</p>
</div>
<span class="font-label-sm text-label-sm text-primary font-bold mt-6 block">ধাপ ০৪</span>
</div>
</div>
<!-- Lab QR Callout -->
<div class="p-6 rounded-2xl bg-surface-container-lowest shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 max-w-4xl mx-auto">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full bg-secondary-container text-on-secondary-fixed flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[24px]">qr_code_scanner</span>
</div>
<div>
<h4 class="font-title-md text-title-md text-on-surface font-bold">ল্যাব টেস্ট রিপোর্ট যাচাই</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">ব্যাচ টেস্ট রিপোর্ট সরাসরি দেখতে আপনার জারের লেবেলের QR কোড স্ক্যান করুন</p>
</div>
</div>
<a class="px-6 py-2.5 rounded-full bg-surface-container text-on-surface hover:bg-primary-container hover:text-on-primary-container transition-colors font-label-md text-label-md shrink-0" href="#lab-test">
          নমুনা রিপোর্ট দেখুন
        </a>
</div>
</div>
</section>
<!-- 9. Customer Testimonials Section -->
<section class="w-full py-20 bg-surface">
<div class="max-w-7xl mx-auto px-6 lg:px-12">
<div class="text-center max-w-2xl mx-auto mb-16">
<span class="font-label-sm text-label-sm text-primary uppercase tracking-wider block mb-2">বিশ্বাস ও ভালোবাসা</span>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-3">আমাদের গ্রাহকদের অভিজ্ঞতা</h2>
<p class="font-body-md text-body-md text-on-surface-variant">সারা দেশে হাজারো পরিবারের আস্থা ও সন্তুষ্টির গল্প</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Review 1 -->
<div class="p-8 rounded-2xl bg-surface-container-low shadow-sm flex flex-col justify-between">
<div>
<div class="flex items-center gap-1 text-primary-container mb-4">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-md text-body-md text-on-surface mb-6 leading-relaxed italic">
              "খলিশা মধুর ঘ্রাণ আর স্বাদ এক কথায় অসাধারণ! আগে অনেক ব্র্যান্ড ট্রাই করেছি, কিন্তু মধুবনের মতো খাঁটি স্বাদ পাইনি। প্যাকিংও খুব প্রিমিয়াম ছিল।"
            </p>
</div>
<div class="flex items-center justify-between pt-4 border-t-0">
<div>
<span class="font-title-md text-title-md text-on-surface font-bold block">তানভীর আহমেদ</span>
<span class="font-body-sm text-body-sm text-on-surface-variant block">ধানমন্ডি, ঢাকা</span>
</div>
<span class="px-2.5 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm">✓ ভেরিফাইড</span>
</div>
</div>
<!-- Review 2 -->
<div class="p-8 rounded-2xl bg-surface-container-low shadow-sm flex flex-col justify-between">
<div>
<div class="flex items-center gap-1 text-primary-container mb-4">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-md text-body-md text-on-surface mb-6 leading-relaxed italic">
              "আমার পরিবারের জন্য নিয়মিত কাঠবাদাম ও সরিষা মধু নিচ্ছি। কাজুবাদামের সাইজ বেশ বড় এবং রোস্টিং পারফেক্ট ক্রাঞ্চি। ক্যাশ অন ডেলিভারি সার্ভিস খুব দ্রুত।"
            </p>
</div>
<div class="flex items-center justify-between pt-4 border-t-0">
<div>
<span class="font-title-md text-title-md text-on-surface font-bold block">ড. নাজমুল হুদা</span>
<span class="font-body-sm text-body-sm text-on-surface-variant block">পাঁচলাইশ, চট্টগ্রাম</span>
</div>
<span class="px-2.5 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm">✓ ভেরিফাইড</span>
</div>
</div>
<!-- Review 3 -->
<div class="p-8 rounded-2xl bg-surface-container-low shadow-sm flex flex-col justify-between">
<div>
<div class="flex items-center gap-1 text-primary-container mb-4">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-md text-body-md text-on-surface mb-6 leading-relaxed italic">
              "গিফট বক্সটি আমার বাবাকে উপহার দিয়েছিলাম। উডেন বক্সের ফিনিশিং এবং মধুর কোয়ালিটি দেখে সবাই খুব খুশি হয়েছে। ধন্যবাদ মধুবন টিমকে!"
            </p>
</div>
<div class="flex items-center justify-between pt-4 border-t-0">
<div>
<span class="font-title-md text-title-md text-on-surface font-bold block">ফারিহা রহমান</span>
<span class="font-body-sm text-body-sm text-on-surface-variant block">উপশহর, সিলেট</span>
</div>
<span class="px-2.5 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm">✓ ভেরিফাইড</span>
</div>
</div>
</div>
</div>
</section>
<!-- 10. Newsletter & Community Section -->
<section class="w-full py-16 bg-surface-container-high">
<div class="max-w-4xl mx-auto px-6 text-center">
<div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center mx-auto mb-6">
<span class="material-symbols-outlined text-[26px]">mail</span>
</div>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-3">
        প্রাকৃতিক খাবারের নতুন আপডেট পেতে যুক্ত থাকুন
      </h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-xl mx-auto mb-8 leading-relaxed">
        নতুন ব্যাচের মধু সংগ্রহ ও বিশেষ ডিসকাউন্ট সবার আগে জানতে সাবস্ক্রাইব করুন। প্রথম অর্ডারে পাবেন ১০% ছাড়!
      </p>
<form class="flex flex-col sm:flex-row items-center gap-3 max-w-lg mx-auto" id="newsletter-form" onsubmit="event.preventDefault(); document.getElementById('newsletter-success').classList.remove('hidden'); this.reset();">
<input class="w-full px-5 py-3.5 rounded-full bg-surface-container-lowest text-on-surface placeholder:text-on-surface-variant/70 font-body-sm text-body-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-container" placeholder="আপনার ইমেইল অ্যাড্রেস লিখুন..." required="" type="email"/>
<button class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-primary-container text-on-primary-container hover:bg-on-surface hover:text-surface transition-colors font-label-md text-label-md whitespace-nowrap shadow-sm" type="submit">
          সাবস্ক্রাইব করুন
        </button>
</form>
<div class="hidden mt-4 text-primary font-label-md text-label-md flex items-center justify-center gap-1.5" id="newsletter-success">
<span class="material-symbols-outlined text-[18px]">check_circle</span>
<span>ধন্যবাদ! আপনার সাবস্ক্রিপশন সম্পন্ন হয়েছে। কোড: MADHUBAN10</span>
</div>
</div>
</section>
</div>
<script>
  // Interactive Tab Toggle
  document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-primary-container', 'text-on-primary-container');
        btn.classList.add('text-on-surface-variant');
      });
      button.classList.add('bg-primary-container', 'text-on-primary-container');
      button.classList.remove('text-on-surface-variant');
    });
  });
</script></main><footer class="w-full bg-surface-container-low pt-16 pb-8 shadow-[0_-1px_12px_rgba(0,0,0,0.03)]"><div class="max-w-7xl mx-auto px-6 lg:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12"><div><div class="flex items-center gap-3 mb-4"><img alt="Madhuban Artisanal Logo" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFIFNGzXweVjOqxHaPw7vl4mRqYapFexb7sTEax30b6KddO6J55BazqkZwtgLDTkmQEFTrk1YSc60_IRHqZUmc9KpdGBJCl2y-uKh1sPuQypVOcHyEJ09Mc362OEEMkF5VpdNL3y0EjdiCOikFdMQDA5BPL3Qr0W1RXvUNakkvmyukpng3n5U1bhFQpGi6Nt_VC35KHVWXv6LK3Wdh1h_SPqqd1pOMT1tXLI8F5AVv"/><div class="flex flex-col"><span class="font-headline-sm text-headline-sm text-primary leading-tight font-bold">মধুবন</span><span class="font-label-sm text-label-sm text-secondary">প্রাকৃতিক মধু ও বাদাম</span></div></div><p class="font-body-sm text-body-sm text-on-surface-variant mb-6 leading-relaxed">মধুবন বাংলাদেশের প্রত্যন্ত অঞ্চল ও সুন্দরবনের মৌয়ালদের থেকে সরাসরি সংগৃহীত খাঁটি মধু ও প্রিমিয়াম গ্রেডের কাজু, কাঠবাদাম ও আখরোট সরবরাহ করে।</p><div class="flex items-center gap-2"><a aria-label="Facebook" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">public</span></a><a aria-label="Instagram" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">photo_camera</span></a><a aria-label="YouTube" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">play_circle</span></a><a aria-label="WhatsApp" class="w-9 h-9 rounded-full bg-surface-container-high text-on-surface flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors" href="#"><span class="material-symbols-outlined text-[18px]">chat</span></a></div></div><div><h3 class="font-title-md text-title-md text-on-surface mb-4 font-bold">প্রয়োজনীয় লিংক</h3><ul class="space-y-2.5 font-body-sm text-body-sm"><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">হোম</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">সব পণ্য</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">আমাদের গল্প</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">যোগাযোগ</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">ব্লগ ও পুষ্টি বার্তা</a></li></ul></div><div><h3 class="font-title-md text-title-md text-on-surface mb-4 font-bold">কাস্টমার সাপোর্ট</h3><ul class="space-y-2.5 font-body-sm text-body-sm"><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">সচরাচর জিজ্ঞাসা (FAQ)</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">ডেলিভারি ও চার্জ</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">রিটার্ন ও রিফান্ড পলিসি</a></li><li class="flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[14px]">arrow_forward_ios</span><a class="text-on-surface-variant hover:text-primary transition-colors" href="#">টার্মস ও প্রাইভেসি পলিসি</a></li></ul></div><div><h3 class="font-title-md text-title-md text-on-surface mb-4 font-bold">যোগাযোগ ও ঠিকানা</h3><ul class="space-y-3 font-body-sm text-body-sm text-on-surface-variant"><li class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">call</span><div><span class="block font-bold text-on-surface">হটলাইন:</span><span>+880 1712-345678</span></div></li><li class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">mail</span><div><span class="block font-bold text-on-surface">ইমেইল:</span><span>support@madhuban.com.bd</span></div></li><li class="flex items-start gap-2.5"><span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">location_on</span><div><span class="block font-bold text-on-surface">ঠিকানা:</span><span>বাড়ি #২৪, রোড #১০, বনানী, ঢাকা-১২১৩, বাংলাদেশ</span></div></li></ul></div></div><div class="max-w-7xl mx-auto px-6 lg:px-12 pt-6 border-t border-outline-variant/30 flex flex-col md:flex-row items-center justify-between gap-4"><p class="font-body-sm text-body-sm text-on-surface-variant text-center md:text-left">Copyright © 2025 মধুবন (Madhuban Artisan Provisions). সর্বস্বত্ব সংরক্ষিত.</p><div class="flex flex-wrap items-center justify-center gap-2 font-label-sm text-label-sm text-on-surface-variant"><span class="px-2 py-1 bg-surface-container rounded">SSLCommerz</span><span class="px-2 py-1 bg-surface-container rounded font-bold text-error">bKash</span><span class="px-2 py-1 bg-surface-container rounded font-bold text-primary">Nagad</span><span class="px-2 py-1 bg-surface-container rounded">Rocket</span><span class="px-2 py-1 bg-surface-container rounded font-bold">VISA</span><span class="px-2 py-1 bg-surface-container rounded font-bold">Mastercard</span></div></div></footer></div>

<!-- ================= MOBILE (below lg) — ported verbatim from home_mobile_code.html ================= -->
<div class="lg:hidden">
<header class="fixed top-0 w-full z-50 pt-safe bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]"><div class="bg-primary text-on-primary text-center py-1 px-4 text-label-sm font-label-sm tracking-wider flex items-center justify-center gap-1.5"><span class="material-symbols-outlined text-[14px]">local_shipping</span><span>বিশুদ্ধ মধু ও বাদাম | ১০০০ টাকার অর্ডারে ফ্রি ডেলিভারি</span></div><div class="h-28 px-margin-mobile flex flex-col justify-center gap-2"><div class="flex items-center justify-between gap-2"><button aria-label="Menu" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface hover:bg-surface-variant/40 transition-colors"><span class="material-symbols-outlined text-[24px]">menu</span></button><div class="flex items-center gap-2"><img alt="Madhuban Artisanal Logo" class="h-8 w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFIFNGzXweVjOqxHaPw7vl4mRqYapFexb7sTEax30b6KddO6J55BazqkZwtgLDTkmQEFTrk1YSc60_IRHqZUmc9KpdGBJCl2y-uKh1sPuQypVOcHyEJ09Mc362OEEMkF5VpdNL3y0EjdiCOikFdMQDA5BPL3Qr0W1RXvUNakkvmyukpng3n5U1bhFQpGi6Nt_VC35KHVWXv6LK3Wdh1h_SPqqd1pOMT1tXLI8F5AVv"/><span class="font-headline-sm text-headline-sm text-primary font-bold tracking-tight">মধুবন</span><span class="hidden">Home</span></div><div class="flex items-center gap-1"><button aria-label="উইশলিস্ট" class="w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined text-[22px]">favorite</span></button><button aria-label="কার্ট" class="relative w-11 h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined text-[22px]">shopping_bag</span><span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] bg-primary-container text-on-primary text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow-sm">৩</span></button><div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center ml-1"><span class="material-symbols-outlined text-on-primary text-[18px]">person</span></div></div></div><div class="w-full"><div class="relative flex items-center"><span class="absolute left-3.5 material-symbols-outlined text-[18px] text-outline pointer-events-none">search</span><input class="w-full h-10 pl-10 pr-4 bg-surface-container-lowest rounded-full font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-1 focus:ring-primary shadow-[0_2px_12px_rgba(56,39,25,0.04)]" placeholder="আপনি কী খুঁজছেন?..." type="search"/></div></div></div></header><main class="flex-1 flex flex-col relative w-full pt-36 pb-28 bg-surface"><div class="flex flex-col w-full">
<!-- 1. Mobile Hero -->
<section class="px-margin-mobile pt-space-xs pb-space-lg flex flex-col gap-4">
<!-- Golden Origin Pill -->
<div class="self-start inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-label-sm font-label-sm shadow-sm">
<span class="w-1.5 h-1.5 rounded-full bg-primary-container animate-pulse"></span>
<span>PURE • NATURAL • AUTHENTIC</span>
</div>
<!-- Editorial Headline & Bengali Copy -->
<div class="flex flex-col gap-2">
<h1 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight font-bold tracking-tight">
        প্রকৃতির বিশুদ্ধতা,<br/>
<span class="text-primary-container italic font-normal">প্রতিটি ফোঁটায়</span>
</h1>
<p class="font-body-md text-body-md text-on-surface-variant max-w-[340px]">
        সুন্দরবন ও বিশ্বস্ত উৎস থেকে সংগৃহীত ১০০% খাঁটি প্রাকৃতিক মধু ও প্রিমিয়াম বাদাম।
      </p>
</div>
<!-- Hero Image Showcase -->
<div class="relative w-full rounded-2xl overflow-hidden shadow-[0_8px_24px_rgba(56,39,25,0.08)] bg-surface-container-low aspect-[4/3] flex items-end">
<img class="absolute inset-0 w-full h-full object-cover" alt="Artisanal raw honey jar with glistening golden honey, handcrafted wooden dipper dripping fresh amber honey onto raw honeycomb, surrounded by natural roasted almonds and whole cashews on a warm rustic linen surface, soft sunlight gastronomic editorial photography." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuOLyOvv0ETRkfI9F8DslYjebQjC2HU4eVI2z-rU7kcUIDUfXu2GIZh4ayLAUE_qxnm4S-527v103OZD79yiwmy4w195_rMZJhclN0OgKeowwsPROB55NDOKVxdY5ytucb3mBxAKvgMBNdFSQIrWxeI0akbHc5_qwm-_BNieuQaKRiN32gSjypTisoc8UQtsTfMKuJL2lduIz8PFIKx08tJMim5EBltKoXGoPxa4As"/>
<div class="absolute inset-0 bg-gradient-to-t from-inverse-surface/80 via-inverse-surface/20 to-transparent"></div>
<!-- Overlaid Freshness Metric Tag -->
<div class="relative z-10 p-3.5 m-3 rounded-xl bg-surface/90 backdrop-blur-md flex items-center justify-between w-[calc(100%-1.5rem)] shadow-sm">
<div class="flex items-center gap-2.5">
<div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed">
<span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">eco</span>
</div>
<div>
<p class="font-title-md text-[13px] leading-tight text-on-surface font-bold">তাজা নিষ্কাশিত ব্যাচ</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">আদ্রতা মাত্রা: ১৮% (প্রিমিয়াম)</p>
</div>
</div>
<span class="px-2 py-1 bg-surface-container-high rounded-full font-label-sm text-[10px] text-primary font-bold">নতুন ব্যাচ</span>
</div>
</div>
<!-- Action Buttons -->
<div class="flex flex-col gap-2.5 pt-1">
<a class="w-full h-12 rounded-full bg-gradient-to-r from-primary-container to-primary flex items-center justify-center gap-2 text-on-primary font-label-md text-label-md shadow-[0_4px_16px_rgba(217,154,39,0.3)] active:scale-[0.98] transition-transform" href="#shop-grid">
<span>এখনই কিনুন</span>
<span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
<a class="w-full h-11 rounded-full bg-surface-container-lowest text-on-surface flex items-center justify-center gap-1.5 font-label-md text-label-md shadow-sm active:bg-surface-container-low transition-colors" href="#brand-story">
<span class="material-symbols-outlined text-[18px] text-primary">menu_book</span>
<span>আমাদের গল্প</span>
</a>
</div>
<!-- Quick Trust Pills Scrollable/Wrap -->
<div class="flex flex-wrap items-center justify-center gap-1.5 pt-1">
<div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-lowest text-on-surface-variant font-label-sm text-[11px] shadow-sm">
<span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span>১০০% খাঁটি</span>
</div>
<div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-lowest text-on-surface-variant font-label-sm text-[11px] shadow-sm">
<span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">payments</span>
<span>ক্যাশ অন ডেলিভারি</span>
</div>
<div class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-lowest text-on-surface-variant font-label-sm text-[11px] shadow-sm">
<span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings: 'FILL' 1;">science</span>
<span>ল্যাব টেস্টেড</span>
</div>
</div>
</section>
<!-- 2. Horizontal Trust Indicators (2x2 Grid) -->
<section class="px-margin-mobile py-2">
<div class="grid grid-cols-2 gap-2.5">
<div class="p-3 bg-surface-container-lowest rounded-xl flex items-center gap-2.5 shadow-[0_2px_10px_rgba(56,39,25,0.03)]">
<div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[20px]">forest</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">১০০% প্রাকৃতিক</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">সুন্দরবনের ফসল</p>
</div>
</div>
<div class="p-3 bg-surface-container-lowest rounded-xl flex items-center gap-2.5 shadow-[0_2px_10px_rgba(56,39,25,0.03)]">
<div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[20px]">biotech</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">ল্যাব মান</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">নিরাপত্তা ছাড়পত্র</p>
</div>
</div>
<div class="p-3 bg-surface-container-lowest rounded-xl flex items-center gap-2.5 shadow-[0_2px_10px_rgba(56,39,25,0.03)]">
<div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[20px]">sanitizer</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">ফুড-গ্রেড জার</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">বায়ুরোধী কাচ</p>
</div>
</div>
<div class="p-3 bg-surface-container-lowest rounded-xl flex items-center gap-2.5 shadow-[0_2px_10px_rgba(56,39,25,0.03)]">
<div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[20px]">local_shipping</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">দ্রুত ডেলিভারি</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">৬৪ জেলায় পৌঁছাই</p>
</div>
</div>
</div>
</section>
<!-- 3. Category Section -->
<section class="px-margin-mobile pt-6 pb-2">
<div class="flex items-center justify-between mb-3.5">
<div>
<span class="font-label-sm text-[11px] text-primary uppercase font-bold tracking-wider">সংগ্রহশালা</span>
<h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">আমাদের পণ্যসমূহ</h2>
</div>
<a class="font-label-md text-label-md text-primary flex items-center gap-0.5 hover:underline" href="#">
<span>সব দেখুন</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
</a>
</div>
<div class="grid grid-cols-2 gap-3">
<!-- Cat 1 -->
<a class="group relative rounded-xl overflow-hidden aspect-[4/5] bg-surface-container-low flex items-end p-3 shadow-sm" href="#">
<img class="absolute inset-0 w-full h-full object-cover group-active:scale-105 transition-transform duration-300" alt="Artisanal raw honey jar with golden nectar dripping over natural rustic honeycomb background warm sunlit photography" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCVx5egOXwB_GJHfJJzcSV26z76pGR1D-mhox4-flwBQnXnBcOrTowNsMQ6O1Pf_wwg2eI5sAX9kaV9LyhFQl2TYYgk9j04mNTrIUXX4C0TUytcnXsg83ZJOMLFxg0oe-1YRWoGLUjJvd-jaIJqdzpZ_n1VqGIWlPMl-W6oQTls2OySQXRjhlZb6kOfdIIUZyhPSJ4WD4LUDWiTDq2DeH8B2QHnIRP3X79-YacRThhx"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent"></div>
<div class="relative z-10 w-full">
<span class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed text-[10px] font-bold uppercase inline-block mb-1">১২+ আইটেম</span>
<p class="font-headline-sm text-[16px] leading-tight text-surface-container-lowest font-bold">খাঁটি মধু</p>
<p class="font-body-sm text-[11px] text-surface-variant">বন ও ফুলের প্রাকৃতিক রস</p>
</div>
</a>
<!-- Cat 2 -->
<a class="group relative rounded-xl overflow-hidden aspect-[4/5] bg-surface-container-low flex items-end p-3 shadow-sm" href="#">
<img class="absolute inset-0 w-full h-full object-cover group-active:scale-105 transition-transform duration-300" alt="Top view of premium roasted almonds, walnuts, and cashews served in a handcrafted clay bowl with warm ambient lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFpuPnPFmXFIArwlLKs47AmrOGVnGq-0DU0lg7XHIhIECrK2GtcWdzJD6ZP7_DcnczRvEQOPY0FF-muXiWf4lCfsYIjX_efXb-1b69N2OzEEs_6hNDThfOaCYrWDuwNLIE4yzCHrhbkdsQYlavIdI5obKes1gHEEQGYRLWC_RYJgt6C1biPXEloszpQv4KaU7OtThH6_WxK6FF48kLxQXS9ygxBS3Qm1Z5qelkpqzi"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent"></div>
<div class="relative z-10 w-full">
<span class="px-2 py-0.5 rounded-full bg-primary-fixed text-on-primary-fixed text-[10px] font-bold uppercase inline-block mb-1">৮+ আইটেম</span>
<p class="font-headline-sm text-[16px] leading-tight text-surface-container-lowest font-bold">প্রিমিয়াম বাদাম</p>
<p class="font-body-sm text-[11px] text-surface-variant">মুচমুচে ও পুষ্টিকর</p>
</div>
</a>
<!-- Cat 3 -->
<a class="group relative rounded-xl overflow-hidden aspect-[4/5] bg-surface-container-low flex items-end p-3 shadow-sm" href="#">
<img class="absolute inset-0 w-full h-full object-cover group-active:scale-105 transition-transform duration-300" alt="Golden honey jar paired with roasted nuts and organic ghee in an authentic artisanal gift presentation" src="https://lh3.googleusercontent.com/aida-public/AB6AXuACCzRHQxfSvzUEdqAiHzhwWoFj9iZhZDQx-BK3PCu2Y3HKRyzkomk2I5dwf6PHNBoYw_LdUnhexFRjBicYAA6rNDmHXbByJ8UYwGpEf5XwtCFtvc4rwme5g0S2bp02OMMUJ4crIXEBEzNI_gT_SK9mRLAJ7eyYbsjqF5If6ZioK8BhuJLayx7g02dohrMZtu9cqh00Ve_aH4ePmzGkMXlEID2rzBYiPjNDS4iQmmn0"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent"></div>
<div class="relative z-10 w-full">
<span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[10px] font-bold uppercase inline-block mb-1">সাশ্রয়ী অফার</span>
<p class="font-headline-sm text-[16px] leading-tight text-surface-container-lowest font-bold">হানি কম্বো</p>
<p class="font-body-sm text-[11px] text-surface-variant">মধু ও বাদামের মেলবন্ধন</p>
</div>
</a>
<!-- Cat 4 -->
<a class="group relative rounded-xl overflow-hidden aspect-[4/5] bg-surface-container-low flex items-end p-3 shadow-sm" href="#">
<img class="absolute inset-0 w-full h-full object-cover group-active:scale-105 transition-transform duration-300" alt="Elegant luxury wooden hamper packed with assorted dry fruits and artisanal glass honey jars with gold accents" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCmzSNqnM9Y95TNK8KME6kuBHN9rk9x1E_dbrfV5y0rIaU3vkMdOclfN5vphwu4O79ndWnwbb5ji_ihWIWu5pIBM93WR3zkOVXHPGIzmcsQlDWEDOppLVgRZeUuu59Kq-suYr4G2j01tPWgwjDgz8ZP8RItUo6VqHTsp6dRTXPg1ggyt5h7iCQYtp9CBimjQIvckMrKydr0F8UpVLkKyj4RuChVTm0W2twoA68mgIO3"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/90 via-on-surface/30 to-transparent"></div>
<div class="relative z-10 w-full">
<span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[10px] font-bold uppercase inline-block mb-1">উৎসব স্পেশাল</span>
<p class="font-headline-sm text-[16px] leading-tight text-surface-container-lowest font-bold">গিফট বক্স</p>
<p class="font-body-sm text-[11px] text-surface-variant">অভিজাত উপহার প্যাক</p>
</div>
</a>
</div>
</section>
<!-- 4. Featured Products (2-column mobile grid) -->
<section class="px-margin-mobile pt-7 pb-3" id="shop-grid">
<div class="flex items-center justify-between mb-3.5">
<div>
<span class="font-label-sm text-[11px] text-primary uppercase font-bold tracking-wider">সেরা পছন্দ</span>
<h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">জনপ্রিয় পণ্য</h2>
</div>
<div class="flex items-center gap-1">
<span class="px-2 py-1 rounded-full bg-surface-container-high text-on-surface text-[11px] font-bold font-label-sm">৪টি পণ্য</span>
</div>
</div>
<div class="grid grid-cols-2 gap-3">
<!-- Product Card 1 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col justify-between shadow-[0_4px_16px_rgba(56,39,25,0.04)]">
<div class="relative aspect-square bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover" alt="Glass jar of Sundarban Khalisha flower raw wild honey with golden hues placed on clean rustic neutral studio backdrop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAUsGJJfxzhztI21HAi7SWysovunQ65Zg0YNqJ_eE08aJ3iTBBfSqpCSkmosbGXC1rvrC0tkI-Q_SPVjybq4qWWKJ2Oo9jJuKE6CqhR36y5q9ZVAbYS5CkT_fMeeRf77zKTkY8pUv69pZDjxLeyg11oGxHvexD1KBXkYxKW_UhySczDGeix_CKTn4NrH14OIlLfsh2wceLdhpfuFiX61w9EQJC8HWBuaIqzN3gbqIJG"/>
<span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-error text-on-error font-label-sm text-[10px] font-bold shadow-sm">-১২%</span>
<button aria-label="উইশলিস্টে যোগ করুন" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-surface/80 backdrop-blur-sm flex items-center justify-center text-on-surface-variant active:text-error transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
<div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-surface/90 backdrop-blur-sm flex items-center gap-1 text-[11px] font-bold text-on-surface shadow-xs">
<span class="material-symbols-outlined text-[13px] text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
<span>৪.৮</span>
</div>
</div>
<div class="p-2.5 flex flex-col gap-1.5 flex-1 justify-between">
<div>
<div class="flex items-center justify-between">
<span class="px-1.5 py-0.5 rounded bg-surface-container font-label-sm text-[10px] text-secondary font-semibold">৫০০ গ্রাম</span>
<span class="text-[10px] text-primary font-bold">সুন্দরবন</span>
</div>
<h3 class="font-title-md text-[14px] leading-tight text-on-surface font-bold mt-1 line-clamp-1">সুন্দরবনের খলিশা মধু</h3>
</div>
<div class="flex items-baseline gap-1.5 pt-0.5">
<span class="font-title-lg text-[16px] text-primary font-bold">৳৭৫০</span>
<span class="font-body-sm text-[12px] text-outline line-through">৳৮৫০</span>
</div>
<button class="w-full h-9 rounded-lg bg-surface-container-high hover:bg-primary-container hover:text-on-primary text-on-surface font-label-sm text-[12px] font-bold flex items-center justify-center gap-1 active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
<span>ব্যাগে যোগ করুন</span>
</button>
</div>
</div>
<!-- Product Card 2 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col justify-between shadow-[0_4px_16px_rgba(56,39,25,0.04)]">
<div class="relative aspect-square bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover" alt="Fresh natural mustard flower honey in premium glass jar with thick creamy texture and yellow floral undertones" src="https://lh3.googleusercontent.com/aida-public/AB6AXuATx0Q4eJ0x8gr4N6UvGqNfyxT7I5hQoALZSAyv38obIV-Tt7oC2oeezUti8haK89uTblAp4pKTMS_SLLuBe9w1th-qkJRXDXdp3HuONN3AOKR9JKr1YTlnGekTmxmOEVHSYiUX0Dc2AOAKQq5RBukL2SGe3HwiLQki8b-YJ8a4f7-oazVs0XYtr8usLJHWETnVoqR4IOFFt1Zz8_VE1T8z9FDhAov6QPTL5EW9TW4X"/>
<span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-error text-on-error font-label-sm text-[10px] font-bold shadow-sm">-১১%</span>
<button aria-label="উইশলিস্টে যোগ করুন" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-surface/80 backdrop-blur-sm flex items-center justify-center text-on-surface-variant active:text-error transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
<div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-surface/90 backdrop-blur-sm flex items-center gap-1 text-[11px] font-bold text-on-surface shadow-xs">
<span class="material-symbols-outlined text-[13px] text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
<span>৪.৭</span>
</div>
</div>
<div class="p-2.5 flex flex-col gap-1.5 flex-1 justify-between">
<div>
<div class="flex items-center justify-between">
<span class="px-1.5 py-0.5 rounded bg-surface-container font-label-sm text-[10px] text-secondary font-semibold">৫০০ গ্রাম</span>
<span class="text-[10px] text-primary font-bold">সিরাজগঞ্জ</span>
</div>
<h3 class="font-title-md text-[14px] leading-tight text-on-surface font-bold mt-1 line-clamp-1">সরিষা ফুলের মধু</h3>
</div>
<div class="flex items-baseline gap-1.5 pt-0.5">
<span class="font-title-lg text-[16px] text-primary font-bold">৳৫৮০</span>
<span class="font-body-sm text-[12px] text-outline line-through">৳৬৫০</span>
</div>
<button class="w-full h-9 rounded-lg bg-surface-container-high hover:bg-primary-container hover:text-on-primary text-on-surface font-label-sm text-[12px] font-bold flex items-center justify-center gap-1 active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
<span>ব্যাগে যোগ করুন</span>
</button>
</div>
</div>
<!-- Product Card 3 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col justify-between shadow-[0_4px_16px_rgba(56,39,25,0.04)]">
<div class="relative aspect-square bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover" alt="Crispy golden roasted whole cashew nuts in a boutique craft pouch on warm wooden texture with subtle herbs" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC5MLkwWYil6Z9FIYH0K5bCMtIN-cMJ48aGWcD2vMLAlxGUh_lNUXb4pgDWwMmBoMvjrgCm9-xw-xUn1Nq7FzOWDBqxg3ReLgLO-eIgI18BTjbs0azlGjWdtAegxUM2TYgZcEjxWQgNgwyAehC1iYdHDD1-isT4j0xDgs17RaDDYTKCiasRSQxU7ASSbRfQOp0kr-Nua9-VmVYfNU0HfjoHLJAnKeAT0UUcUZUr0ITr"/>
<button aria-label="উইশলিস্টে যোগ করুন" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-surface/80 backdrop-blur-sm flex items-center justify-center text-on-surface-variant active:text-error transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
<div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-surface/90 backdrop-blur-sm flex items-center gap-1 text-[11px] font-bold text-on-surface shadow-xs">
<span class="material-symbols-outlined text-[13px] text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
<span>৪.৯</span>
</div>
</div>
<div class="p-2.5 flex flex-col gap-1.5 flex-1 justify-between">
<div>
<div class="flex items-center justify-between">
<span class="px-1.5 py-0.5 rounded bg-surface-container font-label-sm text-[10px] text-secondary font-semibold">২৫০ গ্রাম</span>
<span class="text-[10px] text-primary font-bold">আমদানি</span>
</div>
<h3 class="font-title-md text-[14px] leading-tight text-on-surface font-bold mt-1 line-clamp-1">প্রিমিয়াম কাজুবাদাম</h3>
</div>
<div class="flex items-baseline gap-1.5 pt-0.5">
<span class="font-title-lg text-[16px] text-primary font-bold">৳৪৫০</span>
</div>
<button class="w-full h-9 rounded-lg bg-surface-container-high hover:bg-primary-container hover:text-on-primary text-on-surface font-label-sm text-[12px] font-bold flex items-center justify-center gap-1 active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
<span>ব্যাগে যোগ করুন</span>
</button>
</div>
</div>
<!-- Product Card 4 -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col justify-between shadow-[0_4px_16px_rgba(56,39,25,0.04)]">
<div class="relative aspect-square bg-surface-container-low overflow-hidden">
<img class="w-full h-full object-cover" alt="Crunchy raw Californian whole almonds displayed in an artisanal earthen dish on soft linen background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAyisaiNzCDtIWB7i8bgwCKqWuOGy7rvvrW-52WtUtBrOz4ZRqnC_2YrODeo64CsWYYA1I4IfFcYRvIAIlHra12bgZj0BdiKIgJIXCJzRqamvxAGADDxJcKFPCGZknU1onl9DWPUjVUQvU_ZGfRhwQMtnANmMr_qEmfr5vpBYv6LDOTje63wyAvQ4EKfhUiuVJrJVWa5HD9Dwx7U8ek7lVuIfftH0aprNwJwueu_7dE"/>
<button aria-label="উইশলিস্টে যোগ করুন" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-surface/80 backdrop-blur-sm flex items-center justify-center text-on-surface-variant active:text-error transition-colors shadow-sm">
<span class="material-symbols-outlined text-[18px]">favorite</span>
</button>
<div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-surface/90 backdrop-blur-sm flex items-center gap-1 text-[11px] font-bold text-on-surface shadow-xs">
<span class="material-symbols-outlined text-[13px] text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
<span>৪.৮</span>
</div>
</div>
<div class="p-2.5 flex flex-col gap-1.5 flex-1 justify-between">
<div>
<div class="flex items-center justify-between">
<span class="px-1.5 py-0.5 rounded bg-surface-container font-label-sm text-[10px] text-secondary font-semibold">২৫০ গ্রাম</span>
<span class="text-[10px] text-primary font-bold">ক্যালিফোর্নিয়া</span>
</div>
<h3 class="font-title-md text-[14px] leading-tight text-on-surface font-bold mt-1 line-clamp-1">কাঠবাদাম ফ্রেশ</h3>
</div>
<div class="flex items-baseline gap-1.5 pt-0.5">
<span class="font-title-lg text-[16px] text-primary font-bold">৳৩৮০</span>
</div>
<button class="w-full h-9 rounded-lg bg-surface-container-high hover:bg-primary-container hover:text-on-primary text-on-surface font-label-sm text-[12px] font-bold flex items-center justify-center gap-1 active:scale-[0.98] transition-all">
<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
<span>ব্যাগে যোগ করুন</span>
</button>
</div>
</div>
</div>
</section>
<!-- 5. Brand Story Card -->
<section class="px-margin-mobile pt-5 pb-3" id="brand-story">
<div class="relative rounded-2xl overflow-hidden bg-on-surface text-surface p-5 shadow-[0_8px_30px_rgba(31,27,21,0.12)]">
<img class="absolute inset-0 w-full h-full object-cover opacity-35" alt="Traditional Bengali honey hunters (mouwal) in Sundarban mangrove forest harvesting wild golden honeycomb naturally from deep trees at dawn" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkTdCSYtnBYKg6Ulb5FxiHlQHni9ATzsgstHXAeszWi5pV2JQ_U_MLGO4XdNWyMfkWPoXGV3tZvIiNmJ3S0Hfwi4bms7N-iBBvHVLfT4Fp9VtJ0Vw6rxbyFjlrWu6GYpH4dfWB-UrViwDvBAjCPZKKxhgU9R_v0XgnckDx1T4c0Pr06fkNRJTZXM3_vFXe6YqTXhHGW0S7eOjQlQtzZ-L8t1-2FLFuQqMKFU-N9akS"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-surface via-on-surface/80 to-transparent"></div>
<div class="relative z-10 flex flex-col gap-3">
<span class="self-start px-2.5 py-0.5 rounded-full bg-primary-container text-on-primary font-label-sm text-[10px] uppercase font-bold">মৌয়ালদের গল্প</span>
<h2 class="font-headline-sm text-headline-sm text-surface font-bold">প্রকৃতির কাছ থেকে আপনার পরিবারে</h2>
<p class="font-body-sm text-body-sm text-surface-variant">
          সুন্দরবনের গভীর ম্যানগ্রোভ অরণ্য থেকে ঐতিহ্যবাহী মৌয়ালদের মাধ্যমে কোনো ধরনের তাপ বা রাসায়নিক ছাড়া সরাসরি চাক থেকে ছেঁকে নেওয়া হয় মধুবনের মধু।
        </p>
<div class="pt-2 flex items-center gap-3">
<a class="h-10 px-4 rounded-full bg-surface text-on-surface font-label-md text-label-md font-bold flex items-center gap-1.5 active:bg-surface-variant transition-colors" href="#">
<span>আমাদের গল্প পড়ুন</span>
<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
</a>
<span class="font-label-sm text-[11px] text-tertiary-fixed font-semibold">১০০% অপরিশোধিত ও কাঁচা</span>
</div>
</div>
</div>
</section>
<!-- 6. Gift & Combo Banner -->
<section class="px-margin-mobile py-3">
<div class="rounded-2xl bg-gradient-to-br from-secondary-container via-surface-container to-surface-container-high p-4 flex flex-col gap-3 shadow-sm">
<div class="flex items-start justify-between">
<div class="flex flex-col gap-1 max-w-[220px]">
<span class="px-2 py-0.5 rounded bg-primary text-on-primary text-[10px] font-bold self-start">স্পেশাল প্যাকেজ</span>
<h3 class="font-headline-sm text-[18px] text-on-surface font-bold leading-snug">প্রিয়জনের জন্য প্রাকৃতিক উপহারের আয়োজন</h3>
</div>
<div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-[28px]">featured_seasonal_and_gifts</span>
</div>
</div>
<p class="font-body-sm text-[12px] text-on-surface-variant">
        খাঁটি মধু, কাজুবাদাম, কাঠবাদাম ও পেস্তাবাদামের আকর্ষণীয় কম্বো বক্সে উপভোগ করুন বিশেষ ২০% ছাড়।
      </p>
<div class="flex items-center justify-between pt-1">
<a class="h-10 px-5 rounded-full bg-primary text-on-primary font-label-md text-label-md flex items-center gap-1 shadow-sm active:scale-[0.98]" href="#">
<span>উপহার বক্স দেখুন</span>
<span class="material-symbols-outlined text-[16px]">redeem</span>
</a>
<span class="font-title-md text-[13px] text-primary font-bold">৳১২৫০ থেকে শুরু</span>
</div>
</div>
</section>
<!-- 7. Best Sellers & Tabs with Mini Carousel Cards -->
<section class="px-margin-mobile pt-4 pb-3 flex flex-col gap-3">
<div class="flex items-center justify-between">
<h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">এক্সক্লুসিভ কালেকশন</h2>
<span class="font-label-sm text-[11px] text-outline">স্লাইড করুন</span>
</div>
<!-- Segmented Touch Tabs -->
<div class="flex items-center gap-1.5 overflow-x-auto pb-1" style="scrollbar-width: none;">
<button class="tab-btn px-4 py-1.5 rounded-full bg-primary text-on-primary font-label-sm text-label-sm whitespace-nowrap shadow-xs">বেস্ট সেলার</button>
<button class="tab-btn px-4 py-1.5 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm whitespace-nowrap shadow-xs">নতুন এসেছে</button>
<button class="tab-btn px-4 py-1.5 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm whitespace-nowrap shadow-xs">অফার প্যাকেজ</button>
</div>
<!-- Horizontal Carousel Preview -->
<div class="flex gap-3 overflow-x-auto pt-1 pb-2" style="scrollbar-width: none;">
<!-- Slide Item 1 -->
<div class="w-[200px] shrink-0 bg-surface-container-lowest rounded-xl p-3 shadow-[0_2px_12px_rgba(56,39,25,0.04)] flex flex-col gap-2">
<div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-surface-container-low">
<img class="w-full h-full object-cover" alt="Black seed infused pure organic raw honey jar on neutral artisanal counter" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCU6mAZPnIpSBu4ZrmLGL6F3yJfCRlCKk2CdQXKt33Bc2YsAd9cZ9jqRohjFhoxeMcBqlLXujJKzU9HKSPL5nCtPplRdCC-MyVeEbb0Mx7amBCJkhOBlsDrQHVbZZkKLQKR4rA0AxO_PpelCIws8zGwpSjhpNO5t4nJfJV9DVI7pC6w90ZWTl1DkSk_8MV5m-2Qf8cK-fo1OafJsnQO6FFxlC8mbJhNSwA2y9d4Okso"/>
<span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-primary-fixed text-on-primary-fixed text-[9px] font-bold">হট সেলিং</span>
</div>
<div class="flex flex-col">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">কালোজিরা ফুলের মধু</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">৫০০ গ্রাম • প্রিমিয়াম</p>
</div>
<div class="flex items-center justify-between pt-1">
<span class="font-title-md text-[14px] text-primary font-bold">৳৮২০</span>
<button aria-label="কার্টে যোগ করুন" class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
<!-- Slide Item 2 -->
<div class="w-[200px] shrink-0 bg-surface-container-lowest rounded-xl p-3 shadow-[0_2px_12px_rgba(56,39,25,0.04)] flex flex-col gap-2">
<div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-surface-container-low">
<img class="w-full h-full object-cover" alt="Roasted and salted Iranian pistachios shelled and ready to eat in craft ceramic bowl" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzik2ySmqa1xgJLWWDqTfNwYaSxk-OVi8VrDek1svTWehw18UbZIO1DYthTuHWIMkZqxIoZoh5y66kHJnegwgoK1xSwZpLvlLQaxRpD2Ty5HAeH8yVv78Z1G5BelsLMUcnnac3zAE6WhAEMwJxN76HrIkzz1sflrr6XlzY5OO7GoVDDO17xGguD4wjVzK3X-ppglIDG5ZcBLx_PSryg0v7tJ4Z_vmLFCZzdTZcNocC"/>
<span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-secondary-fixed text-on-secondary-fixed text-[9px] font-bold">প্রিমিয়াম</span>
</div>
<div class="flex flex-col">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">ইরানি পেস্তাবাদাম</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">২০০ গ্রাম • রোস্টেড</p>
</div>
<div class="flex items-center justify-between pt-1">
<span class="font-title-md text-[14px] text-primary font-bold">৳৫৬০</span>
<button aria-label="কার্টে যোগ করুন" class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
<!-- Slide Item 3 -->
<div class="w-[200px] shrink-0 bg-surface-container-lowest rounded-xl p-3 shadow-[0_2px_12px_rgba(56,39,25,0.04)] flex flex-col gap-2">
<div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-surface-container-low">
<img class="w-full h-full object-cover" alt="Wild multi-flora litchi honey jar alongside fresh lychee branch in warm rustic illumination" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCId-yPB6ViiXkKcRIcUWrjIIjEt8BOlbx2ftrE9yJFTcwxgVNREiMlEMeduSNME2n4Xst31hMYNRaotWyB04BVRBVPob88zKC38K0510YP7-RJ0eV-FRxfZMby9Q8SelbLHVjGUk92fJuh4aS3AZmNSw5MluIvYtlSrEBPq3f6EZ6ExZxIDmTfWD8dpzJm23C2eyCGY0Nq-SCTloJUxXm0BySlJ-rn2HAbr7LNdIAc"/>
<span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-primary-fixed text-on-primary-fixed text-[9px] font-bold">সীমিত স্টক</span>
</div>
<div class="flex flex-col">
<p class="font-title-md text-[13px] font-bold text-on-surface truncate">লিচু ফুলের খাঁটি মধু</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">৫০০ গ্রাম • প্রাকৃতিক</p>
</div>
<div class="flex items-center justify-between pt-1">
<span class="font-title-md text-[14px] text-primary font-bold">৳৬২০</span>
<button aria-label="কার্টে যোগ করুন" class="w-7 h-7 rounded-full bg-primary-container text-on-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[16px]">add</span>
</button>
</div>
</div>
</div>
</section>
<!-- 8. Quality & Purity Commitment -->
<section class="px-margin-mobile py-4">
<div class="bg-surface-container-lowest rounded-2xl p-4 shadow-[0_2px_14px_rgba(56,39,25,0.04)] flex flex-col gap-3">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[24px]">verified</span>
<h2 class="font-headline-sm text-[18px] text-on-surface font-bold">বিশুদ্ধতার ৪টি মধুবন প্রতিশ্রুতি</h2>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">
        আমরা প্রতিটি ধাপে সর্বোচ্চ কোয়ালিটি নিয়ন্ত্রণ করি যেন আপনার হাতের মধু থাকে পুরোপুরি অক্ষত।
      </p>
<div class="flex flex-col gap-2.5 pt-1">
<!-- Benefit Row 1 -->
<div class="flex items-center gap-3 p-2 rounded-xl bg-surface-container-low">
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface">প্রত্যক্ষ উৎস নির্বাচন</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">কোনো মধ্যস্বত্বভোগী ছাড়া মৌয়ালদের কাছ থেকে সংগ্রহ</p>
</div>
</div>
<!-- Benefit Row 2 -->
<div class="flex items-center gap-3 p-2 rounded-xl bg-surface-container-low">
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface">আদ্রতা টেস্ট সার্টিফাইড</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">আন্তর্জাতিক মানদণ্ডে ২০% এর নিচে আর্দ্রতা নিশ্চিতকরণ</p>
</div>
</div>
<!-- Benefit Row 3 -->
<div class="flex items-center gap-3 p-2 rounded-xl bg-surface-container-low">
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface">হিট-ফ্রি কোল্ড ফিল্ট্রেশন</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">অতিরিক্ত তাপরোধী প্রক্রিয়ায় প্রাকৃতিক এনজাইম রক্ষা</p>
</div>
</div>
<!-- Benefit Row 4 -->
<div class="flex items-center gap-3 p-2 rounded-xl bg-surface-container-low">
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">check</span>
</div>
<div class="min-w-0">
<p class="font-title-md text-[13px] font-bold text-on-surface">ফুড-গ্রেড কাচের জার</p>
<p class="font-body-sm text-[11px] text-on-surface-variant">প্লাস্টিক বর্জন করে মধুর পুষ্টি ও সুবাস সুরক্ষিত রাখা</p>
</div>
</div>
</div>
</div>
</section>
<!-- 9. Customer Testimonials -->
<section class="px-margin-mobile pt-4 pb-2 flex flex-col gap-3">
<div class="flex items-center justify-between">
<div>
<span class="font-label-sm text-[11px] text-primary uppercase font-bold tracking-wider">গ্রাহকের অনুভূতি</span>
<h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">সন্তুষ্ট গ্রাহকদের কথা</h2>
</div>
<div class="flex items-center text-primary-container font-bold text-[13px]">
<span>৪.৯/৫.০</span>
</div>
</div>
<!-- Swipeable Testimonial Cards -->
<div class="flex gap-3 overflow-x-auto pb-2" style="scrollbar-width: none;">
<!-- Review 1 -->
<div class="w-[280px] shrink-0 bg-surface-container-lowest rounded-xl p-3.5 shadow-[0_2px_12px_rgba(56,39,25,0.03)] flex flex-col justify-between gap-3">
<div class="flex flex-col gap-1.5">
<div class="flex text-primary-container">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-sm text-[12px] text-on-surface-variant leading-relaxed">
            "সুন্দরবনের খলিশা মধুর ঘ্রাণ আর স্বাদ সত্যি অসাধারণ! ঢাকায় এত খাঁটি মধু পাব ভাবিনি। দ্রুত ডেলিভারির জন্য ধন্যবাদ।"
          </p>
</div>
<div class="flex items-center justify-between pt-1 border-t border-surface-container-high/40">
<div>
<p class="font-title-md text-[12px] font-bold text-on-surface">আরিফুল ইসলাম</p>
<p class="font-body-sm text-[10px] text-outline">ধানমন্ডি, ঢাকা</p>
</div>
<span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full">
<span class="material-symbols-outlined text-[12px]">verified</span>
<span>ভেরিফাইড ক্রেতা</span>
</span>
</div>
</div>
<!-- Review 2 -->
<div class="w-[280px] shrink-0 bg-surface-container-lowest rounded-xl p-3.5 shadow-[0_2px_12px_rgba(56,39,25,0.03)] flex flex-col justify-between gap-3">
<div class="flex flex-col gap-1.5">
<div class="flex text-primary-container">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="font-body-sm text-[12px] text-on-surface-variant leading-relaxed">
            "কাজুবাদাম আর কাঠবাদাম দুটোই অনেক মুচমুচে ও ফ্রেশ ছিল। কাচের জারের প্যাকিং বেশ চমৎকার আর নিখুঁত।"
          </p>
</div>
<div class="flex items-center justify-between pt-1 border-t border-surface-container-high/40">
<div>
<p class="font-title-md text-[12px] font-bold text-on-surface">নুসরাত জাহান</p>
<p class="font-body-sm text-[10px] text-outline">চট্টগ্রাম সদর</p>
</div>
<span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-primary bg-primary-fixed/50 px-2 py-0.5 rounded-full">
<span class="material-symbols-outlined text-[12px]">verified</span>
<span>ভেরিফাইড ক্রেতা</span>
</span>
</div>
</div>
</div>
</section>
<!-- 10. Newsletter & Promo Card -->
<section class="px-margin-mobile pt-4 pb-2">
<div class="rounded-2xl bg-surface-container p-4.5 flex flex-col gap-3 shadow-[0_4px_16px_rgba(56,39,25,0.04)]">
<div class="flex items-center gap-2.5">
<div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[22px]">mark_email_read</span>
</div>
<div>
<span class="text-[10px] font-bold uppercase text-primary tracking-wider">১০% ছাড়ের সুযোগ</span>
<h3 class="font-headline-sm text-[16px] text-on-surface font-bold leading-tight">মধুবন পরিবারে যুক্ত হোন</h3>
</div>
</div>
<p class="font-body-sm text-[12px] text-on-surface-variant">
        আপনার ইমেইল দিয়ে সাবস্ক্রাইব করুন এবং প্রথম অর্ডারে পেয়ে যান ফ্ল্যাট ১০% ডিসকাউন্ট কুপন।
      </p>
<form class="flex flex-col gap-2 pt-1" onsubmit="event.preventDefault(); alert('ধন্যবাদ! আপনার ডিসকাউন্ট কোড: MADHUBAN10');">
<div class="relative w-full">
<input class="w-full h-11 px-3.5 bg-surface-container-lowest rounded-full font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none shadow-xs" placeholder="আপনার ইমেইল ঠিকানা দিন..." required="" type="email"/>
</div>
<button class="w-full h-11 rounded-full bg-primary text-on-primary font-label-md text-label-md font-bold shadow-sm active:scale-[0.98] transition-transform flex items-center justify-center gap-1.5" type="submit">
<span>ডিসকাউন্ট কোড নিন</span>
<span class="material-symbols-outlined text-[18px]">redeem</span>
</button>
</form>
</div>
</section>
</div>
<script>
  // Simple micro-interactions for tab pills
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-primary', 'text-on-primary');
        b.classList.add('bg-surface-container', 'text-on-surface');
      });
      btn.classList.remove('bg-surface-container', 'text-on-surface');
      btn.classList.add('bg-primary', 'text-on-primary');
    });
  });
</script><footer class="w-full bg-surface-container-low px-margin-mobile pt-space-lg pb-space-lg mt-space-xl"><div class="flex flex-col gap-6"><div class="flex flex-col gap-2"><div class="flex items-center gap-2"><img alt="Madhuban Artisanal Logo" class="h-7 w-auto object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFIFNGzXweVjOqxHaPw7vl4mRqYapFexb7sTEax30b6KddO6J55BazqkZwtgLDTkmQEFTrk1YSc60_IRHqZUmc9KpdGBJCl2y-uKh1sPuQypVOcHyEJ09Mc362OEEMkF5VpdNL3y0EjdiCOikFdMQDA5BPL3Qr0W1RXvUNakkvmyukpng3n5U1bhFQpGi6Nt_VC35KHVWXv6LK3Wdh1h_SPqqd1pOMT1tXLI8F5AVv"/><span class="font-headline-sm text-headline-sm text-primary font-bold">মধুবন</span></div><p class="font-body-sm text-body-sm text-on-surface-variant">সুন্দরবনের খাঁটি মধু ও পাহাড়ি অর্গানিক বাদামের নির্ভরযোগ্য অনলাইন ঠিকানা। শতভাগ বিশুদ্ধতা ও স্বাস্থ্য সুরক্ষার নিশ্চয়তা।</p></div><div class="grid grid-cols-2 gap-3 py-2"><div class="flex items-center gap-2 p-3 bg-surface-container-lowest rounded-lg shadow-[0_2px_8px_rgba(56,39,25,0.03)]"><span class="material-symbols-outlined text-primary text-[22px]">verified_user</span><div><p class="font-title-md text-[13px] leading-tight text-on-surface font-semibold">১০০% খাঁটি পণ্য</p><p class="font-body-sm text-[11px] text-on-surface-variant">ল্যাব টেস্ট সার্টিফাইড</p></div></div><div class="flex items-center gap-2 p-3 bg-surface-container-lowest rounded-lg shadow-[0_2px_8px_rgba(56,39,25,0.03)]"><span class="material-symbols-outlined text-primary text-[22px]">local_shipping</span><div><p class="font-title-md text-[13px] leading-tight text-on-surface font-semibold">দ্রুত ডেলিভারি</p><p class="font-body-sm text-[11px] text-on-surface-variant">সারাদেশে হোম সার্ভিস</p></div></div></div><div class="flex flex-col gap-2.5"><p class="font-label-md text-label-md text-on-surface uppercase font-bold">পেমেন্ট পার্টনার</p><div class="flex flex-wrap items-center gap-2"><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">bKash</span><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">Nagad</span><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">Debit/Credit Card</span><span class="px-3 py-1.5 bg-surface-container-lowest text-on-surface rounded-full text-label-sm font-label-sm shadow-[0_1px_4px_rgba(0,0,0,0.04)]">ক্যাশ অন ডেলিভারি</span></div></div><div class="flex flex-col gap-2 pt-2"><p class="font-label-md text-label-md text-on-surface uppercase font-bold">যোগাযোগ করুন</p><div class="flex gap-3"><a class="flex-1 flex items-center justify-center gap-1.5 h-11 bg-primary text-on-primary rounded-full font-label-md text-label-md" href="tel:+8801700000000"><span class="material-symbols-outlined text-[18px]">call</span><span>কল করুন</span></a><a class="flex-1 flex items-center justify-center gap-1.5 h-11 bg-surface-container-lowest text-on-surface rounded-full font-label-md text-label-md shadow-[0_1px_6px_rgba(0,0,0,0.05)]" href="https://wa.me/"><span class="material-symbols-outlined text-[18px] text-primary">chat</span><span>হোয়াটসঅ্যাপ</span></a></div></div><div class="text-center pt-4"><p class="font-body-sm text-label-sm text-outline">© ২০২৪ মধুবন অর্গানিক ফুডস। সর্বস্বত্ব সংরক্ষিত।</p></div></div></footer></main><nav class="fixed bottom-0 w-full z-50 pb-safe bg-surface/90 backdrop-blur-xl shadow-[0_-2px_12px_rgba(56,39,25,0.06)]" data-active-classes="text-primary font-bold"><div class="flex justify-around items-center h-16 px-gutter-mobile"><a aria-current="page" class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 transition-colors text-primary font-bold" data-path="home" href="#"><span class="material-symbols-outlined text-[22px]">home</span><span class="font-label-sm text-label-sm">হোম</span></a><a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 text-on-surface-variant hover:text-primary transition-colors" data-path="shop" href="#"><span class="material-symbols-outlined text-[22px]">storefront</span><span class="font-label-sm text-label-sm">ক্যাটালগ</span></a><a class="relative flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 text-on-surface-variant hover:text-primary transition-colors" data-path="cart" href="#"><span class="material-symbols-outlined text-[22px]">shopping_bag</span><span class="absolute top-1 right-2 min-w-[15px] h-[15px] bg-primary-container text-on-primary text-[9px] font-bold rounded-full flex items-center justify-center px-0.5">৩</span><span class="font-label-sm text-label-sm">কার্ট</span></a><a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 text-on-surface-variant hover:text-primary transition-colors" data-path="wishlist" href="#"><span class="material-symbols-outlined text-[22px]">favorite</span><span class="font-label-sm text-label-sm">উইশলিস্ট</span></a><a class="flex flex-col items-center justify-center gap-1 min-w-[56px] h-12 text-on-surface-variant hover:text-primary transition-colors" data-path="account" href="#"><span class="material-symbols-outlined text-[22px]">person</span><span class="font-label-sm text-label-sm">প্রোফাইল</span></a></div></nav></div>
@endsection
