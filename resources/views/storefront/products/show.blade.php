@extends('storefront.layout')

@section('title', ($product->name['bn'] ?? '') . ' — Bazardev Ecom')
@section('description', $product->short_desc['bn'] ?? '')

@section('content')
@php
    $defaultVariant = $product->defaultVariant();
    $pdpAttributes = $product->attributeSet->attributeDefinitions->where('is_variant', false)->where('show_on_pdp', true);
@endphp

<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <div class="grid grid-cols-1 gap-10 sm:grid-cols-2">
        <!-- Gallery -->
        <div>
            <div class="aspect-square overflow-hidden rounded-xl bg-amber-50">
                @if ($image = $product->primaryImageUrl())
                    <img src="{{ $image }}" alt="{{ $product->name['bn'] ?? '' }}" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full items-center justify-center text-6xl">🍯</div>
                @endif
            </div>
        </div>

        <!-- Info -->
        <div>
            <h1 class="font-serif text-2xl font-bold text-stone-900">{{ $product->name['bn'] ?? '' }}</h1>
            @if ($product->name['en'] ?? null)
                <p class="text-sm text-stone-400">{{ $product->name['en'] }}</p>
            @endif

            @if (($product->attributes['purity_cert'] ?? false))
                <span class="mt-2 inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">✅ ল্যাব টেস্টেড / খাঁটি</span>
            @endif

            <p id="pdp-price" class="mt-4 text-2xl font-semibold text-amber-700">৳{{ number_format(($defaultVariant?->effectivePrice() ?? 0) / 100, 0) }}</p>

            @if ($product->short_desc['bn'] ?? null)
                <p class="mt-3 text-stone-600">{{ $product->short_desc['bn'] }}</p>
            @endif

            <form action="{{ route('cart.items.store') }}" method="POST" class="mt-6">
                @csrf

                @if ($product->variants->count() > 1)
                    <p class="text-sm font-medium text-stone-700">ওজন বাছুন</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($product->variants->where('is_active', true) as $variant)
                            @php($available = $variant->inventoryItem?->available() ?? 0)
                            <label class="variant-pill cursor-pointer rounded-full border border-amber-300 px-4 py-1.5 text-sm {{ $available <= 0 ? 'opacity-40' : '' }}">
                                <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                    data-price="{{ $variant->effectivePrice() }}"
                                    {{ $loop->first ? 'checked' : '' }} {{ $available <= 0 ? 'disabled' : '' }}
                                    class="peer hidden">
                                <span class="peer-checked:font-semibold peer-checked:text-amber-800">{{ $variant->options['weight'] ?? $variant->sku }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <input type="hidden" name="variant_id" value="{{ $defaultVariant?->id }}">
                @endif

                <div class="mt-4 flex items-center gap-3">
                    <input type="number" name="qty" value="1" min="1" max="10" class="w-20 rounded-md border border-stone-300 px-3 py-2 text-sm">
                    <button type="submit" {{ ($defaultVariant?->inventoryItem?->available() ?? 0) <= 0 ? 'disabled' : '' }}
                        class="flex-1 rounded-full bg-amber-700 px-6 py-2.5 font-medium text-white hover:bg-amber-800 disabled:opacity-40">
                        🛒 কার্টে যুক্ত করুন
                    </button>
                </div>
            </form>

            @if ($pdpAttributes->isNotEmpty())
                <table class="mt-8 w-full text-sm">
                    <tbody>
                        @foreach ($pdpAttributes as $attr)
                            @php($value = $product->attributes[$attr->code] ?? null)
                            @if ($value !== null && $value !== '')
                                <tr class="border-b border-stone-100">
                                    <td class="py-2 text-stone-500">{{ $attr->label['bn'] ?? $attr->code }}</td>
                                    <td class="py-2 text-right text-stone-800">{{ is_bool($value) ? ($value ? 'হ্যাঁ' : 'না') : $value }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @if ($related->isNotEmpty())
        <section class="mt-16">
            <h2 class="text-lg font-semibold text-stone-900">আরও দেখুন</h2>
            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
                @foreach ($related as $r)
                    @include('storefront.partials.product-card', ['product' => $r])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Swap the displayed price when a weight/option pill is picked — pure
    // display convenience, the server always recomputes the real price at
    // cart/checkout time (CRT-4), this never feeds back into any total.
    document.querySelectorAll('input[name="variant_id"]').forEach((input) => {
        input.addEventListener('change', () => {
            document.getElementById('pdp-price').textContent = '৳' + Math.round(input.dataset.price / 100).toLocaleString();
        });
    });
</script>
@endsection
