<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Device;
use App\Models\Message;
use App\Models\User;

class SmsService
{
    public function send(User $user, string $phone, string $body, ?int $deviceId = null, ?int $contactId = null, ?int $campaignId = null): Message
    {
        if ($user->hasReachedSmsLimit()) {
            throw new \Exception('SMS limit reached. Upgrade your plan.');
        }

        $device = $deviceId
            ? $user->devices()->where('id', $deviceId)->where('is_active', true)->firstOrFail()
            : $user->devices()->where('status', 'online')->where('is_active', true)->first();

        if (!$device) {
            throw new \Exception('No active device available.');
        }

        $message = Message::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'contact_id' => $contactId,
            'campaign_id' => $campaignId,
            'phone_to' => $phone,
            'phone_from' => $device->phone_number,
            'body' => $body,
            'status' => 'pending',
            'direction' => 'outgoing',
        ]);

        $user->incrementSmsUsed();

        return $message;
    }

    public function sendToGroup(User $user, int $contactGroupId, string $body, ?int $deviceId = null, ?int $campaignId = null): array
    {
        $contacts = Contact::where('user_id', $user->id)
            ->where('contact_group_id', $contactGroupId)
            ->get();

        $messages = [];
        foreach ($contacts as $contact) {
            try {
                $messages[] = $this->send($user, $contact->phone, $body, $deviceId, $contact->id, $campaignId);
            } catch (\Exception $e) {
                break;
            }
        }

        return $messages;
    }
}
