{{-- Desktop PDP hero: media hub (gallery + video + harvest metrics) and purchase centre.
     Data: $product (variants, media, content), $rating (ratingSummary), $wishlistIds. --}}
@php
    use App\Modules\Core\Support\Numerals as N;
    $c = $product->content ?? [];
    $gallery = $product->galleryImages();
    $video = $product->videoUrl();
    $activeVariants = $product->variants->where('is_active', true)->values();
    $defaultVariant = $product->defaultVariant();
    $avail = fn ($v) => (int) ($v?->inventoryItem?->available() ?? 0);
    $inStock = $avail($defaultVariant) > 0;
    $pct = fn ($v) => ($v && $v->sale_price && $v->effectivePrice() < $v->price) ? (int) round((1 - $v->effectivePrice() / $v->price) * 100) : 0;
    $fmt = fn ($paisa) => '৳'.number_format($paisa / 100, 0);
    $liked = in_array($product->id, $wishlistIds);
@endphp
<section class="max-w-7xl mx-auto w-full px-gutter-mobile lg:px-margin pb-space-xl">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg lg:gap-space-xl items-start">
<!-- Left: media hub -->
<div class="lg:col-span-7 flex flex-col gap-space-md">
<div class="relative bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm aspect-[4/3] flex items-center justify-center group">
@if ($main = $product->primaryImageUrl())
<img class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" id="main-product-display" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $main }}">
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container text-8xl">🍯</div>
@endif
@if (! empty($c['badge']) || ! empty($c['origin_tag']))
<div class="absolute top-4 left-4 z-10 flex flex-col gap-1.5 items-start">
@if (! empty($c['badge']))
<span class="inline-flex items-center gap-1.5 bg-primary-container text-on-primary-container font-label-sm text-label-sm px-3 py-1 rounded-full shadow-sm backdrop-blur-md">
<span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">eco</span>{{ $c['badge'] }}
</span>
@endif
@if (! empty($c['origin_tag']))
<span class="inline-flex items-center gap-1 bg-surface-container-high/90 text-on-surface font-label-sm text-label-sm px-2.5 py-0.5 rounded-full backdrop-blur-sm">
<span class="material-symbols-outlined text-[14px] text-primary">pin_drop</span>{{ $c['origin_tag'] }}
</span>
@endif
</div>
@endif
<div class="absolute top-4 right-4 z-10 flex items-center gap-2">
<button aria-label="জুম করে দেখুন" title="জুম করে দেখুন" type="button" onclick="pdpZoom()" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-on-surface flex items-center justify-center shadow-md hover:bg-surface-container transition-all hover:scale-110 active:scale-95">
<span class="material-symbols-outlined text-[20px]">zoom_in</span>
</button>
<form action="{{ route('wishlist.toggle', $product) }}" method="POST">
@csrf
<button aria-label="উইশলিস্টে যুক্ত করুন" type="submit" class="w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md flex items-center justify-center shadow-md hover:bg-surface-container transition-all hover:scale-110 active:scale-95 {{ $liked ? 'text-error' : 'text-on-surface' }}">
<span class="material-symbols-outlined text-[20px]" style="{{ $liked ? "font-variation-settings: 'FILL' 1;" : '' }}">favorite</span>
</button>
</form>
</div>
@if (! empty($c['batch']) || ! empty($c['moisture_note']))
<div class="absolute bottom-3 left-3 right-3 py-2 px-3 rounded-lg bg-surface-container-lowest/80 backdrop-blur-md flex items-center justify-between text-body-sm font-body-sm text-on-surface">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-[18px] text-primary">verified_user</span>
@if (! empty($c['batch']))<span class="font-label-sm text-label-sm">ব্যাচ আইডি: {{ $c['batch'] }}</span>@endif
</div>
@if (! empty($c['moisture_note']))<span class="font-label-sm text-label-sm text-primary font-semibold">{{ $c['moisture_note'] }}</span>@endif
</div>
@endif
</div>
<!-- Thumbnails + video -->
@if ($gallery->count() > 1 || $video)
<div class="grid grid-cols-6 gap-space-xs sm:gap-space-sm" id="pdp-thumbnails">
@foreach ($gallery as $media)
<button type="button" aria-label="ছবি {{ $loop->iteration }} দেখুন" data-src="{{ $media->media->url() }}" onclick="pdpSelectThumb(this)" class="thumb-item relative aspect-square rounded-lg overflow-hidden bg-surface-container-high transition-all shadow-sm hover:opacity-100 {{ $loop->first ? 'opacity-100 ring-2 ring-primary' : 'opacity-70' }}">
<img class="w-full h-full object-cover" alt="{{ $product->name['bn'] ?? '' }}" src="{{ $media->media->url() }}"/>
</button>
@endforeach
@if ($video)
<button type="button" aria-label="ভিডিও দেখুন" onclick="pdpPlayVideo()" class="relative aspect-square rounded-lg overflow-hidden bg-surface-container-highest flex flex-col items-center justify-center text-on-surface opacity-90 hover:opacity-100 transition-all group/vid">
<div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shadow-md group-hover/vid:scale-110 transition-transform">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
</div>
<span class="font-label-sm text-[10px] mt-1 text-on-surface-variant font-semibold">ভিডিও দেখুন</span>
</button>
@endif
</div>
@endif
<!-- Harvest metric grid -->
@if (! empty($c['metrics']))
<div class="grid grid-cols-3 gap-space-sm pt-space-xs">
@foreach ($c['metrics'] as $m)
<div class="bg-surface-container p-3 rounded-lg flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-[28px]">{{ $m['icon'] ?? 'verified' }}</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">{{ $m['label'] }}</span>
<span class="font-title-md text-title-md text-on-surface font-bold">{{ $m['value'] }}</span>
</div>
</div>
@endforeach
</div>
@endif
</div>

