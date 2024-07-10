<?php

namespace App\Http\Livewire\Modals;

use App\Traits\HasSelectedSchool;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class PedibusLineCreate extends ModalComponent
{
    use HasSelectedSchool, LivewireAlert;

    public string $newName = '';

    public function render()
    {
        return view('livewire.modals.pedibus-line-create');
    }

    public function mount($selectedSchoolId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
    }

    public function createLine()
    {
        $this->validate(['newName' => 'string|required|max:255']);

        $this->selectedSchool->pedibusLines()->create([
            'name' => $this->newName
        ]);

        $this->alert('success', 'Linea creata');
        $this->dispatchBrowserEvent('close-modal');
    }


}
