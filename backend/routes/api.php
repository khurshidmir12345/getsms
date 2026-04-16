<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Middleware\DeviceAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Device registration (uses API key, not device token)
Route::post('/device/register', [DeviceController::class, 'register']);

// Device authenticated routes
Route::middleware(DeviceAuth::class)->group(function () {
    Route::post('/device/heartbeat', [DeviceController::class, 'heartbeat']);
    Route::post('/device/battery', [DeviceController::class, 'battery']);

    Route::get('/messages/pending', [MessageController::class, 'pending']);
    Route::post('/messages/{id}/status', [MessageController::class, 'updateStatus']);
    Route::post('/messages/incoming', [MessageController::class, 'incoming']);
    Route::post('/messages/bulk-status', [MessageController::class, 'bulkStatus']);
});

// External API (for third-party integrations via API key)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/sms/send', function (Request $request) {
        $request->validate([
            'to' => 'required|string|max:20',
            'body' => 'required|string|max:1600',
            'device_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        if ($user->hasReachedSmsLimit()) {
            return response()->json(['error' => 'SMS limit reached'], 429);
        }

        $device = $request->device_id
            ? $user->devices()->where('id', $request->device_id)->where('is_active', true)->first()
            : $user->devices()->where('status', 'online')->where('is_active', true)->first();

        if (!$device) {
            return response()->json(['error' => 'No active device available'], 422);
        }

        $message = $user->messages()->create([
            'device_id' => $device->id,
            'phone_to' => $request->to,
            'body' => $request->body,
            'status' => 'pending',
            'direction' => 'outgoing',
        ]);

        $user->incrementSmsUsed();

        return response()->json([
            'success' => true,
            'message_id' => $message->id,
            'status' => 'pending',
        ]);
    });

    Route::get('/sms/{id}/status', function (Request $request, int $id) {
        $message = $request->user()->messages()->findOrFail($id);
        return response()->json([
            'id' => $message->id,
            'status' => $message->status,
            'sent_at' => $message->sent_at,
            'delivered_at' => $message->delivered_at,
            'error_message' => $message->error_message,
        ]);
    });
});
