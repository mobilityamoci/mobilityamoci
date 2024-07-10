<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use App\Services\QgisService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class PedibusLineMapDrawModal extends ModalComponent
{
    use LivewireAlert;
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
        $this->pedibusLine->line()->create(['line' => QgisService::transformWKT(4326, 32632, $latLngWKT)]);
        $this->dispatchBrowserEvent('close-modal');
        $this->alert('success', 'Linea disegnata con successo');
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
