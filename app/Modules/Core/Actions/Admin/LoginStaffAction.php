<?php

namespace App\Modules\Core\Actions\Admin;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginStaffAction
{
    /**
     * @return array{user: User, token: string}
     */
    public function execute(string $email, string $password, ?string $deviceName = null): array
    {
        $user = User::query()->where('email', $email)->first();

        if (! $user || ! $user->is_active || ! \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        $user->forceFill(['last_login_at' => now()])->save();

        $token = $user->createToken($deviceName ?: 'admin-api');

        return ['user' => $user, 'token' => $token->plainTextToken];
    }
}
