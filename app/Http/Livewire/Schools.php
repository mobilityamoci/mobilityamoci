<?php

namespace App\Http\Livewire;

use App\Http\Controllers\ArchiveController;
use App\Models\School;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Schools extends Component
{
    use LivewireAlert;

    public bool $editingSections = false;

    public int|null $editSchoolId = null;
    public $schools;

    public $indexToArchive;

    protected $rules = [
        'schools.*.name' => 'string|required'
    ];

    protected $listeners = [
        'archiveSchool',
        'cancel'
    ];

    public function mount()
    {
        $this->schools = getUserSchools(false, [], ['students', 'sections'])->toArray();
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

    public function confirmArchiveSchool($index)
    {
        $this->indexToArchive = $index;
        $this->confirm('<span style="align-self: center">Sicuro/a di voler archiviare la scuola?</span><br>Inserisci un titolo da dare all\'archivio (es. 2024/2025)',
            [
                'input' => 'text',
                'inputValidator' => '(value) => new Promise((resolve) => '.
                    '  resolve('.
                    '    value.trim().length === 0 ? '.
                    '    "Error in password" : undefined '.
                    '  )'.
                    ')',
                'toast' => false,
                'timer' => '',
                'position' => 'center',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Si!',
                'width' => '30%',
                'onConfirmed' => 'archiveSchool',
                'showDenyButton' => true,
                'denyButtonText' => 'No',
                'onDenied' => 'cancel',
//                'onDismissed' => 'chiediAssociazione'
            ]);
    }

    public function cancel()
    {
        $this->indexToArchive = null;
    }

    public function archiveSchool($data)
    {
        (new ArchiveController())->archiveSchool($this->schools[$this->indexToArchive]['id'], $data['value']);
    }
}
