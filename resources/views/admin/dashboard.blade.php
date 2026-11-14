@extends('admin.app')

@section('title', 'Dashboard')

@section('header-actions')
<span class="hidden items-center gap-1.5 rounded-full bg-status-sage-soft px-2.5 py-1 text-[12px] font-medium text-status-sage sm:inline-flex"><span class="h-1.5 w-1.5 animate-pulse rounded-full bg-status-sage"></span>Live data</span>
@endsection

@section('content')
<!-- Heading + range -->
<div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <h2 class="text-xl font-semibold text-ink">Welcome back, <span id="welcome-name">—</span></h2>
        <p class="mt-1 text-sm text-stone-500">Here is what is happening across your honey &amp; nuts store — <span id="range-label" class="font-medium text-ink">Last 7 days</span>.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <div id="range-tabs" class="inline-flex overflow-hidden rounded-md border border-line bg-white text-[13px]">
            <button data-range="today" class="px-3 py-1.5 text-stone-600 hover:bg-stone-50">Today</button>
            <button data-range="yesterday" class="border-l border-line px-3 py-1.5 text-stone-600 hover:bg-stone-50">Yesterday</button>
            <button data-range="7d" class="border-l border-line px-3 py-1.5 text-stone-600 hover:bg-stone-50">7 days</button>
            <button data-range="30d" class="border-l border-line px-3 py-1.5 text-stone-600 hover:bg-stone-50">30 days</button>
            <button data-range="custom" class="border-l border-line px-3 py-1.5 text-stone-600 hover:bg-stone-50">Custom</button>
        </div>
        <button id="refresh-btn" class="btn-secondary !px-2.5" title="Refresh"><svg id="refresh-icon" class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M16.5 10a6.5 6.5 0 1 1-2-4.7M16.5 3.5v3.3h-3.3"/></svg></button>
        <button id="export-btn" class="btn-primary inline-flex items-center gap-1.5"><svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M10 3v9m0 0-3-3m3 3 3-3M4 14.5V16a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-1.5"/></svg>Export report</button>
    </div>
</div>
<div id="custom-range" class="mt-3 hidden items-center gap-2 text-sm">
    <input type="date" id="from-date" class="field-input !w-40"> <span class="text-stone-400">to</span> <input type="date" id="to-date" class="field-input !w-40">
    <button id="apply-custom" class="btn-secondary">Apply</button>
</div>

<!-- Triage -->
<section class="mt-6 rounded-lg border border-line bg-white">
    <div class="flex items-center justify-between border-b border-line px-5 py-3.5">
        <div class="flex items-center gap-2"><svg class="h-4 w-4 text-gold" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M10 2.5 18 16.5H2L10 2.5Z"/><path d="M10 8v3.5M10 14h.01"/></svg><h3 class="text-sm font-semibold text-ink">Action needed today</h3><span id="triage-count" class="rounded-full bg-gold-soft px-2 py-0.5 text-[11px] font-medium text-gold"></span></div>
        <button id="triage-toggle" class="text-xs text-stone-500 hover:text-ink">Hide</button>
    </div>
    <div id="triage" class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 xl:grid-cols-5"></div>
</section>

<!-- KPIs -->
<section id="kpis" class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6"></section>

