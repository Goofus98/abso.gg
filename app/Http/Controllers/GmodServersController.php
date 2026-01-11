<?php

namespace App\Http\Controllers;

use App\Models\GmodServers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use Throwable;
class GmodServersController extends Controller
{
    public function retrieve(){
        return Cache::remember('gmod_server_stats', 900, function () {
            $areas = GmodServers::all();

            $output = [];
            $output["servers"] = [];
            foreach ($areas as $area) {
                $area_data = array(
                    "id" => $area->id,
                    "name" => $area->name,
                    "ip" => $area->ip,
                    "port" => $area->port,
                    "map" => $area->map,
                    "gamemode" => $area->gamemode,
                    "online" => $area->online,
                    "max_online" => $area->max_online
                );
                $output["servers"][] = $area_data;
            }

            return $output;
        });
    }
    public function register(Request $request)
    {
        $server = GmodServers::create([
            'name' => $request->input('name'),
            'ip' => $request->ip(),
            'api_key' => Str::random(64), // Generate the API key here
        ]);

        return response()->json([
            'message' => 'Server registered',
            'api_key' => $server->api_key,
        ]);
    }
}