<!-- Right: purchase centre -->
<div class="lg:col-span-5 flex flex-col gap-space-md">
<div class="flex items-center justify-between">
@if ($product->category)
<span class="inline-flex items-center gap-1 font-label-md text-label-md text-primary bg-primary-fixed px-3 py-0.5 rounded-full font-bold">
<span class="material-symbols-outlined text-[14px]">stars</span>{{ $c['grade'] ?? ($product->category->name['bn'] ?? $product->category->name['en'] ?? '') }}
</span>
@endif
@if ($defaultVariant?->sku)
<span class="font-label-sm text-label-sm text-on-surface-variant tracking-wider">SKU: <strong class="text-on-surface font-semibold" id="product-sku">{{ $defaultVariant->sku }}</strong></span>
@endif
</div>
<div class="flex flex-col gap-1">
<h1 class="font-headline-lg text-headline-lg text-on-surface font-semibold tracking-tight">{{ $product->name['bn'] ?? '' }}</h1>
@if ($product->name['en'] ?? null)
<p class="font-title-lg text-title-lg text-secondary italic font-headline-sm">{{ $c['subtitle'] ?? $product->name['en'] }}</p>
@endif
</div>
<!-- Rating + stock -->
<div class="flex flex-wrap items-center justify-between gap-space-xs py-space-xs">
<div class="flex items-center gap-2">
@if ($rating['count'] > 0)
<div class="flex items-center text-primary-container">
@for ($i = 1; $i <= 5; $i++)
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">{{ $rating['avg'] >= $i ? 'star' : ($rating['avg'] >= $i - 0.5 ? 'star_half' : 'star') }}</span>
@endfor
</div>
<span class="font-label-md text-label-md text-on-surface font-bold">{{ N::bn($rating['avg']) }}</span>
<span class="text-outline-variant">|</span>
<a class="font-label-sm text-label-sm text-primary hover:underline underline-offset-4" href="#customer-reviews">{{ N::bn($rating['count']) }}টি ভেরিফায়েড রিভিউ</a>
@else
<a class="font-label-sm text-label-sm text-primary hover:underline underline-offset-4" href="#customer-reviews">এখনো কোনো রিভিউ নেই</a>
@endif
</div>
<div id="stock-pill" class="inline-flex items-center gap-1.5 {{ $inStock ? 'bg-secondary-fixed/70 text-on-secondary-fixed' : 'bg-surface-dim text-on-surface-variant' }} px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold">
<span class="w-2 h-2 rounded-full {{ $inStock ? 'bg-primary animate-pulse' : 'bg-outline' }}"></span>
<span id="stock-text">{{ $inStock ? ($avail($defaultVariant) <= 10 ? 'ইন স্টক — দ্রুত শেষ হচ্ছে' : 'ইন স্টক') : 'স্টক শেষ' }}</span>
</div>
</div>
<!-- Price -->
<div class="bg-surface-container-low p-space-md rounded-xl flex items-baseline justify-between shadow-sm">
<div class="flex items-baseline gap-space-sm">
<span class="font-display-hero text-headline-lg text-primary font-bold" id="display-price">{{ $fmt($defaultVariant?->effectivePrice() ?? 0) }}</span>
<span class="font-title-lg text-title-lg text-on-surface-variant line-through {{ $pct($defaultVariant) ? '' : 'hidden' }}" id="display-original-price">{{ $fmt($defaultVariant?->price ?? 0) }}</span>
<span class="bg-error-container text-on-error-container font-label-sm text-label-sm px-2 py-0.5 rounded-full font-bold {{ $pct($defaultVariant) ? '' : 'hidden' }}" id="display-discount">{{ N::bn($pct($defaultVariant)) }}% ছাড়</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">ভ্যাট অন্তর্ভুক্ত</span>
</div>
@if ($desc = ($product->description['bn'] ?? $product->short_desc['bn'] ?? null))
<p class="font-body-md text-body-md text-on-surface-variant">{{ $desc }}</p>
@endif
<form action="{{ route('cart.items.store') }}" method="POST" class="flex flex-col gap-space-md" id="pdp-form">
@csrf
@if ($activeVariants->count() > 1)
<div class="flex flex-col gap-space-xs pt-space-xs">
<label class="font-title-md text-title-md text-on-surface font-semibold">ওজন নির্বাচন করুন (Select Weight):</label>
<div class="grid grid-cols-2 gap-space-xs sm:gap-space-sm" id="variant-group">
@foreach ($activeVariants as $variant)
@php($ok = $avail($variant) > 0)
@php($tag = $variant->options['tag'] ?? null)
<label class="variant-btn group text-left p-3 rounded-xl transition-all flex flex-col justify-between shadow-sm {{ $ok ? 'bg-surface-container-lowest hover:bg-surface-container cursor-pointer has-[:checked]:bg-primary-container/20 has-[:checked]:ring-1 has-[:checked]:ring-primary-container' : 'opacity-50 cursor-not-allowed bg-surface-dim shadow-none' }}">
<input type="radio" name="variant_id" value="{{ $variant->id }}" class="hidden pdp-variant-radio"
  data-price="{{ $variant->effectivePrice() }}" data-original="{{ $variant->price }}" data-discount="{{ $pct($variant) }}"
  data-sku="{{ $variant->sku }}" data-stock="{{ $avail($variant) }}" {{ $variant->id === $defaultVariant?->id ? 'checked' : '' }} {{ $ok ? '' : 'disabled' }}>