<!-- Chart + funnel -->
<section class="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-lg border border-line bg-white p-5 xl:col-span-2">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div><h3 class="text-sm font-semibold text-ink">Sales &amp; revenue</h3><p class="mt-0.5 text-xs text-stone-500">Orders placed in the period, excluding cancelled / returned.</p></div>
            <div id="chart-mode" class="hidden overflow-hidden rounded-md border border-line text-xs"><button data-mode="day" class="px-2.5 py-1">Daily</button><button data-mode="week" class="border-l border-line px-2.5 py-1">Weekly</button></div>
        </div>
        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs">
            <span class="flex items-center gap-1.5 text-stone-500"><span class="h-2 w-2 rounded-full bg-gold"></span>Total gross <b id="t-gross" class="font-semibold text-ink tabular-nums">—</b></span>
            <span class="flex items-center gap-1.5 text-stone-500"><span class="h-2 w-2 rounded-full bg-status-sage"></span>Cash realized <b id="t-real" class="font-semibold text-ink tabular-nums">—</b></span>
            <span class="flex items-center gap-1.5 text-stone-500"><span class="h-2 w-2 rounded-full bg-status-rust"></span>Discounts <b id="t-disc" class="font-semibold text-ink tabular-nums">—</b></span>
        </div>
        <div id="chart" class="relative mt-4 h-60 w-full"></div>
        <div id="chart-best" class="mt-3 rounded-md bg-gold-soft/60 px-3 py-2 text-xs text-stone-600"></div>
    </div>
    <div class="rounded-lg border border-line bg-white p-5">
        <h3 class="text-sm font-semibold text-ink">Conversion funnel</h3>
        <p class="mt-0.5 text-xs text-stone-500">From started carts to delivered orders.</p>
        <div id="funnel" class="mt-4 space-y-3"></div>
        <div id="funnel-note" class="mt-4 rounded-md bg-stone-50 px-3 py-2 text-xs text-stone-500"></div>
    </div>
</section>

<!-- Pipeline -->
<section class="mt-6 rounded-lg border border-line bg-white p-5">
    <div class="flex items-center justify-between"><h3 class="text-sm font-semibold text-ink">Order pipeline</h3><a id="pipeline-all" href="/admin/orders" class="text-sm text-gold hover:underline">View all orders</a></div>
    <div id="pipeline" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4 xl:grid-cols-7"></div>
</section>

<!-- Recent orders -->
<section class="mt-6 rounded-lg border border-line bg-white">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-4">
        <div class="flex items-center gap-2"><h3 class="text-sm font-semibold text-ink">Recent orders</h3><span class="rounded-full bg-stone-100 px-2 py-0.5 text-[11px] text-stone-500">Realtime</span></div>
        <div class="flex flex-wrap items-center gap-2">
            <input id="ro-q" placeholder="Search order no. or phone…" class="field-input !w-56">
            <select id="ro-status" class="field-input !w-40"><option value="">All statuses</option><option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="processing">Processing</option><option value="packed">Packed</option><option value="shipped">Shipped</option><option value="delivered">Delivered</option><option value="cancelled">Cancelled</option><option value="on_hold">On hold</option></select>
            <a href="/admin/orders" class="text-sm text-gold hover:underline">View all</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-line bg-stone-50/60 text-left text-xs text-stone-500"><tr>
                <th class="px-5 py-3 font-medium">Order</th><th class="px-5 py-3 font-medium">Customer</th><th class="px-5 py-3 font-medium">Placed</th><th class="px-5 py-3 font-medium">Items</th>
                <th class="px-5 py-3 text-right font-medium">Amount</th><th class="px-5 py-3 font-medium">Payment</th><th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3"></th>
            </tr></thead>
            <tbody id="recent-orders"></tbody>
        </table>
    </div>
</section>

<!-- Top products + stock alerts -->
<section class="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-lg border border-line bg-white xl:col-span-2">
        <div class="flex items-center justify-between border-b border-line px-5 py-4"><div><h3 class="text-sm font-semibold text-ink">Top products</h3><p class="mt-0.5 text-xs text-stone-500">Ranked by units sold in the period.</p></div><a href="/admin/products" class="text-sm text-gold hover:underline">Manage catalog</a></div>
        <div id="top-products" class="divide-y divide-line"></div>
    </div>
    <div class="rounded-lg border border-line bg-white">
        <div class="flex items-center justify-between border-b border-line px-5 py-4"><h3 class="text-sm font-semibold text-ink">Stock &amp; batch alerts</h3><a href="/admin/inventory" class="text-sm text-gold hover:underline">Inventory</a></div>
        <div id="stock-alerts" class="space-y-3 p-4"></div>
    </div>
</section>

