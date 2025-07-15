<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Log;

class JwtService
{
    protected string $secret;
    protected int $expiration;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET');
        $this->expiration = env('JWT_EXPIRATION', 2700); // in seconds
    }

    public function issueToken(string $steamId): string
    {
        $now = time();
        $payload = [
            'iss' => 'api.abso.gg',         // issuer
            'aud' => 'gmod-server',
            'iat' => $now,                  // issued at
            'exp' => $now + $this->expiration, // expiration
            'steamid' => $steamId           // custom claim
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (LogicException $e) {
            Log::warning("Invalid JWT Environmental Setup: " . $e->getMessage());
            return null;
        } catch (UnexpectedValueException $e) {
            Log::warning("Invalid JWT: " . $e->getMessage());
            return null;
        }
    }
}
