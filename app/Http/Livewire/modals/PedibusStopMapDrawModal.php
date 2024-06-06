<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use App\Models\PedibusStop;
use App\Services\QgisService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class PedibusStopMapDrawModal extends ModalComponent
{
    use LivewireAlert;

    public $pedibusLineId;


    protected $listeners = ['pointCreated'];

    public function render()
    {
        return view('livewire.modals.pedibus-stop-map-draw-modal');
    }

    public function mount($pedibusLineId, $pedibusStopId)
    {
        $this->pedibusStopId = $pedibusStopId;
        $this->pedibusLineId = $pedibusLineId;
    }

    public function pointCreated($latLngWKT)
    {
        $this->pedibusStop->point()->delete();
        $this->pedibusStop->point()->create(['point' => QgisService::transformWKT(4326, 32632, $latLngWKT)]);
        $this->emit('close-modal');
        $this->alert('success', 'Punto disegnato con successo');
    }

    public function getPedibusLineProperty()
    {
        return PedibusLine::find($this->pedibusLineId);
    }

    public function getPedibusStopProperty()
    {
        return PedibusStop::find($this->pedibusStopId);
    }

    public function getCenterPointProperty()
    {
        return $this->pedibusLine->centerPoint();
    }

    public function getMarkersProperty()
    {

        return [array_merge($this->centerPoint, ['title' => 'Scuola', 'icon' => 'school'])];
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