<!-- Shortcuts + payments -->
<section class="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-3">
    <div class="rounded-lg border border-line bg-white p-5">
        <h3 class="text-sm font-semibold text-ink">Quick actions</h3><p class="mt-0.5 text-xs text-stone-500">Frequent daily store workflows.</p>
        <div class="mt-4 grid grid-cols-2 gap-3">
            @foreach ([['/admin/products/create', 'Add product', 'Create SKU, price & stock', 'M10 4v12M4 10h12'], ['/admin/orders', 'Process orders', 'Confirm, pack & ship', 'M4.5 2.5h11v15l-2-1.3-1.8 1.3-1.7-1.3-1.8 1.3-1.7-1.3-1.8 1.3v-15Z'], ['/admin/inventory', 'Adjust stock', 'Receive or correct stock', 'M3 6.5 10 3l7 3.5-7 3.5-7-3.5ZM3 6.5v7L10 17l7-3.5v-7'], ['/admin/media', 'Upload media', 'Product photos & videos', 'M2.5 3.5h15v13h-15zM3.2 14.5 7.5 10l2.5 2.5 3-3.5 4 4']] as [$href, $t, $s, $d])
            <a href="{{ $href }}" class="rounded-md border border-line p-3 transition-colors hover:border-gold/40 hover:bg-gold-soft/30">
                <svg class="h-5 w-5 text-gold" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="{{ $d }}"/></svg>
                <p class="mt-2 text-sm font-medium text-ink">{{ $t }}</p><p class="text-xs text-stone-500">{{ $s }}</p>
            </a>
            @endforeach
        </div>
    </div>
    <div class="rounded-lg border border-line bg-white p-5 xl:col-span-2">
        <div class="flex items-start justify-between gap-3"><div><h3 class="text-sm font-semibold text-ink">Payment methods</h3><p class="mt-0.5 text-xs text-stone-500">Revenue and collection status by method.</p></div><div id="pay-totals" class="text-right text-xs text-stone-500"></div></div>
        <div id="payments" class="mt-4 space-y-3"></div>
    </div>
</section>
@endsection

