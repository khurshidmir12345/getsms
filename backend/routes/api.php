<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Middleware\ApiKeyAuth;
use App\Http\Middleware\DeviceAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Device registration (uses API key, not device token)
Route::post('/device/register', [DeviceController::class, 'register']);

// Device authenticated routes (Flutter app)
Route::middleware(DeviceAuth::class)->group(function () {
    Route::post('/device/heartbeat', [DeviceController::class, 'heartbeat']);
    Route::post('/device/battery', [DeviceController::class, 'battery']);

    Route::get('/messages/pending', [MessageController::class, 'pending']);
    Route::post('/messages/{id}/status', [MessageController::class, 'updateStatus']);
    Route::post('/messages/incoming', [MessageController::class, 'incoming']);
    Route::post('/messages/bulk-status', [MessageController::class, 'bulkStatus']);
});

// ============================================================
// External API v1 — pDaftar va boshqa tizimlar uchun
// Auth: X-API-Key header YOKI Authorization: Bearer sk_...
// ============================================================
Route::middleware(ApiKeyAuth::class)->prefix('v1')->group(function () {

    // SMS yuborish (bitta)
    Route::post('/sms/send', function (Request $request) {
        $request->validate([
            'to' => 'required|string|max:20',
            'body' => 'required|string|max:1600',
            'device_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        if ($user->hasReachedSmsLimit()) {
            return response()->json(['success' => false, 'error' => 'SMS limit reached'], 429);
        }

        $device = $request->device_id
            ? $user->devices()->where('id', $request->device_id)->where('is_active', true)->first()
            : $user->devices()->where('status', 'online')->where('is_active', true)->first();

        if (!$device) {
            return response()->json(['success' => false, 'error' => 'No active device available'], 422);
        }

        $message = $user->messages()->create([
            'device_id' => $device->id,
            'phone_to' => $request->to,
            'phone_from' => $device->phone_number,
            'body' => $request->body,
            'status' => 'pending',
            'direction' => 'outgoing',
        ]);

        $user->incrementSmsUsed();

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
            'status' => 'pending',
            'phone_from' => $device->phone_number,
        ]);
    });

    // SMS yuborish (bir nechta — bulk)
    Route::post('/sms/send-bulk', function (Request $request) {
        $request->validate([
            'messages' => 'required|array|min:1|max:100',
            'messages.*.to' => 'required|string|max:20',
            'messages.*.body' => 'required|string|max:1600',
            'device_id' => 'nullable|integer',
        ]);

        $user = $request->user();
        $results = [];

        $device = $request->device_id
            ? $user->devices()->where('id', $request->device_id)->where('is_active', true)->first()
            : $user->devices()->where('status', 'online')->where('is_active', true)->first();

        if (!$device) {
            return response()->json(['success' => false, 'error' => 'No active device available'], 422);
        }

        foreach ($request->messages as $msg) {
            if ($user->hasReachedSmsLimit()) {
                $results[] = ['to' => $msg['to'], 'success' => false, 'error' => 'SMS limit reached'];
                continue;
            }

            $message = $user->messages()->create([
                'device_id' => $device->id,
                'phone_to' => $msg['to'],
                'phone_from' => $device->phone_number,
                'body' => $msg['body'],
                'status' => 'pending',
                'direction' => 'outgoing',
            ]);

            $user->incrementSmsUsed();

            $results[] = [
                'to' => $msg['to'],
                'success' => true,
                'message_id' => $message->id,
                'phone_from' => $device->phone_number,
            ];
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'total' => count($results),
        ]);
    });

    // SMS holati
    Route::get('/sms/{id}/status', function (Request $request, int $id) {
        $message = $request->user()->messages()->find($id);

        if (!$message) {
            return response()->json(['success' => false, 'error' => 'Message not found'], 404);
        }

        return response()->json([
            'success' => true,
            'id' => $message->id,
            'status' => $message->status,
            'phone_to' => $message->phone_to,
            'sent_at' => $message->sent_at?->toIso8601String(),
            'delivered_at' => $message->delivered_at?->toIso8601String(),
            'error_message' => $message->error_message,
        ]);
    });

    // Hisob ma'lumotlari
    Route::get('/account/info', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'name' => $user->name,
            'email' => $user->email,
            'sms_used' => $user->sms_used,
            'sms_limit' => $user->sms_limit,
            'sms_remaining' => max(0, $user->sms_limit - $user->sms_used),
            'devices_online' => $user->devices()->where('status', 'online')->count(),
            'devices_total' => $user->devices()->count(),
        ]);
    });

    // Webhook URL sozlash
    Route::post('/webhook/configure', function (Request $request) {
        $request->validate([
            'url' => 'nullable|url|max:500',
        ]);

        $user = $request->user();
        $user->update([
            'webhook_url' => $request->url,
        ]);

        return response()->json([
            'success' => true,
            'webhook_url' => $user->webhook_url,
        ]);
    });

    // Qurilmalar ro'yxati
    Route::get('/devices', function (Request $request) {
        $devices = $request->user()->devices()->get(['id', 'name', 'phone_number', 'operator', 'status', 'battery_level', 'last_seen_at']);

        return response()->json([
            'success' => true,
            'devices' => $devices,
        ]);
    });
});
