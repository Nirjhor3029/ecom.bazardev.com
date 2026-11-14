<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
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

    /**
     * Regression: Laravel's auth:sanctum middleware calls Auth::shouldUse('sanctum')
     * on success, which mutates config('auth.defaults.guard') for the rest of the
     * request. Role::create() without an explicit guard_name picks that up instead
     * of 'web' — the only guard whose provider actually maps to App\Models\User —
     * which silently breaks every assignRole()/syncPermissions() call on that role
     * afterwards ("no role/permission named X for guard Y").
     */
    public function test_a_role_created_through_the_authenticated_api_can_immediately_be_assigned_and_given_permissions(): void
    {
        $roleId = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/roles', ['name' => 'Packer'])
            ->assertCreated()
            ->json('data.id');

        $this->assertDatabaseHas('roles', ['id' => $roleId, 'name' => 'Packer', 'guard_name' => 'web']);

        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/v1/admin/roles/{$roleId}", ['permissions' => ['orders.order.view']])
            ->assertOk()
            ->assertJsonPath('data.permissions', ['orders.order.view']);

        $staff = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/users', [
                'name' => 'Packer One', 'email' => 'packer@example.com',
                'password' => 'password123', 'role' => 'Packer',
            ])
            ->assertCreated()
            ->json('data');

        $this->assertSame(['Packer'], $staff['roles']);
        $this->assertTrue($staff['is_active']);
    }

    public function test_a_role_still_assigned_to_a_user_cannot_be_deleted(): void
    {
        $roleId = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/roles', ['name' => 'Packer'])
            ->json('data.id');

        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/users', [
                'name' => 'Packer One', 'email' => 'packer@example.com',
                'password' => 'password123', 'role' => 'Packer',
            ])->assertCreated();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/roles/{$roleId}")
            ->assertStatus(409)->assertJsonPath('error.code', 'ROLE_IN_USE');
    }

    public function test_logout_revokes_every_token_for_that_user_not_just_the_current_one(): void
    {
        $this->admin->createToken('device-a');
        $this->admin->createToken('device-b');
        $this->assertSame(2, $this->admin->tokens()->count());

        // Asserted via DB state, not a second guarded HTTP call: Sanctum's
        // guard caches the resolved user on itself for the container's
        // lifetime, so a second request reusing the same test container
        // would return the cached user regardless of whether its token
        // was actually revoked — not a real signal here.
        $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/auth/logout')
            ->assertNoContent();

        $this->assertSame(0, $this->admin->tokens()->count());
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/users/{$this->admin->id}")
            ->assertStatus(422)->assertJsonPath('error.code', 'CANNOT_DELETE_SELF');
    }
}
