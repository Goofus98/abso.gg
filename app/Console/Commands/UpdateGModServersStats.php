<?php

namespace App\Console\Commands;
use App\Models\GmodServers;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

use App\Events\GModServerStats;
use xPaw\SourceQuery\SourceQuery;
use Throwable;
class UpdateGModServersStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmod:update-server-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve Current GMod Server Stats';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Cache::lock('gmod:update-server-stats', 60)->block(10, function () {
            $servers = GmodServers::all();

            foreach ($servers as $server) {
                $Query = new SourceQuery();
                try {
                    $Query->Connect($server->ip, $server->port, 1.5, SourceQuery::SOURCE);
                    $info = $Query->GetInfo();
                    if (is_array($info) && !empty($info)) {
                        GmodServers::where('id', $server->id)->update([
                            "name" => $info["HostName"],
                            "gamemode" => $info["ModDesc"],
                            "map" => $info["Map"],
                            "online" => $info["Players"],
                            "max_online" => $info["MaxPlayers"],
                        ]);
                    } else {
                        //offline
                    }
                } catch (Throwable $e) {
                    echo $e->getMessage();
                } finally {
                    $Query->Disconnect();
                }
            }
            $servers = GmodServers::all();

            $cache = [];
            $cache["servers"] = [];
            foreach ($servers as $area) {
                $cache["servers"][] = array(
                    "id" => $area->id,
                    "name" => $area->name,
                    "ip" => $area->ip,
                    "port" => $area->port,
                    "map" => $area->map,
                    "gamemode" => $area->gamemode,
                    "online" => $area->online,
                    "max_online" => $area->max_online
                );
            }
            Cache::put('gmod_server_stats', $cache, now()->addMinutes(15));
            event(new GModServerStats($servers));
        });
        return 0;
    }
}
