<?php

namespace App\Events;

use App\Models\Bid as Model;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Event;

class Bid extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bid = null;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $bid, $userEvent = null)
    {
        $this->bid = $bid->toArray();

        if ($userEvent) {
            $this->bid['number'] = $userEvent->number;
            $this->bid['name']   = $userEvent->number;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['local'];
    }
}
