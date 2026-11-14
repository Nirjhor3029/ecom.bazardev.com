<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Inventory\Models\InventoryItem;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/** Admin dashboard API: KPIs, pipeline, top products, stock alerts, auth and CSV export. */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Admin');
    }

    private function order(string $status, int $total, array $extra = []): Order
    {
        $order = new Order(array_merge([
            'order_no' => 'T-'.Str::random(8), 'status' => $status, 'payment_status' => $status === 'delivered' ? 'paid' : 'unpaid',
            'fulfillment_status' => 'unfulfilled', 'subtotal' => $total, 'discount_total' => 0, 'delivery_fee' => 0, 'tax_total' => 0,
            'grand_total' => $total, 'paid_total' => $status === 'delivered' ? $total : 0, 'due_total' => $status === 'delivered' ? 0 : $total,
            'currency' => 'BDT', 'payment_method' => 'cod', 'source' => 'web', 'placed_at' => now(), 'idempotency_key' => (string) Str::uuid(),
            'shipping_address' => ['name' => 'টেস্ট', 'phone' => '+8801711111111', 'district' => 'Dhaka'],
        ], $extra));
        $order->save();

        return $order;
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->getJson('/api/v1/admin/dashboard')->assertUnauthorized();
    }

    public function test_kpis_exclude_lost_orders_from_revenue_and_count_pipeline_states(): void
    {
        $this->order('delivered', 100000);
        $this->order('pending', 50000);
        $this->order('cancelled', 900000); // never counts as revenue
        $this->order('returned', 700000);

        $data = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/admin/dashboard?range=7d')->assertOk()->json('data');

        $this->assertSame(4, $data['kpis']['orders']['value']);
        $this->assertSame(150000, $data['kpis']['revenue']['value']);   // delivered + pending only
        $this->assertSame(75000, $data['kpis']['aov']['value']);
        $this->assertSame(1, $data['kpis']['pending']['value']);
        $this->assertSame(1, $data['kpis']['delivered']['value']);
        $this->assertSame(33, $data['kpis']['delivered']['success_rate']); // 1 delivered of 3 finished
        $this->assertSame(1, $data['kpis']['returns']['value']);

        $stages = collect($data['pipeline']['stages'])->pluck('count', 'key');
        $this->assertSame(1, $stages['pending']);
        $this->assertSame(1, $stages['delivered']);
        $this->assertSame(2, $stages['returned']); // cancelled + returned
        $this->assertSame(4, $data['pipeline']['total']);
    }

    public function test_orders_outside_the_selected_range_are_ignored(): void
    {
        $this->order('delivered', 100000, ['placed_at' => now()->subDays(20)]);

        $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/admin/dashboard?range=7d')
            ->assertOk()->assertJsonPath('data.kpis.orders.value', 0);
        $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/admin/dashboard?range=30d')
            ->assertOk()->assertJsonPath('data.kpis.orders.value', 1);
    }

    public function test_top_products_and_stock_alerts_come_from_real_data(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create(['attribute_set_id' => $set->id, 'name' => ['bn' => 'খলিশা মধু'], 'slug' => 'k', 'type' => 'simple', 'status' => 'active']);
        $variant = $product->variants()->create(['sku' => 'K-1', 'price' => 50000, 'weight_grams' => 500, 'is_default' => true]);
        InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 2, 'low_stock_threshold' => 5]);

        $order = $this->order('confirmed', 100000);
        OrderItem::query()->create([
            'order_id' => $order->id, 'product_id' => $product->id, 'variant_id' => $variant->id, 'name_snapshot' => ['bn' => 'খলিশা মধু'],
            'sku' => 'K-1', 'unit_price' => 50000, 'qty' => 2, 'line_total' => 100000, 'weight_grams' => 500,
        ]);

        $data = $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/admin/dashboard')->assertOk()->json('data');

        $this->assertSame('খলিশা মধু', $data['top_products'][0]['name']);
        $this->assertSame(2, $data['top_products'][0]['units']);
        $this->assertSame('low', $data['top_products'][0]['stock_state']);
        $this->assertSame('low', $data['stock_alerts']['stock'][0]['state']);
        $this->assertSame(1, collect($data['triage'])->firstWhere('key', 'stock')['count']);
    }

    public function test_export_streams_a_csv_with_the_orders_in_range(): void
    {
        $o = $this->order('delivered', 123400);

        $res = $this->actingAs($this->admin, 'sanctum')->get('/api/v1/admin/dashboard/export?range=7d')->assertOk();

        $csv = $res->streamedContent();
        $this->assertStringContainsString('Order,', $csv);
        $this->assertStringContainsString($o->order_no, $csv);
        $this->assertStringContainsString('1234', $csv);
        $this->assertStringContainsString('text/csv', $res->headers->get('Content-Type'));
    }

    public function test_orders_list_exposes_item_counts_for_the_recent_orders_table(): void
    {
        $order = $this->order('pending', 100000);
        OrderItem::query()->create(['order_id' => $order->id, 'name_snapshot' => ['bn' => 'x'], 'sku' => 'A', 'unit_price' => 1000, 'qty' => 3, 'line_total' => 3000]);

        $this->actingAs($this->admin, 'sanctum')->getJson('/api/v1/admin/orders?per_page=5')
            ->assertOk()->assertJsonPath('data.0.items_count', 1)->assertJsonPath('data.0.qty_total', 3);
    }
}
