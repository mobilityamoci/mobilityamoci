<?php

namespace App\Observers;

use App\Models\Building;
use App\Services\QgisService;

class BuildingsObserver
{

    private QgisService $qgisService;

    public function __construct(QgisService $qgisService)
    {
        $this->qgisService = $qgisService;
    }

    /**
     * Handle the Building "created" event.
     *
     * @param Building $building
     * @return void
     */
    public function creating(Building $building)
    {
        $building = $this->qgisService::georefBuilding($building);
    }

    /**
     * Handle the Building "updated" event.
     *
     * @param Building $building
     * @return void
     */
    public function updating(Building $building)
    {
        if ($building->isDirty(['address', 'town_istat'])) {
            $building = $this->qgisService::georefBuilding($building);
        }
    }

    /**
     * Handle the Building "deleted" event.
     *
     * @param Building $building
     * @return void
     */
    public function deleted(Building $building)
    {
        //
    }

    /**
     * Handle the Building "restored" event.
     *
     * @param Building $building
     * @return void
     */
    public function restored(Building $building)
    {
        //
    }

    /**
     * Handle the Building "force deleted" event.
     *
     * @param Building $building
     * @return void
     */
    public function forceDeleted(Building $building)
    {
        //
    }
}
