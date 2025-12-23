<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class GmodServers extends Model
{
    use HasFactory;

    protected $fillable = ["name", "online", "max_online". "api_key", "last_seen_at"];

    public static function registerServer($name, $ip = null, $port = null)
    {
        return self::create([
            'name' => $name,
            'ip' => $ip,
            'port' => $port,
            'api_key' => Str::random(64),
        ]);
    }
}
