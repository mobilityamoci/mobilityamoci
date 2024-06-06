<?php

namespace App\Http\Livewire;

use App\Models\PedibusLine;
use Livewire\Component;

class PedibusLineEdit extends Component
{

    public $line;

    protected $rules = [
        'line.name' => 'string|required'
    ];

    protected $listeners = ['stop-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.pedibus-line-edit');
    }

    public function mount($selectedLineId)
    {
        $this->line = PedibusLine::find($selectedLineId);
    }

    public function stopUpdated()
    {
        $this->emit('$refresh');
    }

    public function getPedibusStopsProperty()
    {
        return $this->line->stops;
    }

}
