<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use UnexpectedValueException;
class JwtAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            // Attach decoded token to the request for later use
            $request->attributes->add(['jwt_payload' => $decoded]);
        } catch (\LogicException $e) {
            return response()->json(['error' => 'Invalid environmental setup issue or malformed JWT keys'], 401);
        } catch (UnexpectedValueException  $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}