<?php

namespace App\Modules\Customers\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Modules\Core\Support\PhoneNumber;
use App\Modules\Customers\Models\Customer;
use App\Modules\Customers\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function sendOtp(Request $request, OtpService $otp): JsonResponse
    {
        $request->validate(['phone' => ['required', 'string']]);

        $phone = PhoneNumber::normalize($request->string('phone'));

        if (! $phone) {
            throw ValidationException::withMessages(['phone' => ['Not a valid Bangladeshi mobile number.']]);
        }

        $otp->send($phone);

        return response()->json(['data' => ['message' => 'OTP sent.', 'phone' => $phone]]);
    }

    public function verifyOtp(Request $request, OtpService $otp): JsonResponse
    {
        $request->validate(['phone' => ['required', 'string'], 'code' => ['required', 'string']]);

        $phone = PhoneNumber::normalize($request->string('phone'));

        if (! $phone) {
            throw ValidationException::withMessages(['phone' => ['Not a valid Bangladeshi mobile number.']]);
        }

        $otp->verify($phone, $request->string('code')->toString());

        $customer = Customer::query()->firstOrCreate(
            ['phone' => $phone],
            ['phone_verified_at' => now()],
        );

        if (! $customer->phone_verified_at) {
            $customer->update(['phone_verified_at' => now()]);
        }

        $token = $customer->createToken('customer-api')->plainTextToken;

        return response()->json(['data' => [
            'customer' => ['id' => $customer->id, 'name' => $customer->name, 'phone' => $customer->phone],
            'token' => $token,
            'token_type' => 'Bearer',
        ]]);
    }
}
