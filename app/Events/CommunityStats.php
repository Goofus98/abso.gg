<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommunityStats implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stats;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('live_community_stats');
    }

    // Optional: define event name explicitly
    public function broadcastAs()
    {
        return 'UpdateCommunityStats';
    }
}