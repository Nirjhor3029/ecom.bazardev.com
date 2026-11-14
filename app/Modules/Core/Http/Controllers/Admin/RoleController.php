<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('roles.role.view');

        return response()->json([
            'data' => [
                'roles' => Role::query()->with('permissions')->get()->map(fn (Role $role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                    'users_count' => $role->users()->count(),
                ]),
                // grouped by module prefix (catalog.*, orders.*, ...) so the UI can render sections.
                'permissions' => Permission::query()->pluck('name')->groupBy(fn ($name) => explode('.', $name)[0]),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('roles.role.manage');

        $data = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:roles,name']]);

        // Explicit guard_name is required here: Laravel's `Authenticate`
        // middleware calls Auth::shouldUse('sanctum') on success, which
        // mutates config('auth.defaults.guard') for the rest of this
        // request — Role::create() without an explicit guard would silently
        // pick that up instead of 'web' (the only guard whose provider
        // actually maps to App\Models\User), breaking every assignRole()/
        // syncPermissions() call on the role afterwards.
        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        return response()->json(['data' => ['id' => $role->id, 'name' => $role->name, 'permissions' => []]], 201);
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $this->authorize('roles.role.manage');

        $data = $request->validate(['permissions' => ['present', 'array'], 'permissions.*' => ['string', 'exists:permissions,name']]);

        $role->syncPermissions($data['permissions']);

        return response()->json(['data' => ['id' => $role->id, 'name' => $role->name, 'permissions' => $role->permissions->pluck('name')]]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $this->authorize('roles.role.manage');

        if ($role->users()->exists()) {
            throw new ApiException('ROLE_IN_USE', 'Cannot delete a role that still has users assigned.', 409);
        }

        $role->delete();

        return response()->json(status: 204);
    }
}
