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

    public function render()
    {
        return view('livewire.pedibus-line-edit');
    }

    public function mount($selectedLineId)
    {
        $this->line = PedibusLine::find($selectedLineId);
    }

}
