<?php

namespace App\Observers;

use App\Models\PedibusStop;

class PedibusStopObserver
{
    /**
     * Handle the PedibusStop "created" event.
     *
     * @param PedibusStop $pedibusStop
     * @return void
     */
    public function created(PedibusStop $pedibusStop)
    {
        //move all stops after this one up one place, not this one
        $pedibusStop->pedibusLine->stops()->where('order', '>=', $pedibusStop->order)->whereNotIn('id', [$pedibusStop->id])->increment('order');
    }

    /**
     * Handle the PedibusStop "updated" event.
     *
     * @param PedibusStop $pedibusStop
     * @return void
     */
    public function updating(PedibusStop $pedibusStop)
    {
        $originalOrder = $pedibusStop->getOriginal('order');
        $newOrder = $pedibusStop->order;
        if ($originalOrder > $newOrder) {
            $pedibusStop->pedibusLine->stops()->where('order', '<=', $originalOrder)->whereNotIn('id', [$pedibusStop->id])->increment('order');
        } else if ($originalOrder < $newOrder) {
            $pedibusStop->pedibusLine->stops()->whereBetween('order', [$originalOrder, $newOrder])->whereNotIn('id', [$pedibusStop->id])->decrement('order');
        }

    }

    /**
     * Handle the PedibusStop "deleted" event.
     *
     * @param PedibusStop $pedibusStop
     * @return void
     */
    public function deleted(PedibusStop $pedibusStop)
    {
        $pedibusStop->pedibusLine->stops()->where('order', '>=', $pedibusStop->order)->whereNotIn('id', [$pedibusStop->id])->decrement('order');
    }

    /**
     * Handle the PedibusStop "restored" event.
     *
     * @param PedibusStop $pedibusStop
     * @return void
     */
    public function restored(PedibusStop $pedibusStop)
    {
        //
    }

    /**
     * Handle the PedibusStop "force deleted" event.
     *
     * @param PedibusStop $pedibusStop
     * @return void
     */
    public function forceDeleted(PedibusStop $pedibusStop)
    {
        //
    }
}
