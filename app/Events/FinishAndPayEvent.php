<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FinishAndPayEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_id;
    public $table_id;

    public function __construct($order_id, $table_id)
    {
        $this->order_id = $order_id;
        $this->table_id = $table_id;
    }

    public function broadcastOn()
    {
        //public channel
        return new Channel('finish-and-pay-channel');
    }
}
