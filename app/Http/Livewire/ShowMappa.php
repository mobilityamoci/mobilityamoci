<?php

namespace App\Http\Livewire;


use Auth;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowMappa extends Component
{

    public Collection $schools;

    public int|null $selectedSchoolId = null;
    public function render()
    {
        return view('livewire.show-mappa');
    }

    public function mount()
    {
        $this->schools = getUserSchools(true);
    }

    public function getLizmapLinkProperty()
    {
        return Auth::user()->lizmapLink($this->selectedSchoolId ? [$this->selectedSchoolId] : null);
    }
}
