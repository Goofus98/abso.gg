<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GmodBans extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ["SteamID", "Reason", "Type", "ExpiryDate"];
    protected $attributes = [
        'Reason' => '',
        'Admin' => 'CONSOLE'
    ];
}
