<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
class CommunityStatsService
{
    public function fetch(): array
    {
        $output = [
            "stats" => [
                "player_count" => 0,
                "play_time" => 0,
                "discord_online_user_count" => 0,
            ]
        ];

        try {
            $count = DB::connection('garrysmod_sql')
                ->selectOne("SELECT COUNT(*) AS total FROM sam_players");

            $playtime = DB::connection('garrysmod_sql')
                ->selectOne("SELECT SUM(play_time) AS total FROM sam_players");

            $output["stats"]["player_count"] = (int) ($count->total ?? 0);
            $output["stats"]["play_time"] = (int) ($playtime->total ?? 0);

        } catch (Throwable $e) {
            Log::warning('GMod stats query failed', [
                'exception' => $e->getMessage()
            ]);
        }
        try {
            $response = Http::get(
                'https://discord.com/api/guilds/' . env('DISCORD_SERVER_ID', 'YOUR_SERVER_ID') . '/widget.json'
            );

            if ($response->successful()) {
                $output["stats"]["discord_online_user_count"] =
                    (int) $response->json('presence_count', 0);
            }

        } catch (Throwable $e) {
            Log::warning('Discord widget request failed', [
                'exception' => $e->getMessage()
            ]);
        }

        return $output;
    }
}
