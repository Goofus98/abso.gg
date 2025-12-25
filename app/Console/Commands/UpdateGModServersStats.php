<?php

namespace App\Console\Commands;
use App\Models\GmodServers;

use Illuminate\Console\Command;
use App\Events\GModServerStats;
use xPaw\SourceQuery\SourceQuery;
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
            } catch (Exception $e) {
                echo $e->getMessage();
            } finally {
                $Query->Disconnect();
            }
        }
        // Fetch your data
        $servers = GmodServers::all();

        // Dispatch the broadcast event
        event(new GModServerStats($servers));
        return Command::SUCCESS;
    }
}
