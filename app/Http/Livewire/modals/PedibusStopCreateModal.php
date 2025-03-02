<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class PedibusStopCreateModal extends ModalComponent
{
    use LivewireAlert;

    public int $pedibusLineId;
    public string $newName = '';
    public string $newAddress = '';
    public int $newOrder = 1;
    public $newTime;


    public function render()
    {
        return view('livewire.modals.pedibus-stop-create-modal');
    }

    public function mount($pedibusLineId)
    {
        $this->pedibusLineId = $pedibusLineId;
    }

    public function createStop()
    {
        $this->validate([
            'newName' => 'required|string'
        ]);

        $this->pedibusLine->stops()->create([
            'name' => $this->newName,
            'address' => $this->newAddress,
            'order' => $this->newOrder,
            'time' => $this->newTime
        ]);

        $this->alert('success', 'Fermata creata');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function getPedibusLineProperty()
    {
        return PedibusLine::find($this->pedibusLineId);
    }

    public function getPedibusStopsCountProperty()
    {
        return $this->pedibusLine->stops()->count();
    }
}