<div class="flex items-center justify-between w-full">
<span class="font-label-md text-label-md font-bold {{ $ok ? 'text-on-surface' : 'text-on-surface-variant line-through' }}">{{ $variant->weight_grams ? ($variant->weight_grams >= 1000 ? N::bn(rtrim(rtrim(number_format($variant->weight_grams / 1000, 1), '0'), '.')).' কেজি' : N::bn($variant->weight_grams).' গ্রাম') : $variant->sku }}{{ $tag ? ' ('.$tag.')' : '' }}</span>
@if ($ok)
<span class="material-symbols-outlined text-[18px] text-primary opacity-0 group-has-[:checked]:opacity-100" style="font-variation-settings: 'FILL' 1;">check_circle</span>
@else
<span class="font-label-sm text-[10px] bg-outline/20 px-1.5 py-0.5 rounded text-on-surface-variant">স্টক শেষ</span>
@endif
</div>
<span class="font-body-sm text-body-sm mt-1 {{ $ok ? 'text-primary font-bold' : 'text-outline' }}">{{ $fmt($variant->effectivePrice()) }}</span>
</label>
@endforeach
</div>
</div>
@else
<input type="hidden" name="variant_id" value="{{ $defaultVariant?->id }}">
@endif
<div class="flex flex-col gap-space-sm pt-space-xs">
<div class="flex items-center gap-space-sm">
<div class="flex items-center bg-surface-container-lowest rounded-full p-1 shadow-sm">
<button aria-label="পরিমাণ কমান" type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors active:scale-90" onclick="let i=this.nextElementSibling;i.value=Math.max(1,parseInt(i.value)-1)"><span class="material-symbols-outlined text-[20px]">remove</span></button>
<input class="w-12 text-center bg-transparent font-title-lg text-title-lg font-bold text-on-surface outline-none" name="qty" max="10" min="1" type="number" value="1"/>
<button aria-label="পরিমাণ বাড়ান" type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors active:scale-90" onclick="let i=this.previousElementSibling;i.value=Math.min(10,parseInt(i.value)+1)"><span class="material-symbols-outlined text-[20px]">add</span></button>
</div>
<button type="submit" class="flex-1 py-3.5 px-space-md rounded-full bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center gap-2 shadow-md hover:bg-secondary transition-all hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed" {{ $inStock ? '' : 'disabled' }} id="add-to-cart-btn">
<span class="material-symbols-outlined text-[20px]">shopping_bag</span><span>কার্টে যোগ করুন</span>
</button>
<button type="submit" name="buy_now" value="1" class="py-3.5 px-space-lg rounded-full bg-secondary text-on-secondary font-label-md text-label-md flex items-center justify-center gap-2 shadow-md hover:bg-on-background transition-all hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed" {{ $inStock ? '' : 'disabled' }} id="buy-now-btn">
<span>এখনই কিনুন</span>
</button>
</div>
<div class="flex items-center justify-between text-body-sm font-body-sm text-on-surface-variant px-1">
<span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-tertiary">inventory</span>সর্বোচ্চ ১০টি প্রতি অর্ডারে অনুমোদিত</span>
<button type="button" class="hover:text-primary transition-colors flex items-center gap-1 font-label-sm text-label-sm" onclick="pdpShare()"><span class="material-symbols-outlined text-[16px]">share</span>শেয়ার</button>
</div>
</div>
</form>
<!-- Trust quadrant -->
<div class="grid grid-cols-2 gap-space-sm pt-space-sm">
@foreach ([
    ['thermostat', 'bg-tertiary-fixed', 'text-on-tertiary-fixed', 'কোল্ড এক্সট্রাক্টেড', 'তাপহীন প্রাকৃতিক ফিল্টারেশন'],
    ['local_shipping', 'bg-primary-fixed', 'text-on-primary-fixed', 'ক্যাশ অন ডেলিভারি', 'সারা দেশে পণ্য দেখে পেমেন্ট'],
    ['bolt', 'bg-secondary-fixed', 'text-on-secondary-fixed', 'দ্রুততম ডেলিভারি', 'ঢাকায় ২৪-৪৮ ও বাইরে ৭২ ঘণ্টা'],
    ['published_with_changes', 'bg-surface-container-high', 'text-primary', 'নিরাপদ রিটার্ন', '৩ দিনে শতভাগ নিশ্চিত রিফান্ড'],
] as [$icon, $bg, $fg, $t, $s])
<div class="bg-surface-container-lowest p-3 rounded-lg flex items-center gap-2.5 shadow-sm">
<div class="w-8 h-8 rounded-full {{ $bg }} flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-[18px] {{ $fg }}">{{ $icon }}</span></div>
<div class="flex flex-col"><span class="font-label-sm text-label-sm font-semibold text-on-surface">{{ $t }}</span><span class="font-body-sm text-[11px] text-on-surface-variant">{{ $s }}</span></div>
</div>
@endforeach
</div>
</div>
</div>
</section>

