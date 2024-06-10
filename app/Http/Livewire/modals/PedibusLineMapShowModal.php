<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
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

    public function getStudentMarkersProperty()
    {
        return $this->pedibusLine->students->map(function ($student) {
            return $student->centerPoint();
        })->toArray();
    }

    public function getSchoolMarkersProperty()
    {
        return $this->pedibusLine->school->buildings->map(function ($building) {
            return $building->centerPoint();
        })->toArray();
    }

    public function getMarkersProperty()
    {
        return array_merge($this->studentMarkers, $this->schoolMarkers);
    }

    public static function modalMaxWidth(): string
    {
        return '7xl';
    }
}
