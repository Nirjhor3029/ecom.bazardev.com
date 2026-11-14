@extends('admin.app')

@section('title', 'Media')

@section('header-actions')
    <div class="flex items-center gap-3">
        <span id="storage-total" class="text-sm text-stone-500"></span>
        <label class="btn-primary cursor-pointer">
            + Upload files
            <input id="file-input" type="file" multiple class="hidden">
        </label>
    </div>
@endsection

@section('content')
<div class="mb-4 flex items-center gap-2">
    <div class="flex overflow-hidden rounded-md border border-line">
        <button data-kind="" class="kind-tab bg-gold/10 px-3 py-1.5 text-sm font-medium text-gold">All</button>
        <button data-kind="image" class="kind-tab border-l border-line px-3 py-1.5 text-sm text-stone-600 hover:bg-stone-50">Images</button>
        <button data-kind="video" class="kind-tab border-l border-line px-3 py-1.5 text-sm text-stone-600 hover:bg-stone-50">Videos</button>
        <button data-kind="other" class="kind-tab border-l border-line px-3 py-1.5 text-sm text-stone-600 hover:bg-stone-50">Other files</button>
    </div>
    <input id="q" placeholder="Search filename…" class="field-input max-w-xs">
</div>

<div id="drop-zone" class="grid grid-cols-1 gap-6 sm:grid-cols-4">
    <div id="grid" class="grid grid-cols-3 gap-3 sm:col-span-3 sm:grid-cols-4 lg:grid-cols-5"></div>

    <!-- Detail panel -->
    <div id="detail-panel" class="hidden rounded-lg border border-line bg-white p-4 sm:col-span-1">
        <div id="detail-preview" class="mb-3 flex aspect-square items-center justify-center overflow-hidden rounded-md bg-stone-50"></div>
        <p id="detail-name" class="break-all text-sm font-medium text-ink"></p>
        <dl class="mt-3 space-y-1 text-xs text-stone-500">
            <div class="flex justify-between"><dt>Size</dt><dd id="detail-size"></dd></div>
            <div class="flex justify-between"><dt>Dimensions</dt><dd id="detail-dims"></dd></div>
            <div class="flex justify-between"><dt>Uploaded</dt><dd id="detail-date"></dd></div>
        </dl>
        <button id="copy-url-btn" class="btn-secondary mt-4 w-full text-xs">Copy URL</button>
        <button id="delete-btn" class="mt-2 w-full rounded-md border border-status-rust/30 px-4 py-2 text-xs font-medium text-status-rust hover:bg-status-rust-soft">Delete</button>
    </div>
</div>

<p id="empty-state" class="hidden py-16 text-center text-stone-400">No files yet — drag files here or click "Upload files".</p>
@endsection

