<?php

namespace App\Http\Livewire;

use App\Models\School;
use Livewire\Component;

class Schools extends Component
{
    public bool $editingSections = false;

    public int|null $editSchoolId = null;
    public $schools;

    protected $rules = [
        'schools.*.name' => 'string|required'
    ];


    public function mount()
    {
        $this->schools = getUserSchools([], ['students', 'sections'])->toArray();
    }


    public function render()
    {
        return view('livewire.schools');
    }

//    public function handleSections($schoolId)
//    {
//        $this->editSchoolId = $schoolId;
//        $this->editingSections = true;
//    }

    public function hydrate()
    {
//        $this->schools->loadCount('students');
//        $this->schools->loadCount('sections');
    }

    public function saveSchool($index)
    {
        $this->validate();
        $school = $this->schools[$index] ?? NULL;

        if (!is_null($school))
            optional(School::find($school['id']))->update($school);

        $this->editSchoolId = null;
    }

    public function goToSchoolStudents($index)
    {
        $school = $this->schools[$index] ?? NULL;
        if ($school)
            $this->redirectRoute('students', ['scuola' => $school['id']]);
    }
}
