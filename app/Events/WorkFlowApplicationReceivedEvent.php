<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Client;

class WorkFlowApplicationReceivedEvent extends Event
{
    use SerializesModels;

    /**
     * @var Client
     */
    public $client;

    /**
     * WorkFlowApplicationReceivedEvent constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
