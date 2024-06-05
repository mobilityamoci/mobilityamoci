<?php

namespace App\Http\Livewire\Modals;

use App\Models\PedibusLine;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class PedibusStopCreateModal extends ModalComponent
{
    use LivewireAlert;
    public int $pedibusLineId;
    public string $newName = 'cocane';
    public string $newAddress = 'via poipo';
    public int $newOrder = 1;


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
            'order' => $this->newOrder
        ]);

        $this->alert('success', 'Fermata creata');
        $this->emit('close-modal');
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
