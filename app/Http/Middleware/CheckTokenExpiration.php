<?php

namespace App\Http\Middleware;

use App\Http\Resources\DataResource;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || Carbon::parse($accessToken->expires_at, 'Asia/Jakarta')->timestamp < Carbon::now('Asia/Jakarta')->timestamp) {
            return response()->json(['message' => 'Token expired.'], 401);
        }
        // return response()->json(new DataResource(200, Carbon::now('Asia/Jakarta')->timestamp, $accessToken), 401);
        return $next($request);
    }
}
