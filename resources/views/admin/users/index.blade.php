@extends('admin.app')

@section('title', 'Users')

@section('header-actions')
    <button id="new-btn" class="btn-primary">+ New user</button>
@endsection

@section('content')
<form id="user-form" class="mb-6 hidden space-y-4 rounded-lg border border-line bg-white p-5">
    <input type="hidden" id="f-id">
    <h2 id="form-title" class="text-sm font-semibold text-ink">New user</h2>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="field-label">Name</label>
            <input id="f-name" required class="field-input">
        </div>
        <div>
            <label class="field-label">Email</label>
            <input id="f-email" type="email" required class="field-input">
        </div>
        <div>
            <label class="field-label">Phone</label>
            <input id="f-phone" class="field-input">
        </div>
        <div id="f-password-wrap">
            <label class="field-label">Password</label>
            <input id="f-password" type="password" class="field-input">
        </div>
        <div>
            <label class="field-label">Role</label>
            <select id="f-role" required class="field-input"></select>
        </div>
        <label id="f-active-wrap" class="hidden flex items-center gap-2 pt-6 text-sm text-stone-700">
            <input id="f-active" type="checkbox" class="h-4 w-4 rounded border-line text-gold">
            Active
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
                <th class="px-5 py-3 font-medium">Email</th>
                <th class="px-5 py-3 font-medium">Role</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody id="rows"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    let users = [];
    let roleNames = [];

    async function load() {
        const [usersRes, rolesRes] = await Promise.all([
            AdminApi.call('/users?per_page=100'),
            AdminApi.call('/roles'),
        ]);
        users = usersRes.data;
        roleNames = rolesRes.data.roles.map((r) => r.name);

        document.getElementById('f-role').innerHTML = roleNames.map((r) => `<option value="${r}">${r}</option>`).join('');

        document.getElementById('rows').innerHTML = users.map((u) => `
            <tr class="border-t border-line">
                <td class="px-5 py-3">${u.name}</td>
                <td class="px-5 py-3 text-stone-500">${u.email}</td>
                <td class="px-5 py-3">${u.roles[0] ?? '—'}</td>
                <td class="px-5 py-3">${u.is_active ? '<span class="status-pill bg-status-sage-soft text-status-sage">Active</span>' : '<span class="status-pill bg-status-rust-soft text-status-rust">Disabled</span>'}</td>
                <td class="px-5 py-3 text-right">
                    <button data-edit="${u.id}" class="text-gold hover:underline">Edit</button>
                    <button data-delete="${u.id}" class="ml-3 text-status-rust hover:underline">Delete</button>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="5" class="px-5 py-8 text-center text-stone-400">No staff users yet.</td></tr>';
    }

    function showForm(user = null) {
        document.getElementById('user-form').classList.remove('hidden');
        document.getElementById('form-title').textContent = user ? 'Edit user' : 'New user';
        document.getElementById('f-id').value = user?.id ?? '';
        document.getElementById('f-name').value = user?.name ?? '';
        document.getElementById('f-email').value = user?.email ?? '';
        document.getElementById('f-email').disabled = !!user;
        document.getElementById('f-phone').value = user?.phone ?? '';
        document.getElementById('f-role').value = user?.roles?.[0] ?? roleNames[0];
        document.getElementById('f-password-wrap').classList.toggle('hidden', !!user);
        document.getElementById('f-active-wrap').classList.toggle('hidden', !user);
        document.getElementById('f-active').checked = user ? !!user.is_active : true;
        document.getElementById('form-error').classList.add('hidden');
    }

    document.getElementById('new-btn').addEventListener('click', () => showForm());
    document.getElementById('cancel-btn').addEventListener('click', () => document.getElementById('user-form').classList.add('hidden'));

    document.getElementById('rows').addEventListener('click', async (e) => {
        const editId = e.target.dataset.edit;
        const deleteId = e.target.dataset.delete;

        if (editId) showForm(users.find((u) => u.id == editId));

        if (deleteId) {
            if (!confirm('Delete this user?')) return;
            try {
                await AdminApi.call(`/users/${deleteId}`, { method: 'DELETE' });
                load();
            } catch (err) {
                alert(err.message);
            }
        }
    });

    document.getElementById('user-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('f-id').value;

        const body = id
            ? {
                name: document.getElementById('f-name').value,
                phone: document.getElementById('f-phone').value || null,
                role: document.getElementById('f-role').value,
                is_active: document.getElementById('f-active').checked,
            }
            : {
                name: document.getElementById('f-name').value,
                email: document.getElementById('f-email').value,
                phone: document.getElementById('f-phone').value || null,
                password: document.getElementById('f-password').value,
                role: document.getElementById('f-role').value,
            };

        try {
            await AdminApi.call(id ? `/users/${id}` : '/users', { method: id ? 'PATCH' : 'POST', body });
            document.getElementById('user-form').classList.add('hidden');
            load();
        } catch (err) {
            const errorEl = document.getElementById('form-error');
            errorEl.textContent = err.message + (err.fields ? ' ' + Object.values(err.fields).flat().join(' ') : '');
            errorEl.classList.remove('hidden');
        }
    });

    load();
</script>
@endsection
