<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
class SteamAPIService
{
    public function fetchAvatars($steamids): array
    {
        $output = [];
        $output['staff'] = [];
        try {
            $steamPlayers = Http::get('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . env('STEAM_API_KEY') . '&steamids=' . $steamids)->throw()["response"]["players"];
            foreach ($steamPlayers as $player){
                $output['staff'][$player['steamid']]['profile'] = $player['avatarfull'];
                $output['staff'][$player['steamid']]['name'] = $player['personaname'];
            }
        } catch (Throwable $e) {
            Log::warning('Steam API avatar fetch failed', [
                'exception' => $e->getMessage()
            ]);
        }

        return $output;
    }

    public function getName($steamids): array
    {
        $name = "";
        $avatar = "";
        try {
            $steamPlayers = Http::get('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . env('STEAM_API_KEY') . '&steamids=' . $steamids)->throw()["response"]["players"];
            foreach ($steamPlayers as $player){
                $name = $player['personaname'];
                $avatar = $player['avatarfull'];
            }
        } catch (Throwable $e) {
            Log::warning('Steam API avatar fetch failed', [
                'exception' => $e->getMessage()
            ]);
        }

        return array('nick' => $name, 'avatar' => $avatar);
    }

    public function getAvatarFrame($steamids) {
        $path = 'https://api.steampowered.com/IPlayerService/GetProfileItemsEquipped/v1/?key=' . env('STEAM_API_KEY') . '&steamid='. $steamids;
        $frameURL = "";
        $id = "";
        try {
            $equippedProfileItems = Http::get($path )->throw()["response"];

            $frameURL = "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/" . $equippedProfileItems["avatar_frame"]["image_small"];
            $id = $equippedProfileItems["avatar_frame"]["communityitemid"];
        } catch (Throwable $e) {
            Log::warning('Steam API avatar fetch failed', [
                'exception' => $e->getMessage()
            ]);
        }

        return array('url' => $frameURL, 'id' => $id);
    }
}
