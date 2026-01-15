<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Services\SteamAPIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //static SteamAPIService $steamapi = SteamAPIService();

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'steam_id',
        'rank',
        'playtime'
    ];

    protected $attributes = [
        'rank' => 'user',
        'playtime' => 0
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $steamapi = new SteamAPIService();
            $info = $steamapi->getName($user->steam_id);
            $user->name = $info['nick'];
            $image = Http::get($info['avatar'])->body();
            $path = 'avatar/' . $user->steam_id . '.jpg';
            Storage::disk('public')->put($path, $image);
            $user->avatar_url = Storage::disk('public')->url($path);

            $frameInfo = $steamapi->getAvatarFrame($user->steam_id);
            if ($frameInfo["id"] != "") {
                $image = Http::get($frameInfo["url"])->body();
                $path = 'frames/' . $frameInfo["id"] . '.png';
                Storage::disk('public')->put($path, $image);
                $user->avatar_frame = Storage::disk('public')->url($path);
            }
            $user->last_steam_update = now();
        });
    }
}
