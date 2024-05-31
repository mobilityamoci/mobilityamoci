<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use App\Services\QgisService;
use Clickbar\Magellan\Data\Geometries\LineString;
use LivewireUI\Modal\ModalComponent;

class PedibusLineMapDrawModal extends ModalComponent
{
    public int $pedibusLineId;

    protected $listeners = [
        'lineCreated'
    ];

    public function render()
    {
        return view('livewire.modals.pedibus-line-map-draw-modal');
    }

    public function mount($pedibusLineId)
    {
        $this->pedibusLineId = $pedibusLineId;
    }

    public function lineCreated($latLngWKT)
    {
        $this->pedibusLine->line()->delete();
        $this->pedibusLine->line()->create(['line' => LineString::make(QgisService::fromArrayToArrayOfPoints($latLngWKT), srid: 32632)]);
        $this->emit('close-modal');
    }

    public function getPedibusLineProperty()
    {
        return PedibusLine::find($this->pedibusLineId);
    }


    public function getCenterPointProperty()
    {
        return $this->pedibusLine->centerPoint();
    }

    public function getMarkersProperty()
    {

        return [array_merge($this->centerPoint, ['title' => 'Scuola', 'icon' => 'school'])];
    }

    public static function modalMaxWidth(): string
    {
        return '7xl';
    }


}
