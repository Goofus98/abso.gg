<?php

namespace App\Http\Controllers;

use App\Models\GmodServers;
use Illuminate\Http\Request;

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
}
