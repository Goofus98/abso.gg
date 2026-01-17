<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

use Illuminate\Support\Str;
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
    public static function registerBan($steam)
    {
        User::firstOrCreate(['steam_id' => $steam]);
        return self::create([
            'SteamID' => $steam,
            'ExpiryDate' => 1000000,
            'Reason' => Str::random(64),
        ]);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'SteamID', 'steam_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'Admin', 'steam_id');
    }
    #[SearchUsingFullText(['Reason', 'RevokeReason'])]
    public function toSearchableArray(): array {
        return [
            'Reason' => $this->Reason,
            'RevokeReason' => $this->RevokeReason,
            'Admin' => $this->Admin,
            'banned_user.name' => '',
            'admin_user.name' => '',
            'SteamID' => $this->SteamID,
        ];
    }
}
