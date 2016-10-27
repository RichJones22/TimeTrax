<?php

namespace App\Listeners;

use App\Events\WorkFlowApplicationReceivedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkFlowApplicationReceivedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WorkFlowApplicationReceivedEvent  $event
     * @return void
     */
    public function handle(WorkFlowApplicationReceivedEvent $event)
    {
        var_dump("client is " . $event->client->name);
    }
}
