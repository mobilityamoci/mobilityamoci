<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use Livewire\Component;

class PedibusLineMapShowModal extends Component
{
    public int $pedibusLineId;

    public function render()
    {
        return view('livewire.modals.pedibus-line-map-show-modal');
    }

    public function mount($pedibusLineId)
    {
        $this->pedibusLineId = $pedibusLineId;
    }

    public function getPedibusLineProperty()
    {
        return PedibusLine::find($this->pedibusLineId);
    }

    public function getCenterPointProperty()
    {
        return $this->pedibusLine->school->building->first()->centerPoint();
    }

    public function getPolyLineProperty()
    {
        return [['points' => $this->pedibusLine->toArrayWGS84()]];
    }
}
