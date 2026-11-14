<?php

namespace App\Modules\Notifications\Services;

use App\Modules\Notifications\Contracts\SmsProvider;
use App\Modules\Notifications\Models\NotificationLog;
use App\Modules\Notifications\Models\NotificationTemplate;

/**
 * SRS 5.13: order/OTP lifecycle events fan out to SMS through a template
 * row (admin-editable) with a built-in fallback so the flow still works
 * before anyone has configured a template.
 */
class NotificationService
{
    protected array $fallbacks = [
        'otp.code' => 'Your Bazardev Ecom OTP is {code}. It expires in 5 minutes.',
        'order.placed' => 'Thank you! Your order {order_no} has been placed.',
        'order.confirmed' => 'Your order {order_no} is confirmed.',
        'order.shipped' => 'Your order {order_no} is on its way. Track: {tracking_url}',
        'order.delivered' => 'Your order {order_no} has been delivered. Thank you for shopping with us!',
        'order.cancelled' => 'Your order {order_no} has been cancelled.',
    ];

    public function __construct(protected SmsProvider $sms) {}

    public function sendSms(string $phone, string $templateKey, array $placeholders = []): void
    {
        $template = NotificationTemplate::query()
            ->where('key', $templateKey)->where('channel', 'sms')->where('is_active', true)
            ->first();

        $body = $template?->body ?? $this->fallbacks[$templateKey] ?? $templateKey;

        foreach ($placeholders as $key => $value) {
            $body = str_replace('{'.$key.'}', (string) $value, $body);
        }

        $result = $this->sms->send($phone, $body);

        NotificationLog::query()->create([
            'channel' => 'sms',
            'to' => $phone,
            'template' => $templateKey,
            'status' => $result['ok'] ? 'sent' : 'failed',
            'provider_ref' => $result['provider_ref'],
            'error' => $result['error'],
        ]);
    }
}
