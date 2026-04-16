<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        // Fallback to Bearer token if X-API-Key not provided
        if (!$apiKey) {
            $bearer = $request->bearerToken();
            if ($bearer && str_starts_with($bearer, 'sk_')) {
                $apiKey = $bearer;
            }
        }

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'API key required. Use X-API-Key header or Authorization: Bearer sk_...',
            ], 401);
        }

        $user = User::where('api_key', $apiKey)
            ->where('is_active', true)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API key',
            ], 401);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
