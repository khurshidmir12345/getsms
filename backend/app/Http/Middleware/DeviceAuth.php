<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Device token required'], 401);
        }

        $device = Device::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$device) {
            return response()->json(['error' => 'Invalid device token'], 401);
        }

        $request->merge(['device' => $device]);
        $request->setUserResolver(fn () => $device->user);

        return $next($request);
    }
}
