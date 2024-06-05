<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class PedibusLineMapShowModal extends ModalComponent
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
        return $this->pedibusLine->school->buildings->first()->centerPoint();
    }

    public function getPolyLineProperty()
    {
        return [['points' => $this->pedibusLine->toArrayWGS84()]];
    }

    public static function modalMaxWidth(): string
    {
        return '7xl';
    }
}
