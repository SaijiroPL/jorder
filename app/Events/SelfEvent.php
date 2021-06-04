<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SelfEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_id;
    public $calling_time;

    public function __construct($order_id, $calling_time)
    {
        $this->order_id = $order_id;
        $this->calling_time = $calling_time;
    }

    public function broadcastOn()
    {
        //public channel
        return new Channel('self-channel');
    }
}
