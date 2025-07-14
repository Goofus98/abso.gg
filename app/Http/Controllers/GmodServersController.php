<?php

namespace App\Http\Controllers;

use App\Models\GmodServers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GmodServersController extends Controller
{
    public function retrieve(){
        $areas = GmodServers::all();

        $output = [];

        foreach ($areas as $area) {
            $area_data = array(
                "name" => $area->name,
                "api_key" => $area->api_key,
            );;
            $output[] = $area_data;
        }

        return $output;
    }

    public function register(Request $request)
    {
        $server = GModServer::create([
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
