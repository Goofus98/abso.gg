<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CommunityStatsService;
use Illuminate\Support\Facades\Cache;

use App\Events\CommunityStats;

class UpdateCommunityStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gmod:update-community-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve extra community stats';

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
    public function handle(CommunityStatsService $service){
        Cache::lock('gmod:update-community-stats', 60)->block(10, function () use($service) {
            $output = $service->fetch();
            Cache::put('gmod_community_stats', $output, now()->addMinutes(30));
            event(new CommunityStats($output["stats"]));
        });
        return 0;
    }
}
