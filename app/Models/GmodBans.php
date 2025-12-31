<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmodBans extends Model
{
    use HasFactory;
    protected $fillable = ["SteamID", "Reason", "Type", "ExpiryDate"];
    protected $attributes = [
        'Reason' => '',
        'Admin' => 'CONSOLE'
    ];
}
