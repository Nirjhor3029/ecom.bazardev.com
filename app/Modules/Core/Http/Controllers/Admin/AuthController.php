<?php

namespace App\Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Core\Actions\Admin\LoginStaffAction;
use App\Modules\Core\Http\Requests\Admin\LoginRequest;
use App\Modules\Core\Http\Resources\Admin\StaffUserResource;
use App\Modules\Core\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginStaffAction $action): JsonResponse
    {
        $result = $action->execute(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $request->string('device_name')->toString() ?: $request->userAgent(),
        );

        return response()->json([
            'data' => [
                'user' => new StaffUserResource($result['user']),
                'token' => $result['token'],
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new StaffUserResource($request->user()),
        ]);
    }

    /** Revokes every token for this user, not just the one used here — a stray
     *  token from an old tab/device should never keep behaving as "logged in". */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(status: 204);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'current_password' => ['sometimes', 'string'],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->filled('password')) {
            if (! $request->filled('current_password') || ! Hash::check($request->string('current_password'), $user->password)) {
                throw ValidationException::withMessages(['current_password' => ['Current password is incorrect.']]);
            }

            $user->password = $request->string('password')->toString();
        }

        $user->fill($request->only(['name', 'phone']))->save();

        return response()->json(['data' => new StaffUserResource($user)]);
    }

    public function uploadAvatar(Request $request, MediaService $mediaService): JsonResponse
    {
        $request->validate(['file' => ['required', 'image', 'max:5120']]);

        $user = $request->user();
        $media = $mediaService->upload($request->file('file'), $user->id);
        $user->update(['avatar_media_id' => $media->id]);

        return response()->json(['data' => new StaffUserResource($user)]);
    }
}
