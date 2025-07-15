<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JwtService;

class JwtAuthController extends Controller
{
    public function issue(Request $request, JwtService $jwt)
    {
        $steamId = $request->query('steamid');

        if (!preg_match('/^7656119\d{10}$/', $steamId)) {
            return response()->json(['error' => 'Invalid SteamID64'], 400);
        }

        $token = $jwt->issueToken($steamId);

        return response()->json([
            'token' => $token,
            'expires_in' => env('JWT_EXPIRATION', 2700),
        ]);
    }

    public function status(Request $request, JwtService $jwt)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['valid' => false], 401);
        }

        $decoded = $jwt->verifyToken($token);

        if (!$decoded) {
            return response()->json(['valid' => false], 401);
        }

        $now = time();
        $expiresIn = $decoded->exp - $now;

        if ($expiresIn <= 0) {
            return response()->json(['valid' => false], 401);
        }

        return response()->json([
            'valid' => true,
            'steamid' => $decoded->steamid ?? null,
            'expires_in' => $expiresIn
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache');
    }
}
