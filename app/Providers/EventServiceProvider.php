<?php

namespace App\Providers;

use App\Models\Building;
use App\Models\PedibusStop;
use App\Models\School;
use App\Models\Student;
use App\Models\Trip;
use App\Observers\BuildingsObserver;
use App\Observers\PedibusStopObserver;
use App\Observers\SchoolsObserver;
use App\Observers\StudentsObserver;
use App\Observers\TripsObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{


    protected $observers = [
        Student::class => [StudentsObserver::class],
        Building::class => [BuildingsObserver::class],
        Trip::class => [TripsObserver::class],
        School::class => [SchoolsObserver::class],
        PedibusStop::class => [PedibusStopObserver::class]
    ];


    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
