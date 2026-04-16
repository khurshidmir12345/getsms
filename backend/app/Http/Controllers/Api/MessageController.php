<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Services\WebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function pending(Request $request): JsonResponse
    {
        $device = $request->get('device');
        $limit = $request->input('limit', 10);

        $messages = Message::where('device_id', $device->id)
            ->where('status', 'pending')
            ->where('direction', 'outgoing')
            ->orderBy('created_at', 'asc')
            ->limit(min($limit, 50))
            ->get(['id', 'phone_to', 'body', 'created_at']);

        // Mark as queued
        Message::whereIn('id', $messages->pluck('id'))
            ->update(['status' => 'queued']);

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'count' => $messages->count(),
        ]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $device = $request->get('device');

        $request->validate([
            'status' => 'required|in:sending,sent,delivered,failed',
            'error_message' => 'nullable|string|max:500',
        ]);

        $message = Message::where('id', $id)
            ->where('device_id', $device->id)
            ->first();

        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        $data = ['status' => $request->status];

        if ($request->status === 'sent') {
            $data['sent_at'] = now();
        } elseif ($request->status === 'delivered') {
            $data['delivered_at'] = now();
        } elseif ($request->status === 'failed') {
            $data['error_message'] = $request->error_message;
        }

        $message->update($data);

        // Webhook — pDaftar va boshqa tizimlarga xabar berish
        if (in_array($request->status, ['sent', 'delivered', 'failed'])) {
            $message->load('user');
            app(WebhookService::class)->notifyStatusChange($message);
        }

        // Update campaign counters
        if ($message->campaign_id) {
            $campaign = $message->campaign;
            if ($campaign) {
                if ($request->status === 'sent' || $request->status === 'delivered') {
                    $campaign->increment('sent_count');
                    if ($request->status === 'delivered') {
                        $campaign->increment('delivered_count');
                    }
                } elseif ($request->status === 'failed') {
                    $campaign->increment('failed_count');
                }

                // Check if campaign is completed
                if (($campaign->sent_count + $campaign->failed_count) >= $campaign->total_messages) {
                    $campaign->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function incoming(Request $request): JsonResponse
    {
        $device = $request->get('device');

        $request->validate([
            'phone_from' => 'required|string|max:20',
            'body' => 'required|string',
            'received_at' => 'nullable|date',
        ]);

        $message = Message::create([
            'user_id' => $device->user_id,
            'device_id' => $device->id,
            'phone_to' => $device->phone_number ?? '',
            'phone_from' => $request->phone_from,
            'body' => $request->body,
            'status' => 'delivered',
            'direction' => 'incoming',
            'delivered_at' => $request->received_at ?? now(),
        ]);

        // Webhook — kiruvchi SMS haqida xabar berish
        $message->load('user');
        app(WebhookService::class)->notifyIncomingSms($message);

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
        ]);
    }

    public function bulkStatus(Request $request): JsonResponse
    {
        $device = $request->get('device');

        $request->validate([
            'statuses' => 'required|array|max:100',
            'statuses.*.id' => 'required|integer',
            'statuses.*.status' => 'required|in:sending,sent,delivered,failed',
            'statuses.*.error_message' => 'nullable|string|max:500',
        ]);

        foreach ($request->statuses as $item) {
            $message = Message::where('id', $item['id'])
                ->where('device_id', $device->id)
                ->first();

            if (!$message) continue;

            $data = ['status' => $item['status']];
            if ($item['status'] === 'sent') $data['sent_at'] = now();
            elseif ($item['status'] === 'delivered') $data['delivered_at'] = now();
            elseif ($item['status'] === 'failed') $data['error_message'] = $item['error_message'] ?? null;

            $message->update($data);
        }

        return response()->json(['success' => true]);
    }
}
