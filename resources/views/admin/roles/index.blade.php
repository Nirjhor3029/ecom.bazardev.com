@extends('admin.app')

@section('title', 'Roles & permissions')

@section('header-actions')
    <button id="new-role-btn" class="btn-primary">+ New role</button>
@endsection

@section('content')
<p class="mb-4 text-sm text-stone-500">Tick the permissions each role should have, then save that role. Changes apply immediately.</p>
<div id="roles" class="space-y-4"></div>
@endsection

@section('scripts')
<script>
    let permissionGroups = {};

    function permissionLabel(name) {
        return name.split('.').slice(1).join(' ').replace(/_/g, ' ');
    }

    function roleCardHtml(role) {
        const groupsHtml = Object.entries(permissionGroups).map(([group, perms]) => `
            <div>
                <p class="text-xs font-medium capitalize text-stone-500">${group}</p>
                <div class="mt-1.5 grid grid-cols-2 gap-1.5 sm:grid-cols-4">
                    ${perms.map((p) => `
                        <label class="flex items-center gap-1.5 text-sm text-stone-700">
                            <input type="checkbox" class="perm-cb h-3.5 w-3.5 rounded border-line text-gold" value="${p}" ${role.permissions.includes(p) ? 'checked' : ''}>
                            ${permissionLabel(p)}
                        </label>
                    `).join('')}
                </div>
            </div>
        `).join('');

        return `
            <div class="role-card rounded-lg border border-line bg-white p-5" data-role-id="${role.id}" data-role-name="${role.name}">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-medium text-ink">${role.name}</h2>
                        <p class="text-xs text-stone-500">${role.users_count} user(s)</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="save-role-btn btn-primary text-sm">Save</button>
                        ${role.users_count === 0 ? `<button class="delete-role-btn rounded-md border border-status-rust/30 px-3 py-1.5 text-sm text-status-rust hover:bg-status-rust-soft">Delete</button>` : ''}
                    </div>
                </div>
                <div class="mt-4 space-y-3 border-t border-line pt-4">${groupsHtml}</div>
            </div>
        `;
    }

    async function load() {
        const res = await AdminApi.call('/roles');
        permissionGroups = res.data.permissions;
        document.getElementById('roles').innerHTML = res.data.roles.map(roleCardHtml).join('');
    }

    document.getElementById('roles').addEventListener('click', async (e) => {
        const card = e.target.closest('.role-card');
        if (!card) return;
        const roleId = card.dataset.roleId;

        if (e.target.classList.contains('save-role-btn')) {
            const permissions = [...card.querySelectorAll('.perm-cb:checked')].map((cb) => cb.value);
            try {
                await AdminApi.call(`/roles/${roleId}`, { method: 'PATCH', body: { permissions } });
                e.target.textContent = 'Saved!';
                setTimeout(() => { e.target.textContent = 'Save'; }, 1200);
            } catch (err) {
                alert(err.message);
            }
        }

        if (e.target.classList.contains('delete-role-btn')) {
            if (!confirm(`Delete the "${card.dataset.roleName}" role?`)) return;
            try {
                await AdminApi.call(`/roles/${roleId}`, { method: 'DELETE' });
                load();
            } catch (err) {
                alert(err.message);
            }
        }
    });

    document.getElementById('new-role-btn').addEventListener('click', async () => {
        const name = prompt('New role name:');
        if (!name) return;
        try {
            await AdminApi.call('/roles', { method: 'POST', body: { name } });
            load();
        } catch (err) {
            alert(err.message);
        }
    });

    load();
</script>
@endsection
