@extends('admin.layout')

@section('title', $productId ? 'Edit Product' : 'New Product')

@section('content')
<div class="min-h-full">
    @include('admin.partials.nav')

    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">{{ $productId ? 'Edit Product' : 'New Product' }}</h1>
            <a href="/admin/products" class="text-sm text-gray-500 hover:underline">&larr; Back to products</a>
        </div>

        <form id="product-form" class="mt-6 space-y-6 rounded-lg border border-gray-200 bg-white p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Attribute set</label>
                    <select id="f-attribute-set" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm"></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="f-category" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
                        <option value="">— none —</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name (বাংলা)</label>
                    <input id="f-name-bn" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name (English)</label>
                    <input id="f-name-en" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type</label>
                    <select id="f-type" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
                        <option value="simple">Simple (one variant)</option>
                        <option value="variable">Variable (multiple options, e.g. weight)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="f-status" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm">
                        <option value="draft">Draft</option>
                        <option value="active">Active (visible on storefront)</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-medium text-gray-900">Attributes</h2>
                <p class="text-xs text-gray-500">From the selected attribute set — required ones must be filled before the product can go Active.</p>
                <div id="attribute-fields" class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2"></div>
            </div>

            <p id="form-error" class="hidden rounded-md bg-red-50 px-3 py-2 text-sm text-red-700"></p>

            <div class="flex gap-2 border-t border-gray-100 pt-4">
                <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Save</button>
            </div>
        </form>

        <!-- Variants -->
        <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-medium text-gray-900">Variants</h2>
                @if (!$productId)
                    <p class="text-xs text-gray-500">Save the product first, then add variants.</p>
                @endif
            </div>

            <div id="variant-rows" class="mt-4 space-y-3"></div>

            @if ($productId)
                <button id="add-variant-btn" type="button" class="mt-4 rounded-md border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">+ Add variant</button>
            @endif
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    const productId = @json($productId);
    let attributeSets = [];
    let product = null;

    // One option key=value pair per variant (e.g. weight=500g) — covers the
    // honey/grocery presets; a product needing multiple variant dimensions
    // (e.g. weight x color) would need this editor extended.
    function variantRowHtml(v = {}) {
        const optKey = Object.keys(v.options || {})[0] || '';
        const optVal = v.options?.[optKey] || '';
        return `
            <div class="variant-row grid grid-cols-7 gap-2 rounded-md border border-gray-100 p-3" data-id="${v.id ?? ''}">
                <input placeholder="SKU" value="${v.sku ?? ''}" class="v-sku col-span-2 rounded border border-gray-300 px-2 py-1 text-sm">
                <input placeholder="Option key (weight)" value="${optKey}" class="v-opt-key rounded border border-gray-300 px-2 py-1 text-sm">
                <input placeholder="Value (500g)" value="${optVal}" class="v-opt-val rounded border border-gray-300 px-2 py-1 text-sm">
                <input placeholder="Price (paisa)" type="number" value="${v.price ?? ''}" class="v-price rounded border border-gray-300 px-2 py-1 text-sm">
                <input placeholder="Sale price" type="number" value="${v.sale_price ?? ''}" class="v-sale rounded border border-gray-300 px-2 py-1 text-sm">
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1 text-xs text-gray-600"><input type="checkbox" class="v-default" ${v.is_default ? 'checked' : ''}> default</label>
                    <button type="button" class="v-remove text-red-600 text-xs hover:underline">✕</button>
                </div>
            </div>
        `;
    }

    function readVariantRow(el) {
        const optKey = el.querySelector('.v-opt-key').value.trim();
        const optVal = el.querySelector('.v-opt-val').value.trim();
        return {
            id: el.dataset.id || null,
            sku: el.querySelector('.v-sku').value.trim(),
            options: optKey ? { [optKey]: optVal } : {},
            price: parseInt(el.querySelector('.v-price').value || '0', 10),
            sale_price: el.querySelector('.v-sale').value ? parseInt(el.querySelector('.v-sale').value, 10) : null,
            is_default: el.querySelector('.v-default').checked,
        };
    }

    function renderVariants(variants) {
        document.getElementById('variant-rows').innerHTML = variants.length
            ? variants.map(variantRowHtml).join('')
            : '<p class="text-sm text-gray-400">No variants yet.</p>';
    }

    document.getElementById('variant-rows').addEventListener('click', async (e) => {
        if (!e.target.classList.contains('v-remove')) return;
        const row = e.target.closest('.variant-row');
        const id = row.dataset.id;

        if (id) {
            if (!confirm('Remove this variant?')) return;
            await AdminApi.call(`/products/${productId}/variants/${id}`, { method: 'DELETE' });
            loadProduct();
        } else {
            row.remove();
        }
    });

    document.getElementById('add-variant-btn')?.addEventListener('click', () => {
        document.getElementById('variant-rows').insertAdjacentHTML('beforeend', variantRowHtml());
    });

    // Save any NEW (no data-id) variant rows against the live API as soon as they have a SKU+price.
    document.getElementById('variant-rows')?.addEventListener('focusout', async (e) => {
        const row = e.target.closest('.variant-row');
        if (!row || row.dataset.id || !productId) return;
        const data = readVariantRow(row);
        if (!data.sku || !data.price) return;

        try {
            const res = await AdminApi.call(`/products/${productId}/variants`, { method: 'POST', body: data });
            row.dataset.id = res.data.id;
        } catch (err) {
            // user still typing / duplicate option combo — leave the row as-is, they'll see it fail silently here;
            // a toast would be nicer but this keeps the page dependency-free.
        }
    });

    function renderAttributeFields(attrs, values = {}) {
        document.getElementById('attribute-fields').innerHTML = attrs.filter((a) => !a.is_variant).map((a) => {
            const label = a.label.bn || a.code;
            const val = values[a.code] ?? '';
            const id = `attr-${a.code}`;

            if (a.type === 'boolean') {
                return `<label class="flex items-center gap-2 text-sm text-gray-700"><input type="checkbox" id="${id}" data-code="${a.code}" class="attr-input" ${val ? 'checked' : ''}> ${label}${a.is_required ? ' *' : ''}</label>`;
            }
            if (a.type === 'select') {
                const opts = (a.options || []).map((o) => `<option value="${o}" ${val === o ? 'selected' : ''}>${o}</option>`).join('');
                return `<div><label class="block text-sm text-gray-700">${label}${a.is_required ? ' *' : ''}</label><select id="${id}" data-code="${a.code}" class="attr-input mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm"><option value="">—</option>${opts}</select></div>`;
            }
            return `<div><label class="block text-sm text-gray-700">${label}${a.is_required ? ' *' : ''}</label><input id="${id}" data-code="${a.code}" value="${val}" type="${a.type === 'number' ? 'number' : a.type === 'date' ? 'date' : 'text'}" class="attr-input mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm"></div>`;
        }).join('');
    }

    function readAttributeValues() {
        const values = {};
        document.querySelectorAll('.attr-input').forEach((el) => {
            const v = el.type === 'checkbox' ? el.checked : el.value;
            if (v !== '' && v !== false) values[el.dataset.code] = v;
        });
        return values;
    }

    async function loadAttributeSets() {
        const res = await AdminApi.call('/attribute-sets');
        attributeSets = res.data;
        document.getElementById('f-attribute-set').innerHTML = attributeSets.map((s) => `<option value="${s.id}">${s.name}</option>`).join('');
        document.getElementById('f-attribute-set').addEventListener('change', () => {
            const set = attributeSets.find((s) => s.id == document.getElementById('f-attribute-set').value);
            renderAttributeFields(set?.attributes ?? [], product?.attributes ?? {});
        });
        document.getElementById('f-attribute-set').dispatchEvent(new Event('change'));
    }

    async function loadCategories() {
        const res = await AdminApi.call('/categories?per_page=100');
        document.getElementById('f-category').insertAdjacentHTML('beforeend',
            res.data.map((c) => `<option value="${c.id}">${c.name.bn}</option>`).join(''));
    }

    async function loadProduct() {
        if (!productId) return;
        const res = await AdminApi.call(`/products/${productId}`);
        product = res.data;

        document.getElementById('f-attribute-set').value = product.attribute_set_id;
        document.getElementById('f-category').value = product.category_id ?? '';
        document.getElementById('f-name-bn').value = product.name.bn ?? '';
        document.getElementById('f-name-en').value = product.name.en ?? '';
        document.getElementById('f-type').value = product.type;
        document.getElementById('f-status').value = product.status;

        const set = attributeSets.find((s) => s.id == product.attribute_set_id);
        renderAttributeFields(set?.attributes ?? [], product.attributes ?? {});

        renderVariants(product.variants);
    }

    document.getElementById('product-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const body = {
            attribute_set_id: parseInt(document.getElementById('f-attribute-set').value, 10),
            category_id: document.getElementById('f-category').value || null,
            name: { bn: document.getElementById('f-name-bn').value, en: document.getElementById('f-name-en').value || null },
            type: document.getElementById('f-type').value,
            status: document.getElementById('f-status').value,
            attributes: readAttributeValues(),
        };

        if (!productId) {
            body.variants = [...document.querySelectorAll('.variant-row')].map(readVariantRow);
        }

        try {
            const res = productId
                ? await AdminApi.call(`/products/${productId}`, { method: 'PATCH', body })
                : await AdminApi.call('/products', { method: 'POST', body });

            if (!productId) window.location.href = `/admin/products/${res.data.id}/edit`;
            else { product = res.data; alert('Saved.'); }
        } catch (err) {
            const errorEl = document.getElementById('form-error');
            errorEl.textContent = err.message + (err.fields ? ' ' + JSON.stringify(err.fields) : '');
            errorEl.classList.remove('hidden');
        }
    });

    (async function init() {
        await Promise.all([loadAttributeSets(), loadCategories()]);
        await loadProduct();
    })();
</script>
@endsection
