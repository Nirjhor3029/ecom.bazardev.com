@extends('admin.app')

@section('title', 'Your profile')

@section('content')
<div class="max-w-xl space-y-6">
    <div class="rounded-lg border border-line bg-white p-6">
        <h2 class="text-sm font-semibold text-ink">Photo</h2>
        <div class="mt-3 flex items-center gap-4">
            <span id="p-avatar" class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full bg-gold-soft text-xl font-semibold text-gold">?</span>
            <label class="btn-secondary cursor-pointer text-sm">
                Change photo
                <input id="avatar-input" type="file" accept="image/*" class="hidden">
            </label>
        </div>
    </div>

    <form id="profile-form" class="space-y-4 rounded-lg border border-line bg-white p-6">
        <h2 class="text-sm font-semibold text-ink">Details</h2>

        <div>
            <label class="field-label">Name</label>
            <input id="p-name" required class="field-input">
        </div>
        <div>
            <label class="field-label">Email</label>
            <input id="p-email" disabled class="field-input bg-stone-50 text-stone-500">
        </div>
        <div>
            <label class="field-label">Contact number</label>
            <input id="p-phone" placeholder="01XXXXXXXXX" class="field-input">
        </div>

        <p id="profile-error" class="hidden rounded-md bg-status-rust-soft px-3 py-2 text-sm text-status-rust"></p>
        <p id="profile-success" class="hidden rounded-md bg-status-sage-soft px-3 py-2 text-sm text-status-sage"></p>

        <button type="submit" class="btn-primary">Save changes</button>
    </form>

    <form id="password-form" class="space-y-4 rounded-lg border border-line bg-white p-6">
        <h2 class="text-sm font-semibold text-ink">Change password</h2>

        <div>
            <label class="field-label">Current password</label>
            <input id="p-current-password" type="password" class="field-input">
        </div>
        <div>
            <label class="field-label">New password</label>
            <input id="p-password" type="password" class="field-input">
        </div>
        <div>
            <label class="field-label">Confirm new password</label>
            <input id="p-password-confirm" type="password" class="field-input">
        </div>

        <p id="password-error" class="hidden rounded-md bg-status-rust-soft px-3 py-2 text-sm text-status-rust"></p>

        <button type="submit" class="btn-secondary">Update password</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function renderAvatar(user) {
        const el = document.getElementById('p-avatar');
        el.innerHTML = user.avatar_url
            ? `<img src="${user.avatar_url}" class="h-full w-full object-cover">`
            : (user.name || '?').charAt(0).toUpperCase();
    }

    async function load() {
        const res = await AdminApi.call('/auth/me');
        const user = res.data;
        document.getElementById('p-name').value = user.name ?? '';
        document.getElementById('p-email').value = user.email ?? '';
        document.getElementById('p-phone').value = user.phone ?? '';
        renderAvatar(user);
    }

    document.getElementById('avatar-input').addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('file', file);
        try {
            const res = await AdminApi.upload('/auth/profile/avatar', formData);
            renderAvatar(res.data);
        } catch (err) {
            alert(err.message);
        }
    });

    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const errorEl = document.getElementById('profile-error');
        const successEl = document.getElementById('profile-success');
        errorEl.classList.add('hidden');
        successEl.classList.add('hidden');

        try {
            await AdminApi.call('/auth/profile', {
                method: 'PATCH',
                body: { name: document.getElementById('p-name').value, phone: document.getElementById('p-phone').value || null },
            });
            successEl.textContent = 'Saved.';
            successEl.classList.remove('hidden');
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        }
    });

    document.getElementById('password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const errorEl = document.getElementById('password-error');
        errorEl.classList.add('hidden');

        try {
            await AdminApi.call('/auth/profile', {
                method: 'PATCH',
                body: {
                    current_password: document.getElementById('p-current-password').value,
                    password: document.getElementById('p-password').value,
                    password_confirmation: document.getElementById('p-password-confirm').value,
                },
            });
            document.getElementById('password-form').reset();
            alert('Password updated.');
        } catch (err) {
            errorEl.textContent = err.message + (err.fields ? ' ' + Object.values(err.fields).flat().join(' ') : '');
            errorEl.classList.remove('hidden');
        }
    });

    load();
</script>
@endsection
