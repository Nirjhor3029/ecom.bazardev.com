<?php

namespace Database\Seeders;

use App\Modules\Catalog\Models\ProductVariant;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryBatch;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * ~5 weeks of believable orders so the admin dashboard has something to show.
 * Deterministic (fixed RNG seed) and re-runnable: its own rows are tagged note_internal = 'demo-seed'.
 * Needs the catalog first:  php artisan db:seed --class=DemoCatalogSeeder
 */
class DemoOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $variants = ProductVariant::query()->with('product')->where('is_active', true)->get();

        if ($variants->isEmpty()) {
            $this->command?->warn('No products found — run DemoCatalogSeeder first.');

            return;
        }

        Order::query()->where('note_internal', 'demo-seed')->delete();
        mt_srand(2611);

        $people = [
            ['রাকিবুল হাসান', '01711000001', 'Dhaka', 'ধানমন্ডি'], ['তানজিলা আহমেদ', '01711000002', 'Chattogram', 'পাঁচলাইশ'],
            ['মাহমুদুল করিম', '01711000003', 'Dhaka', 'উত্তরা'], ['সুমাইয়া ইসলাম', '01711000004', 'Sylhet', 'সিলেট সদর'],
            ['আরিফুর রহমান', '01711000005', 'Rajshahi', 'বোয়ালিয়া'], ['নাজমুন নাহার', '01711000006', 'Khulna', 'সোনাডাঙ্গা'],
            ['ফারহান সাদিক', '01711000007', 'Dhaka', 'মিরপুর'], ['শারমিন সুলতানা', '01711000008', 'Bogura', 'বগুড়া সদর'],
            ['তাহমিদ আহমেদ', '01711000009', 'Gazipur', 'টঙ্গী'], ['ইমরান হোসেন', '01711000010', 'Cumilla', 'কান্দিরপাড়'],
            ['নুসরাত জাহান', '01711000011', 'Dhaka', 'গুলশান'], ['সাদিয়া ইসলাম', '01711000012', 'Dhaka', 'বনানী'],
        ];
        $customers = collect($people)->map(fn ($p) => Customer::query()->firstOrCreate(['phone' => '+88'.$p[1]], ['name' => $p[0], 'phone_verified_at' => now()]));

        $seq = Order::query()->count();
        $now = now();

        for ($daysAgo = 34; $daysAgo >= 0; $daysAgo--) {
            $perDay = $daysAgo === 0 ? mt_rand(3, 5) : mt_rand(2, 6);

            for ($n = 0; $n < $perDay; $n++) {
                $at = $now->copy()->subDays($daysAgo)->setTime(mt_rand(8, 22), mt_rand(0, 59));
                if ($at->gt($now)) {
                    $at = $now->copy()->subMinutes(mt_rand(5, 90));
                }

                $idx = mt_rand(0, count($people) - 1);
                [$name, $phone, $district, $area] = $people[$idx];
                $status = $this->status($daysAgo);
                $lines = $variants->random(mt_rand(1, 3))->map(fn ($v) => ['v' => $v, 'qty' => mt_rand(1, 3)]);
                $subtotal = (int) $lines->sum(fn ($l) => $l['v']->effectivePrice() * $l['qty']);
                $coupon = mt_rand(1, 100) <= 18 && $subtotal >= 100000;
                $discount = $coupon ? min((int) round($subtotal * 0.10), 50000) : 0;
                $delivery = $district === 'Dhaka' ? ($subtotal - $discount >= 100000 ? 0 : 6000) : 12000;
                $grand = $subtotal - $discount + $delivery;
                $paid = $status === 'delivered' ? $grand : 0;

                $order = new Order([
                    'order_no' => 'ORD-'.$at->format('ym').'-'.str_pad((string) (++$seq), 6, '0', STR_PAD_LEFT),
                    'customer_id' => $customers[$idx]->id,
                    'status' => $status,
                    'payment_status' => $paid ? 'paid' : 'unpaid',
                    'fulfillment_status' => in_array($status, ['shipped', 'delivered'], true) ? 'fulfilled' : 'unfulfilled',
                    'subtotal' => $subtotal, 'discount_total' => $discount, 'delivery_fee' => $delivery, 'tax_total' => 0,
                    'grand_total' => $grand, 'paid_total' => $paid, 'due_total' => $grand - $paid,
                    'currency' => 'BDT', 'payment_method' => 'cod', 'coupon_code' => $coupon ? 'HONEY10' : null,
                    'shipping_address' => ['name' => $name, 'phone' => '+88'.$phone, 'district' => $district, 'area' => $area, 'address_line' => 'বাড়ি #'.mt_rand(1, 40).', রোড #'.mt_rand(1, 12)],
                    'note_internal' => 'demo-seed', 'source' => 'web', 'placed_at' => $at, 'idempotency_key' => (string) Str::uuid(),
                    'risk_flags' => ($daysAgo <= 2 && in_array($status, ['pending', 'on_hold'], true) && mt_rand(0, 2) === 0) ? ['duplicate_address'] : null,
                ]);
                $order->timestamps = false;
                $order->forceFill(['created_at' => $at, 'updated_at' => $at])->save();

                foreach ($lines as $l) {
                    OrderItem::query()->create([
                        'order_id' => $order->id, 'product_id' => $l['v']->product_id, 'variant_id' => $l['v']->id,
                        'name_snapshot' => $l['v']->product->name, 'sku' => $l['v']->sku, 'options' => $l['v']->options,
                        'unit_price' => $l['v']->effectivePrice(), 'qty' => $l['qty'], 'line_total' => $l['v']->effectivePrice() * $l['qty'],
                        'weight_grams' => $l['v']->weight_grams,
                    ]);
                }
            }
        }

        $this->stockStory($variants);
        $this->command?->info('Demo orders: '.Order::query()->where('note_internal', 'demo-seed')->count());
    }

    /** Older orders are mostly finished; the newest ones are still moving through the pipeline. */
    private function status(int $daysAgo): string
    {
        $roll = mt_rand(1, 100);

        return match (true) {
            $daysAgo > 5 => $roll <= 80 ? 'delivered' : ($roll <= 88 ? 'cancelled' : ($roll <= 96 ? 'returned' : 'failed')),
            $daysAgo >= 2 => $roll <= 35 ? 'delivered' : ($roll <= 65 ? 'shipped' : ($roll <= 75 ? 'packed' : ($roll <= 85 ? 'processing' : ($roll <= 93 ? 'cancelled' : 'on_hold')))),
            default => $roll <= 40 ? 'pending' : ($roll <= 65 ? 'confirmed' : ($roll <= 80 ? 'processing' : ($roll <= 90 ? 'packed' : 'on_hold'))),
        };
    }

    /** A couple of low / out-of-stock items and expiring batches so the alerts panel is not empty. */
    private function stockStory($variants): void
    {
        foreach (['HNY-KHA-250' => 4, 'NUT-CSH-500' => 0, 'HNY-LIT-1000' => 3] as $sku => $onHand) {
            if ($v = $variants->firstWhere('sku', $sku)) {
                InventoryItem::query()->where('variant_id', $v->id)->update(['on_hand' => $onHand, 'reserved' => 0]);
            }
        }

        foreach ([['HNY-KHA-500', 'HN-2024-B3', 12, 28], ['HNY-MUS-500', 'MS-2024-B1', 9, 52]] as [$sku, $batch, $qty, $days]) {
            $v = $variants->firstWhere('sku', $sku);
            $item = $v ? InventoryItem::query()->where('variant_id', $v->id)->first() : null;
            if ($item) {
                InventoryBatch::query()->updateOrCreate(
                    ['inventory_item_id' => $item->id, 'batch_no' => $batch],
                    ['qty_on_hand' => $qty, 'mfg_date' => now()->subMonths(10), 'expiry_date' => now()->addDays($days), 'received_at' => now()->subMonths(10)],
                );
            }
        }
    }
}
