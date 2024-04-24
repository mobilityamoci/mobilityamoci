<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SingleStudentCreate extends Component
{
    use LivewireAlert;

    public User $user;

    public $sections;

    public ?Student $student;

    public string $newStudentName;
    public string $newStudentSurname;
    public string $newStudentAddress;
    public int $newStudentIstat;
    public int $newStudentSection;

    protected $rules = [
        'newStudentName' => 'required|string',
        'newStudentSurname' => 'required|string',
        'newStudentAddress' => 'required|string',
        'newStudentSection' => 'required|integer|exists:my_sections,id',
        'newStudentIstat' => 'required|integer',
    ];

    protected $validationAttributes = [
        'newStudentName' => 'nome',
        'newStudentSurname' => 'cognome',
        'newStudentAddress' => 'indirizzo',
        'newStudentSection' => 'sezione',
        'student.town_istat' => 'comune di domicilio',
        'student.trips.*.transport_1' => 'primo mezzo di trasporto',
        'student.trips.*.transport_2' => 'secondo mezzo di trasporto',
        'student.trips.*.town_istat' => 'comune di arrivo'
    ];

    public $possibleStudents;

    protected $listeners = ['chiediAssociazione', 'associaStudente'];

    public function mount()
    {
        $this->user = auth()->user();
        $this->newStudentName = $this->user->name;
        $this->newStudentSurname = $this->user->surname;

        $this->sections = $this->user->schools->first()->sections;
        $this->getPossibleStudents();
    }


    public function render()
    {
        return view('livewire.single-student-create');
    }


    public function chiediAssociazione(): void
    {

        if ($this->possibleStudents->isNotEmpty()) {
            $this->student = $this->possibleStudents->pop();
            $this->alert('question', '<span style="align-self: center">Sei <b>' . $this->student->fullInfo() . '</b></span>?',
                [
                    'toast' => false,
                    'timer' => '',
                    'position' => 'center',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Si!',
                    'width' => '30%',
                    'onConfirmed' => 'associaStudente',
                    'showDenyButton' => true,
                    'denyButtonText' => 'No',
                    'onDenied' => 'chiediAssociazione',
                    'onDismissed' => 'chiediAssociazione'
                ]);
        }
    }

    public function associaStudente()
    {
        $this->student->update([
            'user_id' => $this->user->id
        ]);
        $this->emitUp('mount');
    }

    public function getComuniProperty()
    {
        return getComuniArray();
    }

    public function createStudent()
    {
        $this->validate();

        $student = Student::create([
            'name' => $this->newStudentName,
            'surname' => $this->newStudentSurname,
            'section_id' => $this->newStudentSection,
            'town_istat' => $this->newStudentIstat,
            'address' => $this->newStudentAddress,
            'user_id' => $this->user->id
        ]);

        $this->alert('success', 'Dati salvati con successo.');
        $this->emitUp('mount');


    }

    public function askPossibleStudents()
    {
        $this->getPossibleStudents();
        $this->chiediAssociazione();
    }

    /**
     * @return void
     */
    private function getPossibleStudents(): void
    {
        $section_ids = $this->user->schools->first()->sections->pluck('id');
        $this->possibleStudents = Student::where(function ($query) {
            $query->orWhere('surname', 'ILIKE', '%' . $this->user->surname . '%');
            $query->orWhere('name', 'ILIKE', '%' . $this->user->name . '%');
        })
            ->whereIn('section_id', $section_ids)
            ->whereNull('user_id')
            ->get();

    }


}
