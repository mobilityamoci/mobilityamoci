<?php

namespace App\Http\Livewire;

use App\Models\School;
use Livewire\Component;
use Ramsey\Collection\Collection;

class Schools extends Component
{
    public bool $editingSections = false;

    public int|null $editSchoolId = null;
    public $schools;

    public string $editingSchoolName = '';


    public function mount() {
        $this->schools = School::withCount('students','sections')->get();
    }


    public function render()
    {
        return view('livewire.schools');
    }

    public function handleSections($schoolId) {
        $this->editSchoolId = $schoolId;
        $this->editingSections = true;
    }

    public function hydrate()
    {
        $this->schools->loadCount('students');
        $this->schools->loadCount('sections');
    }

    public function saveSchool()
    {

    }
}
