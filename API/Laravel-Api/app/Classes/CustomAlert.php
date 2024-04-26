<?php

namespace App\Classes;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CustomAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $userId;
    protected $eventType;
    public $message;

    public function __construct($userId, $message, $eventType)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->eventType = $eventType;
    }

    public function broadcastOn()
    {
        return [$this->eventType];
    }

    public function broadcastAs()
    {
        return $this->userId;
    }
}
