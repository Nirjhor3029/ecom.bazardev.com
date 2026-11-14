@extends('admin.app')

@section('title', 'Categories')

@section('header-actions')
    <button id="new-btn" class="btn-primary">+ New category</button>
@endsection

@section('content')
<!-- Create/edit form, hidden until "New category" or "Edit" is clicked -->
<form id="category-form" class="mb-6 hidden space-y-4 rounded-lg border border-line bg-white p-5">
    <input type="hidden" id="f-id">
    <h2 id="form-title" class="text-sm font-semibold text-ink">New category</h2>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label">Name (বাংলা)</label>
            <input id="f-name-bn" required class="field-input">
        </div>
        <div>
            <label class="field-label">Name (English)</label>
            <input id="f-name-en" class="field-input">
        </div>
        <div>
            <label class="field-label">Parent category</label>
            <select id="f-parent" class="field-input">
                <option value="">— none (top level) —</option>
            </select>
        </div>
        <label class="flex items-center gap-2 pt-6 text-sm text-stone-700">
            <input id="f-active" type="checkbox" checked class="h-4 w-4 rounded border-line text-gold focus:ring-gold/40">
            Active (visible on storefront)
        </label>
    </div>

    <p id="form-error" class="hidden rounded-md bg-status-rust-soft px-3 py-2 text-sm text-status-rust"></p>

    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Save</button>
        <button type="button" id="cancel-btn" class="btn-secondary">Cancel</button>
    </div>
</form>

<div class="overflow-hidden rounded-lg border border-line bg-white">
    <table class="w-full text-sm">
        <thead class="border-b border-line bg-stone-50/60 text-left text-stone-500">
            <tr>
                <th class="px-5 py-3 font-medium">Name</th>
                <th class="px-5 py-3 font-medium">Slug</th>
                <th class="px-5 py-3 font-medium">Active</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody id="rows"></tbody>
    </table>
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
            <tr class="border-b border-line last:border-0">
                <td class="px-5 py-3">${c.name.bn}${c.name.en ? ` <span class="text-stone-400">(${c.name.en})</span>` : ''}</td>
                <td class="px-5 py-3 text-stone-500">${c.slug}</td>
                <td class="px-5 py-3">${c.is_active ? '<span class="status-pill bg-status-sage-soft text-status-sage">Active</span>' : '<span class="status-pill bg-stone-100 text-stone-500">Hidden</span>'}</td>
                <td class="px-5 py-3 text-right">
                    <button data-edit="${c.id}" class="text-gold hover:underline">Edit</button>
                    <button data-delete="${c.id}" class="ml-3 text-status-rust hover:underline">Delete</button>
                </td>
            </tr>
        `).join('') || '<tr><td class="px-5 py-6 text-center text-stone-400" colspan="4">No categories yet.</td></tr>';
    }

    function showForm(category = null) {
        document.getElementById('category-form').classList.remove('hidden');
        document.getElementById('form-title').textContent = category ? 'Edit category' : 'New category';
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
