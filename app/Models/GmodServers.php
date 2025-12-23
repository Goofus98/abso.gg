<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class GmodServers extends Model
{
    use HasFactory;

    protected $fillable = ["name", "online", "gamemode", "map", "max_online"];

    public static function registerServer($name, $ip = null, $port = null)
    {
        return self::create([
            'name' => $name,
            'ip' => $ip,
            'port' => $port,
            //'api_key' => Str::random(64),
        ]);
    }
}
