<?php

namespace App\Modules\Notifications\Contracts;

interface SmsProvider
{
    /** @return array{ok: bool, provider_ref: ?string, error: ?string} */
    public function send(string $phone, string $message): array;
}
