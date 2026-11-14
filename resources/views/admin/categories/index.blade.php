@extends('admin.layout')

@section('title', 'Categories')

@section('content')
<div class="min-h-full">
    @include('admin.partials.nav')

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Categories</h1>
            <button id="new-btn" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">+ New Category</button>
        </div>

        <!-- Create/edit form, hidden until "New Category" or "Edit" is clicked -->
        <form id="category-form" class="mt-4 hidden space-y-4 rounded-lg border border-gray-200 bg-white p-5">
            <input type="hidden" id="f-id">
            <h2 id="form-title" class="font-medium text-gray-900">New Category</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name (বাংলা)</label>
                    <input id="f-name-bn" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name (English)</label>
                    <input id="f-name-en" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Parent category</label>
                    <select id="f-parent" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500">
                        <option value="">— none (top level) —</option>
                    </select>
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input id="f-active" type="checkbox" checked class="h-4 w-4 rounded border-gray-300">
                    <label for="f-active" class="text-sm text-gray-700">Active (visible on storefront)</label>
                </div>
            </div>

            <p id="form-error" class="hidden rounded-md bg-red-50 px-3 py-2 text-sm text-red-700"></p>

            <div class="flex gap-2">
                <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">Save</button>
                <button type="button" id="cancel-btn" class="rounded-md border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50">Cancel</button>
            </div>
        </form>

        <table class="mt-6 w-full border-separate border-spacing-0 overflow-hidden rounded-lg border border-gray-200 bg-white text-sm">
            <thead class="bg-gray-50 text-left text-gray-500">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Slug</th>
                    <th class="px-4 py-2">Active</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody id="rows"></tbody>
        </table>
    </main>
</div>
@endsection

@section('scripts')
<script>
    let categories = [];

    async function load() {
        const res = await AdminApi.call('/categories?per_page=100');
        categories = res.data;

        const parentSelect = document.getElementById('f-parent');
        parentSelect.innerHTML = '<option value="">— none (top level) —</option>'
            + categories.map((c) => `<option value="${c.id}">${c.name.bn}</option>`).join('');

        document.getElementById('rows').innerHTML = categories.map((c) => `
            <tr class="border-t border-gray-100">
                <td class="px-4 py-2">${c.name.bn}${c.name.en ? ` <span class="text-gray-400">(${c.name.en})</span>` : ''}</td>
                <td class="px-4 py-2 text-gray-500">${c.slug}</td>
                <td class="px-4 py-2">${c.is_active ? '✅' : '—'}</td>
                <td class="px-4 py-2 text-right">
                    <button data-edit="${c.id}" class="text-amber-700 hover:underline">Edit</button>
                    <button data-delete="${c.id}" class="ml-3 text-red-600 hover:underline">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    function showForm(category = null) {
        document.getElementById('category-form').classList.remove('hidden');
        document.getElementById('form-title').textContent = category ? 'Edit Category' : 'New Category';
        document.getElementById('f-id').value = category?.id ?? '';
        document.getElementById('f-name-bn').value = category?.name?.bn ?? '';
        document.getElementById('f-name-en').value = category?.name?.en ?? '';
        document.getElementById('f-parent').value = category?.parent_id ?? '';
        document.getElementById('f-active').checked = category ? !!category.is_active : true;
        document.getElementById('form-error').classList.add('hidden');
    }

    function hideForm() {
        document.getElementById('category-form').classList.add('hidden');
    }

    document.getElementById('new-btn').addEventListener('click', () => showForm());
    document.getElementById('cancel-btn').addEventListener('click', hideForm);

    document.getElementById('rows').addEventListener('click', async (e) => {
        const editId = e.target.dataset.edit;
        const deleteId = e.target.dataset.delete;

        if (editId) showForm(categories.find((c) => c.id == editId));

        if (deleteId) {
            if (!confirm('Delete this category?')) return;
            try {
                await AdminApi.call(`/categories/${deleteId}`, { method: 'DELETE' });
                load();
            } catch (err) {
                alert(err.message);
            }
        }
    });

    document.getElementById('category-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('f-id').value;
        const body = {
            name: { bn: document.getElementById('f-name-bn').value, en: document.getElementById('f-name-en').value || null },
            parent_id: document.getElementById('f-parent').value || null,
            is_active: document.getElementById('f-active').checked,
        };

        try {
            await AdminApi.call(id ? `/categories/${id}` : '/categories', { method: id ? 'PATCH' : 'POST', body });
            hideForm();
            load();
        } catch (err) {
            const errorEl = document.getElementById('form-error');
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        }
    });

    load();
</script>
@endsection
