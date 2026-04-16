<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * SMS holati o'zgarganda foydalanuvchining webhook URL ga xabar yuborish
     */
    public function notifyStatusChange(Message $message): void
    {
        $user = $message->user;

        if (!$user || empty($user->webhook_url)) {
            return;
        }

        $payload = [
            'event' => 'sms.status_changed',
            'message_id' => $message->id,
            'status' => $message->status,
            'phone_to' => $message->phone_to,
            'body' => $message->body,
            'sent_at' => $message->sent_at?->toIso8601String(),
            'delivered_at' => $message->delivered_at?->toIso8601String(),
            'error_message' => $message->error_message,
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            $headers = ['Content-Type' => 'application/json'];

            // Webhook secret bo'lsa, signature qo'shish
            if (!empty($user->webhook_secret)) {
                $signature = hash_hmac('sha256', json_encode($payload), $user->webhook_secret);
                $headers['X-Webhook-Signature'] = $signature;
            }

            Http::timeout(5)
                ->withHeaders($headers)
                ->post($user->webhook_url, $payload);

        } catch (\Exception $e) {
            Log::warning('Webhook failed', [
                'user_id' => $user->id,
                'url' => $user->webhook_url,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Kiruvchi SMS haqida webhook yuborish
     */
    public function notifyIncomingSms(Message $message): void
    {
        $user = $message->user;

        if (!$user || empty($user->webhook_url)) {
            return;
        }

        $payload = [
            'event' => 'sms.incoming',
            'message_id' => $message->id,
            'phone_from' => $message->phone_from,
            'body' => $message->body,
            'received_at' => $message->delivered_at?->toIso8601String(),
            'timestamp' => now()->toIso8601String(),
        ];

        try {
            $headers = ['Content-Type' => 'application/json'];

            if (!empty($user->webhook_secret)) {
                $signature = hash_hmac('sha256', json_encode($payload), $user->webhook_secret);
                $headers['X-Webhook-Signature'] = $signature;
            }

            Http::timeout(5)
                ->withHeaders($headers)
                ->post($user->webhook_url, $payload);

        } catch (\Exception $e) {
            Log::warning('Webhook failed', [
                'user_id' => $user->id,
                'url' => $user->webhook_url,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