<!-- Zoom lightbox + video modal -->
<div id="pdp-lightbox" class="hidden fixed inset-0 z-[100] bg-on-surface/80 backdrop-blur-sm flex items-center justify-center p-6" onclick="this.classList.add('hidden')">
<img id="pdp-lightbox-img" class="max-h-full max-w-full rounded-xl shadow-2xl" alt="">
</div>
@if ($video)
<div id="pdp-video-modal" class="hidden fixed inset-0 z-[100] bg-on-surface/80 backdrop-blur-sm flex items-center justify-center p-6" onclick="if(event.target===this)pdpCloseVideo()">
<div class="relative w-full max-w-3xl">
<button type="button" aria-label="বন্ধ করুন" onclick="pdpCloseVideo()" class="absolute -top-12 right-0 w-10 h-10 rounded-full bg-surface text-on-surface flex items-center justify-center"><span class="material-symbols-outlined">close</span></button>
<video id="pdp-video" class="w-full rounded-xl shadow-2xl bg-black" controls playsinline preload="none" src="{{ $video }}"></video>
</div>
</div>
@endif
<script>
function pdpSelectThumb(btn) {
  var main = document.getElementById('main-product-display');
  if (main) main.src = btn.dataset.src;
  document.querySelectorAll('#pdp-thumbnails .thumb-item').forEach(function (b) { b.classList.remove('opacity-100', 'ring-2', 'ring-primary'); b.classList.add('opacity-70'); });
  btn.classList.remove('opacity-70'); btn.classList.add('opacity-100', 'ring-2', 'ring-primary');
}
function pdpZoom() {
  var main = document.getElementById('main-product-display');
  if (!main) return;
  document.getElementById('pdp-lightbox-img').src = main.src;
  document.getElementById('pdp-lightbox').classList.remove('hidden');
}
function pdpPlayVideo() { var m = document.getElementById('pdp-video-modal'); if (m) { m.classList.remove('hidden'); document.getElementById('pdp-video').play(); } }
function pdpCloseVideo() { var m = document.getElementById('pdp-video-modal'); if (m) { m.classList.add('hidden'); document.getElementById('pdp-video').pause(); } }
function pdpShare() {
  var data = { title: document.title, url: location.href };
  if (navigator.share) { navigator.share(data).catch(function () {}); }
  else if (navigator.clipboard) { navigator.clipboard.writeText(location.href); alert('লিংক কপি হয়েছে'); }
}
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { document.getElementById('pdp-lightbox').classList.add('hidden'); pdpCloseVideo(); } });
var pdpBn = function (s) { return String(s).replace(/\d/g, function (d) { return '০১২৩৪৫৬৭৮৯'[d]; }); };
document.querySelectorAll('.pdp-variant-radio').forEach(function (radio) {
  radio.addEventListener('change', function () {
    var price = Math.round(radio.dataset.price / 100), orig = Math.round(radio.dataset.original / 100), disc = parseInt(radio.dataset.discount || '0', 10);
    document.getElementById('display-price').textContent = '৳' + price.toLocaleString('en-US');
    var o = document.getElementById('display-original-price'), d = document.getElementById('display-discount');
    o.textContent = '৳' + orig.toLocaleString('en-US'); d.textContent = pdpBn(disc) + '% ছাড়';
    o.classList.toggle('hidden', !disc); d.classList.toggle('hidden', !disc);
    var sku = document.getElementById('product-sku'); if (sku) sku.textContent = radio.dataset.sku;
    var stock = parseInt(radio.dataset.stock || '0', 10);
    document.getElementById('stock-text').textContent = stock <= 0 ? 'স্টক শেষ' : (stock <= 10 ? 'ইন স্টক — দ্রুত শেষ হচ্ছে' : 'ইন স্টক');
  });
});
</script>
