<?php

namespace App\Providers;

use App\TimeCardHoursWorked;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use \App\Project;
use \App\Client;
use \App\WorkType;
use \App\TimeCardFormat;
use \App\Work;
use \App\TimeCard;
use \App\TaskType;
use \App\Task;
use \App\Observers\ProjectObserver;
use \App\Observers\ClientObserver;
use \App\Observers\WorkTypeObserver;
use \App\Observers\TimeCardFormatObserver;
use \App\Observers\WorkObserver;
use \App\Observers\TimeCardObserver;
use \App\Observers\TimeCardHoursWorkedObserver;
use \App\Observers\TaskTypeObserver;
use \App\Observers\TaskObserver;

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
        // register the TimeCardFormatObserver class.
        TimeCardFormat::observe(new TimeCardFormatObserver);
        // register the WorkObserver class.
        Work::observe(new WorkObserver);
        // register the TimeCardObserver class.
        TimeCard::observe(new TimeCardObserver);
        // register the TimeCardHoursWorkedObserver class.
        TimeCardHoursWorked::observe(new TimeCardHoursWorkedObserver);
        // register the TaskTypeObserver class.
        TaskType::observe(new TaskTypeObserver);
        // register the TaskObserver class.
        Task::observe(new TaskObserver);
    }
}
