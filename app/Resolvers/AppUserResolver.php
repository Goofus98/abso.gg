<?php
namespace App\Resolvers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;
use UnexpectedValueException;
class AppUserResolver implements \OwenIt\Auditing\Contracts\UserResolver
{
    /**
     * {@inheritdoc}
     */
    public static function resolve()
    {
        $token = Request::bearerToken();

        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                return User::where('steam_id', $decoded->steamid)->first();
            } catch (\LogicException $e) {
                return null;
            } catch (UnexpectedValueException  $e) {
                return null;
            }
        }
        $guards = Config::get('audit.user.guards', [
            \config('auth.defaults.guard')
        ]);

        foreach ($guards as $guard) {
            try {
                $authenticated = Auth::guard($guard);
            } catch (\Exception $exception) {
                continue;
            }

            if ($authenticated) {
                return Auth::guard($guard)->user();
            }
        }

        return null;
    }
}