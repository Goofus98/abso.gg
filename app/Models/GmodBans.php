<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Searchable;
class GmodBans extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use Searchable;
    protected $fillable = ["SteamID", "Reason", "Type", "ExpiryDate"];
    protected $attributes = [
        'Reason' => '',
        'Admin' => 'CONSOLE'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'SteamID', 'steam_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'Admin', 'steam_id');
    }

    public function toSearchableArray(): array {
        return [
            'Reason' => $this->Reason,
            'Admin' => $this->Admin,
            'banned_user.name' => '',
            'admin_user.name' => '',
            'SteamID' => $this->SteamID,
        ];
    }
}
