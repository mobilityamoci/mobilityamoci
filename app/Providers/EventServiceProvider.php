<?php

namespace App\Providers;

use App\Models\Building;
use App\Models\Student;
use App\Models\Trip;
use App\Observers\BuildingsObserver;
use App\Observers\StudentsObserver;
use App\Observers\TripsObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{


    protected $observers = [
        Student::class => [StudentsObserver::class],
        Building::class => [BuildingsObserver::class],
        Trip::class => [TripsObserver::class],
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
