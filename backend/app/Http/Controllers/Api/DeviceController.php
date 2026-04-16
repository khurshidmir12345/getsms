<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'api_key' => 'required|string',
            'device_id' => 'required|string|max:128',
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'operator' => 'nullable|string|max:20',
            'sim_slots' => 'nullable|array',
            'model' => 'nullable|string|max:255',
            'android_version' => 'nullable|string|max:10',
        ]);

        $user = User::where('api_key', $request->api_key)
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $deviceCount = $user->devices()->count();
        $deviceLimit = $user->plan?->device_limit ?? 1;

        $device = Device::updateOrCreate(
            ['device_id' => $request->device_id],
            [
                'user_id' => $user->id,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'operator' => $request->operator,
                'sim_slots' => $request->sim_slots,
                'model' => $request->model,
                'android_version' => $request->android_version,
                'status' => 'online',
                'last_seen_at' => now(),
                'is_active' => true,
            ]
        );

        if (!$device->wasRecentlyCreated && $device->wasChanged()) {
            // Existing device updated
        } elseif ($device->wasRecentlyCreated && $deviceCount >= $deviceLimit) {
            $device->update(['is_active' => false]);
            return response()->json([
                'error' => "Device limit reached ({$deviceLimit}). Upgrade your plan.",
            ], 403);
        }

        return response()->json([
            'success' => true,
            'device_token' => $device->token,
            'device_id' => $device->id,
        ]);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $device = $request->get('device');

        $device->update([
            'status' => 'online',
            'last_seen_at' => now(),
            'battery_level' => $request->input('battery_level'),
            'signal_strength' => $request->input('signal_strength'),
        ]);

        return response()->json([
            'success' => true,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    public function battery(Request $request): JsonResponse
    {
        $device = $request->get('device');

        $device->update([
            'battery_level' => $request->input('battery_level'),
            'signal_strength' => $request->input('signal_strength'),
        ]);

        return response()->json(['success' => true]);
    }
}