@section('scripts')
<script>
    let items = [];
    let selected = null;
    let currentKind = '';

    function formatSize(bytes) {
        if (bytes < 1024) return `${bytes} B`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
        return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
    }

    function thumbHtml(item) {
        if (item.kind === 'image') {
            return `<img src="${item.url}" class="h-full w-full object-cover">`;
        }
        const icon = { video: '🎬', document: '📄', other: '📦' }[item.kind] ?? '📦';
        return `<div class="flex h-full flex-col items-center justify-center gap-1 text-stone-400">
                    <span class="text-2xl">${icon}</span>
                    <span class="px-1 text-center text-[10px] uppercase">${(item.mime || '').split('/')[1] ?? ''}</span>
                </div>`;
    }

    function renderGrid() {
        document.getElementById('empty-state').classList.toggle('hidden', items.length > 0);
        document.getElementById('grid').innerHTML = items.map((item) => `
            <button data-id="${item.id}" class="media-tile group relative aspect-square overflow-hidden rounded-lg border ${selected?.id === item.id ? 'border-gold ring-2 ring-gold/30' : 'border-line'} bg-white">
                ${thumbHtml(item)}
                <span class="absolute inset-x-0 bottom-0 truncate bg-black/50 px-1.5 py-1 text-left text-[10px] text-white opacity-0 group-hover:opacity-100">${item.name ?? ''}</span>
            </button>
        `).join('');
    }

    function renderDetail() {
        const panel = document.getElementById('detail-panel');
        if (!selected) { panel.classList.add('hidden'); return; }
        panel.classList.remove('hidden');

        document.getElementById('detail-preview').innerHTML = thumbHtml(selected);
        document.getElementById('detail-name').textContent = selected.name ?? '(untitled)';
        document.getElementById('detail-size').textContent = formatSize(selected.size);
        document.getElementById('detail-dims').textContent = selected.width ? `${selected.width} × ${selected.height}px` : '—';
        document.getElementById('detail-date').textContent = new Date(selected.created_at).toLocaleDateString();
    }

    async function load() {
        const params = new URLSearchParams({ per_page: 100 });
        if (currentKind) params.set('kind', currentKind);
        if (document.getElementById('q').value) params.set('q', document.getElementById('q').value);

        const res = await AdminApi.call('/media?' + params.toString());
        items = res.data;
        document.getElementById('storage-total').textContent = formatSize(res.meta.total_size) + ' used';
        renderGrid();
    }

    document.querySelectorAll('.kind-tab').forEach((btn) => {
        btn.addEventListener('click', () => {
            currentKind = btn.dataset.kind;
            document.querySelectorAll('.kind-tab').forEach((b) => b.classList.remove('bg-gold/10', 'text-gold', 'font-medium'));
            btn.classList.add('bg-gold/10', 'text-gold', 'font-medium');
            load();
        });
    });

    document.getElementById('q').addEventListener('input', () => { clearTimeout(window._t); window._t = setTimeout(load, 300); });

    document.getElementById('grid').addEventListener('click', (e) => {
        const tile = e.target.closest('.media-tile');
        if (!tile) return;
        selected = items.find((i) => i.id == tile.dataset.id);
        renderGrid();
        renderDetail();
    });

    async function uploadFiles(files) {
        for (const file of files) {
            const formData = new FormData();
            formData.append('file', file);
            try {
                await AdminApi.upload('/media', formData);
            } catch (err) {
                alert(`${file.name}: ${err.message}`);
            }
        }
        load();
    }

    document.getElementById('file-input').addEventListener('change', (e) => uploadFiles(e.target.files));

    const dropZone = document.getElementById('drop-zone');
    ['dragover', 'dragleave', 'drop'].forEach((evt) => {
        dropZone.addEventListener(evt, (e) => { e.preventDefault(); e.stopPropagation(); });
    });
    dropZone.addEventListener('dragover', () => dropZone.classList.add('ring-2', 'ring-gold/40', 'rounded-lg'));
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('ring-2', 'ring-gold/40', 'rounded-lg'));
    dropZone.addEventListener('drop', (e) => {
        dropZone.classList.remove('ring-2', 'ring-gold/40', 'rounded-lg');
        if (e.dataTransfer.files.length) uploadFiles(e.dataTransfer.files);
    });

    document.getElementById('copy-url-btn').addEventListener('click', () => {
        if (!selected) return;
        navigator.clipboard.writeText(selected.url);
        const btn = document.getElementById('copy-url-btn');
        const original = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(() => { btn.textContent = original; }, 1200);
    });

    document.getElementById('delete-btn').addEventListener('click', async () => {
        if (!selected) return;
        if (!confirm(`Delete "${selected.name}"? This can't be undone.`)) return;

        try {
            await AdminApi.call(`/media/${selected.id}`, { method: 'DELETE' });
        } catch (err) {
            if (err.code === 'MEDIA_IN_USE' && confirm(`${err.message}\n\nDelete anyway?`)) {
                await AdminApi.call(`/media/${selected.id}?force=1`, { method: 'DELETE' });
            } else {
                return;
            }
        }

        selected = null;
        load();
    });

    load();
</script>
@endsection
