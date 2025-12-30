<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Cache;
use App\Services\CommunityStatsService;

class CommunityStatsController extends Controller
{
    public function retrieve(CommunityStatsService $service){
        return Cache::remember('gmod_community_stats', 1800, function () use ($service) {
            return $service->fetch();
        });
    }
}
