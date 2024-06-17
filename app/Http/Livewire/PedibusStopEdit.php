<?php

namespace App\Http\Livewire;

use App\Models\PedibusStop;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PedibusStopEdit extends Component
{
    use LivewireAlert;

    public $pedibusStop;

    public $test = 2;

    protected $rules = [
        'pedibusStop.name' => 'required|string',
        'pedibusStop.address' => 'required|string',
        'pedibusStop.order' => 'required|numeric',
        'pedibusStop.time' => 'required|string'
    ];

    public function render()
    {
        return view('livewire.pedibus-stop-edit');
    }

    public function mount(int $pedibusStopId)
    {
        $this->pedibusStop = PedibusStop::find($pedibusStopId);
    }

    public function update()
    {
        $this->validate();
        $this->pedibusStop->save();
        $this->alert('success', 'Fermata aggiornata!');
        $this->emitUp('stop-updated');
    }

    public function getPedibusLineProperty()
    {
        return $this->pedibusStop->pedibusLine;
    }

    public function getPedibusStopsCountProperty()
    {
        return $this->pedibusLine->stops()->count();
    }
}
