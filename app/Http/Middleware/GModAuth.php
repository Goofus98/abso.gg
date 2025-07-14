<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\GmodServers;

class GModAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        $server = GmodServers::where('api_key', $apiKey)->first();

        if (! $server) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //$requestIp = $request->ip();
       //if ($server->ip && $server->ip !== $requestIp) {
        //    return response()->json(['message' => 'IP mismatch'], 403);
        //}

        $server->update(['last_seen_at' => now()]);
        // Optional: attach server to request for later use
        $request->merge(['gmod_server' => $server]);

        return $next($request);
    }
}