@section('scripts')
<script>
(() => {
    const $ = (id) => document.getElementById(id);
    const money = (p) => '৳' + Math.round((p || 0) / 100).toLocaleString('en-US');
    const axis = (p) => { const t = (p || 0) / 100; return '৳' + (t >= 1000 ? (Math.round(t / 100) / 10) + 'k' : Math.round(t)); };
    const num = (n) => Number(n || 0).toLocaleString('en-US');
    const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const tone = {
        amber: 'bg-status-amber-soft text-status-amber', rust: 'bg-status-rust-soft text-status-rust',
        slate: 'bg-status-slate-soft text-status-slate', sage: 'bg-status-sage-soft text-status-sage', stone: 'bg-stone-100 text-stone-600',
    };
    const statusPill = {
        pending: tone.stone, confirmed: tone.slate, processing: tone.amber, packed: tone.amber, shipped: tone.slate,
        delivered: tone.sage, cancelled: tone.rust, returned: tone.rust, failed: tone.rust, on_hold: tone.rust,
    };

    let state = { range: '7d', from: '', to: '', mode: 'day', data: null };

    // ---- helpers
    const delta = (c, invert = false) => {
        if (c === null || c === undefined) return '<span class="text-[11px] text-stone-400">no prior data</span>';
        const good = invert ? c <= 0 : c >= 0;
        return `<span class="inline-flex items-center gap-0.5 text-[11px] font-medium ${good ? 'text-status-sage' : 'text-status-rust'}">${c >= 0 ? '▲' : '▼'} ${Math.abs(c)}%</span> <span class="text-[11px] text-stone-400">vs previous</span>`;
    };

    function renderTriage(items) {
        const live = items.filter((i) => i.count > 0);
        $('triage-count').textContent = live.length ? `${live.length} item${live.length > 1 ? 's' : ''}` : 'All clear';
        $('triage').innerHTML = items.map((i) => `
            <div class="flex flex-col rounded-md border border-line p-3.5 ${i.count ? '' : 'opacity-60'}">
                <div class="flex items-center justify-between"><span class="rounded-full px-2 py-0.5 text-[11px] font-semibold ${i.count ? tone[i.tone] : tone.stone}">${num(i.count)}</span></div>
                <p class="mt-2 text-sm font-medium text-ink">${esc(i.title)}</p><p class="mt-0.5 flex-1 text-xs text-stone-500">${esc(i.hint)}</p>
                <a href="${i.url}" class="mt-3 text-xs font-medium text-gold hover:underline">${esc(i.cta)} →</a>
            </div>`).join('');
    }

    function donut(pct) {
        const r = 15.9, c = 2 * Math.PI * r, v = pct ?? 0;
        return `<div class="relative -mt-1 h-10 w-10"><svg viewBox="0 0 36 36" class="h-10 w-10 -rotate-90"><circle cx="18" cy="18" r="${r}" fill="none" stroke="#e8efe4" stroke-width="3.5"/><circle cx="18" cy="18" r="${r}" fill="none" stroke="#5c7a52" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="${(v / 100) * c} ${c}"/></svg><span class="absolute inset-0 flex items-center justify-center text-[11px] font-semibold text-ink">${pct === null ? '—' : v + '%'}</span></div>`;
    }

    function renderKpis(k) {
        const card = (label, value, foot, extra = '') => `<div class="rounded-lg border border-line bg-white p-4"><div class="flex items-start justify-between"><p class="text-xs text-stone-500">${label}</p>${extra}</div><p class="mt-2 text-2xl font-semibold tabular-nums text-ink">${value}</p><div class="mt-1.5">${foot}</div></div>`;
        $('kpis').innerHTML =
            card('Total orders', num(k.orders.value), `${delta(k.orders.change)}<p class="mt-0.5 text-[11px] text-stone-400">${num(k.orders.in_fulfillment)} in fulfillment</p>`) +
            card('Net revenue', money(k.revenue.value), `${delta(k.revenue.change)}<p class="mt-0.5 text-[11px] text-stone-400">${k.revenue.cod_share === null ? '—' : k.revenue.cod_share + '% cash on delivery'}</p>`) +
            card('Avg. order value', money(k.aov.value), delta(k.aov.change)) +
            card('Pending orders', num(k.pending.value), `<a href="/admin/orders?status=pending" class="text-[11px] font-medium ${k.pending.value ? 'text-status-amber' : 'text-stone-400'} hover:underline">${k.pending.value ? 'Requires action →' : 'Nothing waiting'}</a>`) +
            card('Delivered', num(k.delivered.value), `<p class="text-[11px] text-stone-400">Success rate of finished orders</p>`, donut(k.delivered.success_rate)) +
            card('Returns / failed', num(k.returns.value), `<span class="text-[11px] font-medium ${k.returns.rate <= k.returns.prev_rate ? 'text-status-sage' : 'text-status-rust'}">${k.returns.rate}% of orders</span> <span class="text-[11px] text-stone-400">(prev ${k.returns.prev_rate}%)</span>`);
    }

    // ---- chart (hand-rolled SVG, no dependency)
    function series() {
        const c = state.data.chart;
        if (state.mode === 'week' && c.granularity === 'day') {
            const out = { labels: [], gross: [], realized: [], discounts: [] };
            for (let i = 0; i < c.labels.length; i += 7) {
                const sl = (a) => a.slice(i, i + 7).reduce((x, y) => x + y, 0);
                out.labels.push(c.labels[i] + (c.labels[Math.min(i + 6, c.labels.length - 1)] !== c.labels[i] ? ' – ' + c.labels[Math.min(i + 6, c.labels.length - 1)] : ''));
                out.gross.push(sl(c.gross)); out.realized.push(sl(c.realized)); out.discounts.push(sl(c.discounts));
            }
            return out;
        }
        return c;
    }
    function renderChart() {
        const c = state.data.chart, s = series(), W = 720, H = 240, pl = 44, pr = 12, pt = 14, pb = 26;
        $('chart-mode').classList.toggle('hidden', c.granularity !== 'day' || c.labels.length <= 7);
        $('chart-mode').querySelectorAll('button').forEach((b) => { b.className = 'px-2.5 py-1 ' + (b.dataset.mode === state.mode ? 'bg-gold-soft text-gold font-medium' : 'text-stone-500') + (b.dataset.mode === 'week' ? ' border-l border-line' : ''); });
        $('t-gross').textContent = money(c.totals.gross); $('t-real').textContent = money(c.totals.realized); $('t-disc').textContent = money(c.totals.discounts);
        const max = Math.max(1, ...s.gross), n = s.labels.length, x = (i) => pl + (n === 1 ? (W - pl - pr) / 2 : (i * (W - pl - pr)) / (n - 1)), y = (v) => pt + (1 - v / max) * (H - pt - pb);
        const path = (arr) => arr.map((v, i) => `${i ? 'L' : 'M'}${x(i).toFixed(1)},${y(v).toFixed(1)}`).join(' ');
        const grid = [0, 0.25, 0.5, 0.75, 1].map((g) => `<line x1="${pl}" x2="${W - pr}" y1="${y(max * g)}" y2="${y(max * g)}" stroke="#e4d9c8" stroke-width="1" stroke-dasharray="${g ? '3 4' : '0'}"/><text x="${pl - 6}" y="${y(max * g) + 3}" text-anchor="end" font-size="10" fill="#8a7f72">${axis(max * g)}</text>`).join('');
        const step = Math.ceil(n / 8), xl = s.labels.map((l, i) => (i % step === 0 || i === n - 1) ? `<text x="${x(i)}" y="${H - 6}" text-anchor="middle" font-size="10" fill="#8a7f72">${esc(String(l).split(' – ')[0])}</text>` : '').join('');
        $('chart').innerHTML = `<svg viewBox="0 0 ${W} ${H}" class="h-full w-full" preserveAspectRatio="none" id="chart-svg">
            <defs><linearGradient id="g1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#a8701f" stop-opacity=".28"/><stop offset="1" stop-color="#a8701f" stop-opacity="0"/></linearGradient></defs>
            ${grid}${xl}
            <path d="${path(s.gross)} L${x(n - 1)},${y(0)} L${x(0)},${y(0)} Z" fill="url(#g1)"/>
            <path d="${path(s.gross)}" fill="none" stroke="#a8701f" stroke-width="2.2" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
            <path d="${path(s.realized)}" fill="none" stroke="#5c7a52" stroke-width="1.8" stroke-dasharray="5 4" vector-effect="non-scaling-stroke"/>
            <line id="hover-line" x1="0" x2="0" y1="${pt}" y2="${H - pb}" stroke="#a8701f" stroke-opacity=".35" display="none"/><circle id="hover-dot" r="4" fill="#a8701f" stroke="#fff" stroke-width="2" display="none"/>
        </svg><div id="tip" class="pointer-events-none absolute hidden rounded-md bg-walnut px-2.5 py-1.5 text-[11px] leading-4 text-stone-100 shadow-lg"></div>`;
        const svg = $('chart-svg'), tip = $('tip');
        svg.addEventListener('mousemove', (e) => {
            const r = svg.getBoundingClientRect(), px = ((e.clientX - r.left) / r.width) * W;
            const i = Math.max(0, Math.min(n - 1, Math.round(((px - pl) / (W - pl - pr)) * (n - 1))));
            $('hover-line').setAttribute('x1', x(i)); $('hover-line').setAttribute('x2', x(i)); $('hover-line').removeAttribute('display');
            $('hover-dot').setAttribute('cx', x(i)); $('hover-dot').setAttribute('cy', y(s.gross[i])); $('hover-dot').removeAttribute('display');
            tip.innerHTML = `<b>${esc(s.labels[i])}</b><br>Gross ${money(s.gross[i])}<br>Realized ${money(s.realized[i])}<br>Discounts ${money(s.discounts[i])}`;
            tip.classList.remove('hidden'); tip.style.left = Math.min(r.width - 130, Math.max(0, (x(i) / W) * r.width + 10)) + 'px'; tip.style.top = '6px';
        });
        svg.addEventListener('mouseleave', () => { tip.classList.add('hidden'); $('hover-line').setAttribute('display', 'none'); $('hover-dot').setAttribute('display', 'none'); });
        $('chart-best').innerHTML = c.best ? `<b class="text-ink">Best ${c.granularity === 'hour' ? 'hour' : 'day'}:</b> ${esc(c.best.label)} — ${money(c.best.amount)} (${c.best.share}% of the period's revenue).` : 'No sales in this period yet.';
    }

    function renderFunnel(f) {
        const shades = ['bg-gold', 'bg-status-amber', 'bg-status-sage'];
        $('funnel').innerHTML = f.steps.map((s, i) => `<div><div class="mb-1 flex justify-between text-xs"><span class="text-stone-600">${i + 1}. ${esc(s.label)}</span><span class="tabular-nums text-ink"><b>${num(s.count)}</b> <span class="text-stone-400">(${s.pct}%)</span></span></div><div class="h-2 overflow-hidden rounded-full bg-stone-100"><div class="h-full rounded-full ${shades[i]}" style="width:${s.pct}%"></div></div></div>`).join('');
        $('funnel-note').innerHTML = `Cart abandonment: <b class="text-ink">${f.abandonment_rate}%</b> (${num(f.abandoned)} cart${f.abandoned === 1 ? '' : 's'} left behind).`;
    }

    function renderPipeline(p) {
        const bg = { pending: 'bg-stone-50 border-stone-200', confirmed: 'bg-status-slate-soft/60 border-status-slate/20', processing: 'bg-status-amber-soft/60 border-status-amber/20', packed: 'bg-status-amber-soft/60 border-status-amber/20', shipped: 'bg-status-slate-soft/60 border-status-slate/20', delivered: 'bg-status-sage-soft/70 border-status-sage/20', returned: 'bg-status-rust-soft/70 border-status-rust/20' };
        const dot = { pending: 'bg-stone-400', confirmed: 'bg-status-slate', processing: 'bg-status-amber', packed: 'bg-status-amber', shipped: 'bg-status-slate', delivered: 'bg-status-sage', returned: 'bg-status-rust' };
        $('pipeline-all').textContent = `View all ${num(p.total)} orders`;
        $('pipeline').innerHTML = p.stages.map((s) => `<a href="/admin/orders${['cancelled', 'returned'].includes(s.key) ? '' : '?status=' + s.key}" class="rounded-md border p-3 transition-shadow hover:shadow-sm ${bg[s.key]}"><div class="flex items-center gap-1.5 text-xs text-stone-600"><span class="h-1.5 w-1.5 rounded-full ${dot[s.key]}"></span>${esc(s.label)}</div><p class="mt-1.5 text-xl font-semibold tabular-nums text-ink">${num(s.count)}</p></a>`).join('');
    }

    function renderTop(list) {
        $('top-products').innerHTML = list.map((p, i) => `
            <a href="${p.url ?? '#'}" class="flex items-center gap-3 px-5 py-3 hover:bg-stone-50/60">
                <span class="w-4 text-xs text-stone-400">${i + 1}</span>
                <span class="h-11 w-11 flex-shrink-0 overflow-hidden rounded-md bg-gold-soft">${p.image ? `<img src="${esc(p.image)}" class="h-full w-full object-cover" alt="">` : '<span class="flex h-full items-center justify-center">🍯</span>'}</span>
                <span class="min-w-0 flex-1"><span class="block truncate text-sm font-medium text-ink">${esc(p.name)}${p.variant ? ` <span class="font-normal text-stone-400">· ${esc(p.variant)}</span>` : ''}</span><span class="text-xs text-stone-500">${num(p.units)} sold</span></span>
                <span class="text-right"><span class="block text-sm font-semibold tabular-nums text-ink">${money(p.revenue)}</span>${p.stock_state ? `<span class="text-[11px] ${p.stock_state === 'ok' ? 'text-status-sage' : p.stock_state === 'low' ? 'text-status-amber' : 'text-status-rust'}">${p.stock_state === 'out' ? 'Out of stock' : num(p.stock) + ' in stock' + (p.stock_state === 'low' ? ' (low)' : '')}</span>` : ''}</span>
            </a>`).join('') || '<p class="px-5 py-8 text-center text-sm text-stone-400">No sales in this period yet.</p>';
    }

    function renderStock(a) {
        const rows = a.stock.map((s) => `<div class="flex items-center justify-between gap-3 rounded-md border border-line p-3"><div class="min-w-0"><span class="rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase ${s.state === 'out' ? tone.rust : tone.amber}">${s.state === 'out' ? 'Out of stock' : 'Low stock'}</span><p class="mt-1 truncate text-sm font-medium text-ink">${esc(s.name)}${s.variant ? ` <span class="font-normal text-stone-400">· ${esc(s.variant)}</span>` : ''}</p><p class="text-xs text-stone-500">${s.available <= 0 ? 'Nothing left to sell' : s.available + ' left (alert at ' + s.threshold + ')'}</p></div><a href="/admin/inventory" class="flex-shrink-0 text-xs font-medium text-gold hover:underline">Restock</a></div>`).join('');
        const exp = a.expiring.map((b) => `<div class="rounded-md border border-line p-3"><span class="rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase ${tone.slate}">Near expiry</span><p class="mt-1 truncate text-sm font-medium text-ink">${esc(b.name)}</p><p class="text-xs text-stone-500">Batch ${esc(b.batch_no)} · ${num(b.qty)} units · expires ${esc(b.expiry)} (${b.days_left} days)</p></div>`).join('');
        $('stock-alerts').innerHTML = (rows + exp) || '<p class="py-6 text-center text-sm text-stone-400">Stock levels look healthy.</p>';
    }

    function renderPayments(p) {
        $('pay-totals').innerHTML = `Collected <b class="text-ink">${money(p.collected)}</b><br>Outstanding <b class="text-ink">${money(p.outstanding)}</b>`;
        $('payments').innerHTML = p.methods.map((m) => `<div><div class="mb-1 flex items-center justify-between text-sm"><span class="font-medium text-ink">${esc(m.label)} <span class="text-xs font-normal text-stone-400">· ${num(m.orders)} orders</span></span><span class="tabular-nums text-ink">${money(m.amount)}</span></div><div class="h-2 overflow-hidden rounded-full bg-stone-100"><div class="h-full rounded-full bg-gold" style="width:${m.share}%"></div></div><p class="mt-1 text-xs text-stone-500">${m.share}% of revenue · collected ${money(m.collected)} · due ${money(m.due)}</p></div>`).join('') || '<p class="py-4 text-sm text-stone-400">No orders in this period.</p>';
    }

    // ---- data loading
    function qs() {
        const p = new URLSearchParams({ range: state.range });
        if (state.range === 'custom') { p.set('from', state.from); p.set('to', state.to); }
        return p.toString();
    }
    async function load() {
        $('refresh-icon').classList.add('animate-spin');
        try {
            const res = await AdminApi.call('/dashboard?' + qs());
            state.data = res.data;
            $('range-label').textContent = res.data.range.label;
            renderTriage(res.data.triage); renderKpis(res.data.kpis); renderChart(); renderFunnel(res.data.funnel);
            renderPipeline(res.data.pipeline); renderTop(res.data.top_products); renderStock(res.data.stock_alerts); renderPayments(res.data.payments);
        } catch (e) {
            $('kpis').innerHTML = `<p class="col-span-full rounded-lg border border-status-rust/30 bg-status-rust-soft px-4 py-3 text-sm text-status-rust">${esc(e.message)}</p>`;
        } finally { $('refresh-icon').classList.remove('animate-spin'); }
        loadOrders();
    }
    async function loadOrders() {
        const p = new URLSearchParams({ per_page: 6 });
        if ($('ro-q').value) p.set('q', $('ro-q').value);
        if ($('ro-status').value) p.set('filter[status]', $('ro-status').value);
        const res = await AdminApi.call('/orders?' + p.toString());
        const when = (t) => t ? new Date(t).toLocaleString('en-GB', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' }) : '—';
        $('recent-orders').innerHTML = res.data.map((o) => `
            <tr class="border-b border-line last:border-0 hover:bg-stone-50/50">
                <td class="px-5 py-3 font-medium tabular-nums text-ink">${esc(o.order_no)}${o.risk_flags ? ' <span class="ml-1 rounded bg-status-rust-soft px-1.5 py-0.5 text-[10px] font-semibold text-status-rust">RISK</span>' : ''}</td>
                <td class="px-5 py-3"><span class="block text-ink">${esc(o.shipping_address?.name ?? '—')}</span><span class="text-xs tabular-nums text-stone-500">${esc(o.shipping_address?.phone ?? '')}${o.shipping_address?.district ? ' · ' + esc(o.shipping_address.district) : ''}</span></td>
                <td class="px-5 py-3 text-stone-500">${when(o.placed_at)}</td>
                <td class="px-5 py-3 text-stone-500">${o.items_count ?? '—'} item${o.items_count === 1 ? '' : 's'}${o.qty_total ? ` · ${o.qty_total} pcs` : ''}</td>
                <td class="px-5 py-3 text-right font-medium tabular-nums text-ink">${money(o.grand_total)}</td>
                <td class="px-5 py-3 text-xs text-stone-500">${esc((o.payment_method || '').toUpperCase())}<br><span class="${o.payment_status === 'paid' ? 'text-status-sage' : 'text-stone-400'}">${esc(o.payment_status)}</span></td>
                <td class="px-5 py-3"><span class="status-pill ${statusPill[o.status] ?? ''}">${esc(o.status.replace('_', ' '))}</span></td>
                <td class="px-5 py-3 text-right"><a href="/admin/orders/${o.id}" class="text-gold hover:underline">View</a></td>
            </tr>`).join('') || '<tr><td class="px-5 py-8 text-center text-stone-400" colspan="8">No orders match.</td></tr>';
    }

    // ---- controls
    function paintTabs() {
        $('range-tabs').querySelectorAll('button').forEach((b) => b.classList.toggle('!bg-gold-soft', b.dataset.range === state.range)), $('range-tabs').querySelectorAll('button').forEach((b) => b.classList.toggle('!text-gold', b.dataset.range === state.range));
    }
    $('range-tabs').addEventListener('click', (e) => {
        const b = e.target.closest('button'); if (!b) return;
        state.range = b.dataset.range; paintTabs();
        $('custom-range').classList.toggle('hidden', state.range !== 'custom'); $('custom-range').classList.toggle('flex', state.range === 'custom');
        if (state.range !== 'custom') load();
    });
    $('apply-custom').addEventListener('click', () => { if ($('from-date').value && $('to-date').value) { state.from = $('from-date').value; state.to = $('to-date').value; load(); } });
    $('refresh-btn').addEventListener('click', load);
    $('chart-mode').addEventListener('click', (e) => { const b = e.target.closest('button'); if (b && state.data) { state.mode = b.dataset.mode; renderChart(); } });
    $('triage-toggle').addEventListener('click', () => { const t = $('triage'); t.classList.toggle('hidden'); $('triage-toggle').textContent = t.classList.contains('hidden') ? 'Show' : 'Hide'; });
    $('ro-q').addEventListener('input', () => { clearTimeout(window._ro); window._ro = setTimeout(loadOrders, 300); });
    $('ro-status').addEventListener('change', loadOrders);
    $('export-btn').addEventListener('click', async () => {
        const res = await fetch('/api/v1/admin/dashboard/export?' + qs(), { headers: { Authorization: 'Bearer ' + AdminApi.getToken() } });
        if (!res.ok) return alert('Export failed.');
        const blob = await res.blob(), a = document.createElement('a');
        a.href = URL.createObjectURL(blob); a.download = (res.headers.get('content-disposition')?.match(/filename="?([^";]+)/)?.[1]) || 'orders.csv'; a.click(); URL.revokeObjectURL(a.href);
    });

    AdminApi.call('/auth/me').then((r) => { $('welcome-name').textContent = (r.data.name || '').split(' ')[0] || 'there'; });
    paintTabs(); load();
    setInterval(() => { if (!document.hidden && state.range !== 'custom') load(); }, 60000);
})();
</script>
@endsection
