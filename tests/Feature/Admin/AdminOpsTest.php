<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Inventory\Models\InventoryItem;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOpsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Admin');
    }

    public function test_admin_can_block_and_unblock_a_customer(): void
    {
        $customer = Customer::query()->create(['phone' => '+8801911112222', 'phone_verified_at' => now()]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/customers/{$customer->id}/block")
            ->assertOk()->assertJsonPath('data.is_blocked', true);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/customers/{$customer->id}/unblock")
            ->assertOk()->assertJsonPath('data.is_blocked', false);
    }

    public function test_admin_can_adjust_stock_and_it_is_logged(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create(['attribute_set_id' => $set->id, 'name' => ['bn' => 'x'], 'slug' => 'x', 'type' => 'simple']);
        $variant = $product->variants()->create(['sku' => 'ADJ-1', 'price' => 1000]);
        $item = InventoryItem::query()->create(['variant_id' => $variant->id, 'on_hand' => 10]);

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/inventory/{$item->id}/adjust", ['delta' => -3, 'reason' => 'damaged'])
            ->assertOk()->assertJsonPath('data.on_hand', 7);

        $this->assertDatabaseHas('stock_movements', ['inventory_item_id' => $item->id, 'type' => 'adjust', 'qty' => -3, 'reason' => 'damaged']);
    }

    public function test_admin_can_update_a_setting_and_toggle_a_feature_flag(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->patchJson('/api/v1/admin/settings/store.name', ['value' => 'New Name'])
            ->assertOk()->assertJsonPath('data.value', 'New Name');

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson('/api/v1/admin/features/reviews', ['enabled' => false])
            ->assertOk()->assertJsonPath('data.enabled', false)->assertJsonPath('data.source', 'admin');
    }
}
