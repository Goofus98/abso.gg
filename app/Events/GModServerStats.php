<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GModServerStats implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // The data sent to frontend
    public $servers;

    public function __construct($servers)
    {
        $this->servers = $servers;
    }

    // The channel name must match what your frontend listens to
    public function broadcastOn()
    {
        return new Channel('gm_live_servers');
    }

    // Optional: define event name explicitly
    public function broadcastAs()
    {
        return 'UpdateGModServerStats';
    }
}
