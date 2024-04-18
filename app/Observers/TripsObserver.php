<?php

namespace App\Observers;

use App\Models\Trip;
use App\Services\QgisService;

class TripsObserver
{


    private QgisService $qgisService;

    public function __construct(QgisService $qgisService)
    {
        $this->qgisService = $qgisService;
    }

    /**
     * Handle the Trips "created" event.
     *
     * @param  \App\Models\Trip  $trip
     * @return void
     */
    public function created(Trip $trip)
    {
        $this->qgisService::georefTrip($trip);
    }

    /**
     * Handle the Trips "updated" event.
     *
     * @param  \App\Models\Trip  $trip
     * @return void
     */
    public function updated(Trip $trip)
    {
        if ($trip->isDirty(['address', 'town_istat'])) {
            $this->qgisService::georefTrip($trip);
        }
    }

    public function deleting(Trip $trip)
    {
    }

    /**
     * Handle the Trips "deleted" event.
     *
     * @param  \App\Models\Trip  $trip
     * @return void
     */
    public function deleted(Trip $trip)
    {
        //
    }

    /**
     * Handle the Trips "restored" event.
     *
     * @param  \App\Models\Trip  $trip
     * @return void
     */
    public function restored(Trip $trip)
    {
        //
    }

    /**
     * Handle the Trips "force deleted" event.
     *
     * @param  \App\Models\Trips  $trip
     * @return void
     */
    public function forceDeleted(Trips $trip)
    {
        //
    }
}
