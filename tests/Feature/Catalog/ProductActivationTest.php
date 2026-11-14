<?php

namespace Tests\Feature\Catalog;

use App\Models\User;
use App\Modules\Catalog\Models\AttributeSet;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductActivationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected AttributeSet $attributeSet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Admin');

        $this->attributeSet = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $this->attributeSet->attributeDefinitions()->create([
            'code' => 'origin', 'label' => ['bn' => 'উৎস'], 'type' => 'select',
            'is_required' => true, 'is_variant' => false,
        ]);
    }

    public function test_product_cannot_activate_without_a_variant(): void
    {
        $product = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/products', [
                'attribute_set_id' => $this->attributeSet->id,
                'name' => ['bn' => 'টেস্ট প্রোডাক্ট'],
                'type' => 'simple',
                'attributes' => ['origin' => 'সুন্দরবন'],
            ])->assertCreated()->json('data');

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/v1/admin/products/{$product['id']}", ['status' => 'active'])
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_FAILED');
    }

    public function test_product_cannot_activate_without_required_attribute(): void
    {
        $product = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/products', [
                'attribute_set_id' => $this->attributeSet->id,
                'name' => ['bn' => 'টেস্ট প্রোডাক্ট'],
                'type' => 'simple',
                'variants' => [['sku' => 'TST-1', 'price' => 10000]],
            ])->assertCreated()->json('data');

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/v1/admin/products/{$product['id']}", ['status' => 'active'])
            ->assertStatus(422);
    }

    public function test_product_activates_when_valid(): void
    {
        $product = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/products', [
                'attribute_set_id' => $this->attributeSet->id,
                'name' => ['bn' => 'টেস্ট প্রোডাক্ট'],
                'type' => 'simple',
                'attributes' => ['origin' => 'সুন্দরবন'],
                'variants' => [['sku' => 'TST-1', 'price' => 10000, 'is_default' => true]],
            ])->assertCreated()->json('data');

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/v1/admin/products/{$product['id']}", ['status' => 'active'])
            ->assertOk()
            ->assertJsonPath('data.status', 'active');
    }

    public function test_duplicate_variant_option_combination_is_rejected(): void
    {
        $product = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/products', [
                'attribute_set_id' => $this->attributeSet->id,
                'name' => ['bn' => 'টেস্ট'],
                'type' => 'variable',
                'variants' => [['sku' => 'TST-500', 'options' => ['weight' => '500g'], 'price' => 10000]],
            ])->assertCreated()->json('data');

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/products/{$product['id']}/variants", [
                'sku' => 'TST-500-DUP', 'options' => ['weight' => '500g'], 'price' => 11000,
            ])
            ->assertStatus(422);
    }
}
