<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use \App\Project;
use \App\Client;
use \App\WorkType;
use \App\Observers\ProjectObserver;
use \App\Observers\ClientObserver;
use \App\Observers\WorkTypeObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        // register the ProjectObserver class.
        Client::observe(new ClientObserver);
        // register the ProjectObserver class.
        Project::observe(new ProjectObserver);
        // register the WorkTypeObserver class.
        WorkType::observe(new WorkTypeObserver);
    }
}
