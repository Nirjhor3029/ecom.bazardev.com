<?php

namespace App\Modules\Customers\Services;

use App\Modules\Notifications\Services\NotificationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

/**
 * SRS 5.2: 6-digit code, 5 min validity, max 3 verify attempts, redone via
 * `otp-send` rate limiter (3/10min per phone, 10/hr per IP — already wired
 * on the route). Stored in Redis, not the DB — it's a short-lived secret.
 */
class OtpService
{
    protected const int TTL_MINUTES = 5;

    protected const int MAX_ATTEMPTS = 3;

    public function __construct(protected NotificationService $notifications) {}

    public function send(string $phone): void
    {
        $code = (string) random_int(100000, 999999);

        Cache::put($this->key($phone), ['code' => $code, 'attempts' => 0], now()->addMinutes(self::TTL_MINUTES));

        $this->notifications->sendSms($phone, 'otp.code', ['code' => $code]);
    }

    public function verify(string $phone, string $code): bool
    {
        $key = $this->key($phone);
        $entry = Cache::get($key);

        if (! $entry) {
            throw ValidationException::withMessages(['code' => ['OTP expired or not requested. Please request a new one.']]);
        }

        if ($entry['attempts'] >= self::MAX_ATTEMPTS) {
            Cache::forget($key);

            throw ValidationException::withMessages(['code' => ['Too many incorrect attempts. Please request a new OTP.']]);
        }

        if (! hash_equals($entry['code'], $code)) {
            Cache::put($key, ['code' => $entry['code'], 'attempts' => $entry['attempts'] + 1], now()->addMinutes(self::TTL_MINUTES));

            throw ValidationException::withMessages(['code' => ['Incorrect OTP.']]);
        }

        Cache::forget($key);

        return true;
    }

    protected function key(string $phone): string
    {
        return "otp:{$phone}";
    }
}
