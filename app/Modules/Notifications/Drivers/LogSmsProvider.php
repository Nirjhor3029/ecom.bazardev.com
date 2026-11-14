<?php

namespace App\Modules\Notifications\Drivers;

use App\Modules\Notifications\Contracts\SmsProvider;
use Illuminate\Support\Facades\Log;

/**
 * ARC-5: SMS sits behind an adapter. This driver just logs — swap it for
 * a real BD provider (open question SRS §11.1) by binding SmsProvider to
 * a new class in a service provider; nothing else in the app changes.
 */
class LogSmsProvider implements SmsProvider
{
    public function send(string $phone, string $message): array
    {
        Log::channel('stack')->info("[SMS:LOG] to={$phone} message=\"{$message}\"");

        return ['ok' => true, 'provider_ref' => 'log-'.uniqid(), 'error' => null];
    }
}
