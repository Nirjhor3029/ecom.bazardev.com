<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Core\Http\Resources\Admin\StaffUserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('roles.role.view');

        $query = User::query()->with('roles')->latest();

        if ($q = $request->string('q')->toString()) {
            $query->where(fn ($q2) => $q2->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }

        return $this->paginated($query->paginate($request->integer('per_page', 20)), StaffUserResource::class);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('roles.role.manage');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'is_active' => true,
        ]);

        $user->assignRole($data['role']);

        return response()->json(['data' => new StaffUserResource($user->load('roles'))], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('roles.role.manage');

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['sometimes', 'boolean'],
            'role' => ['sometimes', 'string', 'exists:roles,name'],
        ]);

        $user->fill($request->only(['name', 'phone', 'is_active']))->save();

        if (! empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return response()->json(['data' => new StaffUserResource($user->load('roles'))]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('roles.role.manage');

        if ($user->id === $request->user()->id) {
            throw new ApiException('CANNOT_DELETE_SELF', 'You cannot delete your own account.', 422);
        }

        $user->delete();

        return response()->json(status: 204);
    }
}
