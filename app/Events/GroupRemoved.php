<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GroupRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $campaign_id;
    public $group_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($campaignId, $groupId)
    {
        $this->campaign_id = $campaignId;
        $this->group_id = $groupId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
