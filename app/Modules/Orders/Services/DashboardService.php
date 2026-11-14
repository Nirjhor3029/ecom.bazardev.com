<?php

namespace App\Modules\Orders\Services;

use App\Modules\Cart\Models\Cart;
use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Inventory\Models\InventoryBatch;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Numbers for the admin dashboard. Everything is derived from real orders / carts / inventory —
 * nothing here is estimated. Day boundaries are Asia/Dhaka (the store's business day), while the
 * database stays in UTC.
 */
class DashboardService
{
    public const string TZ = 'Asia/Dhaka';

    /** Orders in these states never count towards revenue. */
    private const array LOST = ['cancelled', 'failed', 'returned'];

    /** @return array{key: string, from: CarbonImmutable, to: CarbonImmutable, label: string, hourly: bool} */
    public function resolveRange(string $key, ?string $from = null, ?string $to = null): array
    {
        $now = CarbonImmutable::now(self::TZ);

        [$start, $end, $label] = match ($key) {
            'today' => [$now->startOfDay(), $now->endOfDay(), 'Today'],
            'yesterday' => [$now->subDay()->startOfDay(), $now->subDay()->endOfDay(), 'Yesterday'],
            '30d' => [$now->subDays(29)->startOfDay(), $now->endOfDay(), 'Last 30 days'],
            'custom' => $this->custom($from, $to, $now),
            default => [$now->subDays(6)->startOfDay(), $now->endOfDay(), 'Last 7 days'],
        };

        return [
            'key' => in_array($key, ['today', 'yesterday', '30d', 'custom'], true) ? $key : '7d',
            'from' => $start, 'to' => $end, 'label' => $label,
            'hourly' => $start->isSameDay($end),
        ];
    }

    private function custom(?string $from, ?string $to, CarbonImmutable $now): array
    {
        try {
            $start = CarbonImmutable::parse($from ?: $now->subDays(6)->toDateString(), self::TZ)->startOfDay();
            $end = CarbonImmutable::parse($to ?: $now->toDateString(), self::TZ)->endOfDay();
        } catch (\Throwable) {
            return [$now->subDays(6)->startOfDay(), $now->endOfDay(), 'Last 7 days'];
        }

        if ($end->lt($start)) {
            [$start, $end] = [$end->startOfDay(), $start->endOfDay()];
        }
        if ($start->diffInDays($end) > 365) {
            $start = $end->subDays(365)->startOfDay();
        }

        return [$start, $end, $start->format('d M').' – '.$end->format('d M Y')];
    }

    public function build(array $range): array
    {
        $from = $range['from'];
        $to = $range['to'];
        $len = max(1, (int) ceil($from->diffInSeconds($to) / 86400));
        $prevFrom = $from->subDays($len);
        $prevTo = $from->subSecond();

        $orders = $this->ordersBetween($from, $to);
        $prev = $this->ordersBetween($prevFrom, $prevTo);

        return [
            'range' => ['key' => $range['key'], 'label' => $range['label'], 'from' => $from->toDateString(), 'to' => $to->toDateString()],
            'kpis' => $this->kpis($orders, $prev),
            'triage' => $this->triage(),
            'chart' => $this->chart($orders, $range),
            'funnel' => $this->funnel($orders, $from, $to),
            'pipeline' => $this->pipeline(),
            'top_products' => $this->topProducts($from, $to),
            'stock_alerts' => $this->stockAlerts(),
            'payments' => $this->payments($orders),
            'generated_at' => now()->toIso8601String(),
        ];
    }

    /** @return Collection<int, Order> light rows (no relations) placed inside the window */
    private function ordersBetween(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Order::query()
            ->whereBetween(DB::raw('COALESCE(placed_at, created_at)'), [$from->utc(), $to->utc()])
            ->get(['id', 'status', 'payment_method', 'payment_status', 'grand_total', 'discount_total', 'paid_total', 'due_total', 'placed_at', 'created_at', 'source']);
    }

    private function valid(Collection $orders): Collection
    {
        return $orders->reject(fn (Order $o) => in_array($o->status, self::LOST, true));
    }

    private function pct(float|int $now, float|int $before): ?float
    {
        return $before > 0 ? round(($now - $before) / $before * 100, 1) : null;
    }

    private function kpis(Collection $orders, Collection $prev): array
    {
        $valid = $this->valid($orders);
        $validPrev = $this->valid($prev);
        $revenue = (int) $valid->sum('grand_total');
        $revenuePrev = (int) $validPrev->sum('grand_total');
        $aov = $valid->count() ? intdiv($revenue, $valid->count()) : 0;
        $aovPrev = $validPrev->count() ? intdiv($revenuePrev, $validPrev->count()) : 0;

        $finished = fn (Collection $c) => $c->whereIn('status', ['delivered', 'cancelled', 'returned', 'failed'])->count();
        $delivered = $orders->where('status', 'delivered')->count();
        $lost = fn (Collection $c) => $c->whereIn('status', ['returned', 'failed'])->count();

        return [
            'orders' => [
                'value' => $orders->count(), 'change' => $this->pct($orders->count(), $prev->count()),
                'in_fulfillment' => $orders->whereIn('status', ['confirmed', 'processing', 'packed'])->count(),
            ],
            'revenue' => [
                'value' => $revenue, 'change' => $this->pct($revenue, $revenuePrev),
                'cod_share' => $valid->count() ? (int) round($valid->where('payment_method', 'cod')->count() / $valid->count() * 100) : null,
            ],
            'aov' => ['value' => $aov, 'change' => $this->pct($aov, $aovPrev)],
            'pending' => ['value' => Order::query()->where('status', 'pending')->count()],
            'delivered' => [
                'value' => $delivered,
                'success_rate' => $finished($orders) ? (int) round($delivered / $finished($orders) * 100) : null,
            ],
            'returns' => [
                'value' => $lost($orders),
                'rate' => $orders->count() ? round($lost($orders) / $orders->count() * 100, 1) : 0,
                'prev_rate' => $prev->count() ? round($lost($prev) / $prev->count() * 100, 1) : 0,
            ],
        ];
    }

    /** Things staff should act on today (always "now", independent of the date range). */
    private function triage(): array
    {
        $lowStock = $this->lowStockQuery();

        return [
            ['key' => 'pending', 'count' => Order::query()->where('status', 'pending')->count(), 'title' => 'Orders awaiting confirmation', 'hint' => 'Call to confirm before packing', 'cta' => 'Review now', 'url' => '/admin/orders?status=pending', 'tone' => 'amber'],
            ['key' => 'risk', 'count' => Order::query()->whereNotNull('risk_flags')->whereIn('status', ['pending', 'confirmed', 'on_hold'])->count(), 'title' => 'High-risk orders', 'hint' => 'Flagged for duplicate / risky details', 'cta' => 'Resolve flags', 'url' => '/admin/orders', 'tone' => 'rust'],
            ['key' => 'ship', 'count' => Order::query()->whereIn('status', ['confirmed', 'processing', 'packed'])->count(), 'title' => 'Ready to ship', 'hint' => 'Confirmed and waiting for a courier', 'cta' => 'Book couriers', 'url' => '/admin/orders', 'tone' => 'slate'],
            ['key' => 'hold', 'count' => Order::query()->where('status', 'on_hold')->count(), 'title' => 'Orders on hold', 'hint' => 'Need a decision from staff', 'cta' => 'Review', 'url' => '/admin/orders?status=on_hold', 'tone' => 'rust'],
            ['key' => 'stock', 'count' => (clone $lowStock)->count(), 'title' => 'Low or out-of-stock items', 'hint' => 'Restock before they sell out', 'cta' => 'Adjust stock', 'url' => '/admin/inventory', 'tone' => 'amber'],
        ];
    }

    private function lowStockQuery()
    {
        return InventoryItem::query()->where('track_stock', true)
            ->whereRaw('(on_hand - reserved) <= low_stock_threshold');
    }

    private function chart(Collection $orders, array $range): array
    {
        $valid = $this->valid($orders);
        $hourly = $range['hourly'];
        $labels = [];
        $slots = [];

        if ($hourly) {
            foreach (range(0, 23) as $h) {
                $slots[sprintf('%02d', $h)] = ['gross' => 0, 'realized' => 0, 'discounts' => 0];
                $labels[] = sprintf('%02d:00', $h);
            }
        } else {
            for ($d = $range['from']; $d->lte($range['to']); $d = $d->addDay()) {
                $slots[$d->format('Y-m-d')] = ['gross' => 0, 'realized' => 0, 'discounts' => 0];
                $labels[] = $d->format('d M');
            }
        }

        foreach ($valid as $o) {
            $at = CarbonImmutable::parse($o->placed_at ?? $o->created_at)->setTimezone(self::TZ);
            $k = $hourly ? $at->format('H') : $at->format('Y-m-d');
            if (isset($slots[$k])) {
                $slots[$k]['gross'] += $o->grand_total;
                $slots[$k]['realized'] += $o->paid_total;
                $slots[$k]['discounts'] += $o->discount_total;
            }
        }

        $gross = array_column($slots, 'gross');
        $total = array_sum($gross);
        $bestIdx = $total > 0 ? array_search(max($gross), $gross, true) : null;

        return [
            'granularity' => $hourly ? 'hour' : 'day',
            'labels' => $labels,
            'gross' => array_values($gross),
            'realized' => array_column(array_values($slots), 'realized'),
            'discounts' => array_column(array_values($slots), 'discounts'),
            'totals' => ['gross' => $total, 'realized' => array_sum(array_column($slots, 'realized')), 'discounts' => array_sum(array_column($slots, 'discounts'))],
            'best' => $bestIdx === null ? null : ['label' => $labels[$bestIdx], 'amount' => $gross[$bestIdx], 'share' => (int) round($gross[$bestIdx] / $total * 100)],
        ];
    }

    /**
     * Carts are deleted when an order is placed, so carts that still exist are abandoned ones:
     * "started" = abandoned carts + web orders placed in the window.
     */
    private function funnel(Collection $orders, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $webOrders = $orders->where('source', 'web')->count();
        $abandoned = Cart::query()->whereBetween('created_at', [$from->utc(), $to->utc()])->whereHas('items')->count();
        $started = $abandoned + $webOrders;
        $delivered = $orders->where('status', 'delivered')->count();

        return [
            'steps' => [
                ['label' => 'Carts started', 'count' => $started, 'pct' => 100],
                ['label' => 'Orders placed', 'count' => $orders->count(), 'pct' => $started ? min(100, (int) round($orders->count() / $started * 100)) : 0],
                ['label' => 'Orders delivered', 'count' => $delivered, 'pct' => $started ? min(100, (int) round($delivered / $started * 100)) : 0],
            ],
            'abandoned' => $abandoned,
            'abandonment_rate' => $started ? round($abandoned / $started * 100, 1) : 0,
        ];
    }

    private function pipeline(): array
    {
        $c = Order::query()->select('status', DB::raw('count(*) as n'))->groupBy('status')->pluck('n', 'status');

        return [
            'total' => (int) $c->sum(),
            'stages' => [
                ['key' => 'pending', 'label' => 'Pending', 'count' => (int) ($c['pending'] ?? 0)],
                ['key' => 'confirmed', 'label' => 'Confirmed', 'count' => (int) ($c['confirmed'] ?? 0)],
                ['key' => 'processing', 'label' => 'Processing', 'count' => (int) ($c['processing'] ?? 0)],
                ['key' => 'packed', 'label' => 'Packed', 'count' => (int) ($c['packed'] ?? 0)],
                ['key' => 'shipped', 'label' => 'In transit', 'count' => (int) ($c['shipped'] ?? 0)],
                ['key' => 'delivered', 'label' => 'Delivered', 'count' => (int) ($c['delivered'] ?? 0)],
                ['key' => 'returned', 'label' => 'Cancelled / returned', 'count' => (int) (($c['cancelled'] ?? 0) + ($c['returned'] ?? 0) + ($c['failed'] ?? 0))],
            ],
        ];
    }

    private function topProducts(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween(DB::raw('COALESCE(orders.placed_at, orders.created_at)'), [$from->utc(), $to->utc()])
            ->whereNotIn('orders.status', self::LOST)
            ->whereNotNull('order_items.variant_id')
            ->groupBy('order_items.variant_id')
            ->orderByDesc(DB::raw('SUM(order_items.qty)'))
            ->limit(5)
            ->get(['order_items.variant_id', DB::raw('SUM(order_items.qty) as units'), DB::raw('SUM(order_items.line_total) as revenue')]);

        $variants = ProductVariant::query()->with(['product.media.media', 'inventoryItem'])->whereIn('id', $rows->pluck('variant_id'))->get()->keyBy('id');

        return $rows->map(function ($r) use ($variants) {
            $v = $variants[$r->variant_id] ?? null;
            $avail = $v?->inventoryItem?->available();

            return [
                'name' => $v?->product?->name['bn'] ?? $v?->product?->name['en'] ?? '—',
                'variant' => $v ? ($v->weight_grams ? ($v->weight_grams >= 1000 ? rtrim(rtrim(number_format($v->weight_grams / 1000, 1), '0'), '.').' kg' : $v->weight_grams.' g') : $v->sku) : null,
                'image' => $v?->product?->primaryImageUrl(),
                'units' => (int) $r->units,
                'revenue' => (int) $r->revenue,
                'stock' => $avail,
                'stock_state' => $avail === null ? null : ($avail <= 0 ? 'out' : ($avail <= ($v->inventoryItem->low_stock_threshold ?? 5) ? 'low' : 'ok')),
                'url' => $v ? '/admin/products/'.$v->product_id.'/edit' : null,
            ];
        })->all();
    }

    private function stockAlerts(): array
    {
        $items = $this->lowStockQuery()->with('variant.product')->get()
            ->sortBy(fn (InventoryItem $i) => $i->available())->take(4)->values();

        $stock = $items->map(fn (InventoryItem $i) => [
            'name' => $i->variant?->product?->name['bn'] ?? $i->variant?->sku,
            'variant' => $i->variant?->weight_grams ? ($i->variant->weight_grams >= 1000 ? rtrim(rtrim(number_format($i->variant->weight_grams / 1000, 1), '0'), '.').' kg' : $i->variant->weight_grams.' g') : null,
            'available' => $i->available(), 'threshold' => $i->low_stock_threshold,
            'state' => $i->available() <= 0 ? 'out' : 'low',
        ])->all();

        $expiring = InventoryBatch::query()->with('inventoryItem.variant.product')
            ->where('qty_on_hand', '>', 0)->whereNotNull('expiry_date')
            ->whereDate('expiry_date', '<=', now()->addDays(60))
            ->orderBy('expiry_date')->limit(3)->get()
            ->map(fn (InventoryBatch $b) => [
                'name' => $b->inventoryItem?->variant?->product?->name['bn'] ?? '—',
                'batch_no' => $b->batch_no, 'qty' => $b->qty_on_hand,
                'expiry' => $b->expiry_date->format('d M Y'), 'days_left' => (int) now()->startOfDay()->diffInDays($b->expiry_date, false),
            ])->all();

        return ['stock' => $stock, 'expiring' => $expiring];
    }

    private function payments(Collection $orders): array
    {
        $valid = $this->valid($orders);
        $labels = ['cod' => 'Cash on Delivery', 'bkash' => 'bKash', 'nagad' => 'Nagad', 'card' => 'Card', 'sslcommerz' => 'SSLCommerz'];
        $revenue = max(1, (int) $valid->sum('grand_total'));

        return [
            'methods' => $valid->groupBy(fn ($o) => $o->payment_method ?: 'other')->map(fn ($g, $m) => [
                'method' => $m, 'label' => $labels[$m] ?? ucfirst($m), 'orders' => $g->count(),
                'amount' => (int) $g->sum('grand_total'), 'collected' => (int) $g->sum('paid_total'), 'due' => (int) $g->sum('due_total'),
                'share' => (int) round($g->sum('grand_total') / $revenue * 100),
            ])->sortByDesc('amount')->values()->all(),
            'collected' => (int) $valid->sum('paid_total'),
            'outstanding' => (int) $valid->sum('due_total'),
        ];
    }

    /** CSV rows for the dashboard "Export report" button. */
    public function exportRows(array $range): \Generator
    {
        yield ['Order', 'Placed (Dhaka)', 'Customer', 'Phone', 'District', 'Status', 'Payment method', 'Payment status', 'Subtotal', 'Discount', 'Delivery', 'Total', 'Paid', 'Due'];

        $q = Order::query()->whereBetween(DB::raw('COALESCE(placed_at, created_at)'), [$range['from']->utc(), $range['to']->utc()])->orderBy('placed_at');

        foreach ($q->cursor() as $o) {
            $a = $o->shipping_address ?? [];
            yield [
                $o->order_no, CarbonImmutable::parse($o->placed_at ?? $o->created_at)->setTimezone(self::TZ)->format('Y-m-d H:i'),
                $a['name'] ?? '', $a['phone'] ?? '', $a['district'] ?? '', $o->status, $o->payment_method, $o->payment_status,
                $o->subtotal / 100, $o->discount_total / 100, $o->delivery_fee / 100, $o->grand_total / 100, $o->paid_total / 100, $o->due_total / 100,
            ];
        }
    }
}
