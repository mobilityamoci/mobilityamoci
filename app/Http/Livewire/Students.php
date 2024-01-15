<?php

namespace App\Http\Livewire;

use App\Enums\RolesEnum;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Students extends Component
{
    use LivewireAlert;

    public $user;

//    public $querySelectedSection = 1;

    public $students;
    public int|null $editStudentIndex = null;
    public string|null $editStudentField = null;

    public bool $addingNewStudent = false;
    public bool $addingNewTrip = false;

    public int|null $newSectionId = null;
    public int|null $newComuneIstat = null;
    public string|null $newIndirizzo = null;
    public string|null $newName = null;
    public string|null $newSurname = null;

    public int|null $editingTripTransport = null;

    public int|null $newTripTownIstat = null;
    public int|null $newTripTransport1 = null;
//    public int|null $newTripTransport2 = null;
    public ?string $newTripAddress = null;

    public int|null $editTripIndex = null;

    public $transports;

    public $recaptchaResponse = null;

//    public $test = true;

    public bool $editSections = false;

    public Collection $schools;
    public  $selectedSchoolId;

    public  $selectedSectionId;

    public bool $showTransportsModal = false;


    protected $rulesWithName = [
//        'students.*.name' => 'string|required',
//        'students.*.surname' => 'string|required',
        'students.*.town_istat' => 'integer|nullable|',
        'students.*.section_id' => 'numeric|exists:sections,id'
    ];

    protected $rulesWithoutName = [
        'students.*.town_istat' => 'integer|required|',
        'students.*.section_id' => 'numeric|exists:sections,id'
    ];

    protected $validationAttributes = [
        'students.*.town_istat' => 'comune',
        'students.*.section_id' => 'sezione',
        'newTripTransport1' => '1° mezzo',
        'newTripTownIstat' => 'comune intermedio',
        'newTripAddress' => 'indirizzo intermedio'
    ];

    protected $queryString = [
        'selectedSectionId' => ['except' => 1, 'as' => 'sezione'],
        'selectedSchoolId' => ['except' => 1, 'as' => 'scuola'],
    ];

    public function render()
    {
        if ($this->selectedSectionId) {
            $section = Section::with('students', 'students.user')->find($this->selectedSectionId);


            $students = $section->students()->with('trips', 'trips.transport1', 'trips.transport2')->orderBy('name')->get();

            $students = $students->map(function ($item) {
                $item['has_user'] = !is_null($item->user_id);


                if ($item->trips->isNotEmpty()) {
                    $string = '1) Da <b>' . $this->comuni[$item->town_istat]['comune']  . ' (' . $item->address . ')</b> in ';
                    $i = 0;

                    foreach ($item->trips as $trip) {
                        if ($i != 0) {
                            $string .= '<br>' . $i + 1 . ') ';
                        }
                        $string .= '<b>' . $trip->transport1->name . '</b>';
                        $trip->transport2 ? $string .= '<b>/' . $trip->transport2->name . '</b>' : $string .= '';
                        if ($trip->town_istat)
                            $string .= ' fino a <b>' . $this->comuni[$trip->town_istat]['comune'] . ' (' . $trip->address . ')</b>';
                        else
                            $string .= ' (comune assente)';
                        $i++;
                    }

                    $string .= '<br>' . $i + 1 . ') <b>Arrivo a Scuola</b>';
                    $item['trip_string'] = $string;
                } else {
                    $item['trip_string'] = '<b>Percorso ancora da creare!</b>';
                }
                return $item;
            })->sortBy('name');


            $this->students = $students->toArray();
        } else {
            $this->students = null;
        }
        return view('livewire.students');
    }

    public function mount()
    {
        $this->user = \Auth::user();
        if ($this->user->can('all_schools')) {
            $this->schools = School::all();
        } else {
            $this->schools = $this->user->schools;
        }
        $this->selectedSchoolId = $this->selectedSchoolId ?? optional($this->schools->first())->id;
        $this->selectedSectionId = $this->selectedSectionId ??  optional($this->sections->first())->id;
        $this->transports = Transport::all()->keyBy('id')->toArray();
    }


    public function getSectionsProperty()
    {
        if ($this->user->hasAnyPermission(['all_schools', 'school']))
            return Section::where('school_id', $this->selectedSchoolId)->get()->keyBy('id');
        else
            return $this->user->sections;
    }



    public function setEditStudentField($index, $fieldName)
    {
        $this->editStudentField = $index . '.' . $fieldName;
    }

    public function saveStudent($index)
    {
        if ($this->user->hasAnyRole($this->canSeeNamesRoles))
            $this->validate($this->rulesWithName);
        else
            $this->validate($this->rulesWithoutName);


        $student = $this->students[$index] ?? NULL;
        if (!is_null($student)) {
            optional(Student::find($student['id']))->update($student);

        }
        $this->editStudentField = null;
        $this->editStudentIndex = null;
        $this->alert('success', 'Utente salvato con successo');
    }


    public function getComuniProperty()
    {
        return Cache::get('comuni');
    }

    public function schoolChanged()
    {
        $school = School::with('sections')->find($this->selectedSchoolId);
        $this->selectedSectionId = optional(optional($this->sections)->first())->id;
        $this->sectionChanged();
    }

    public function createStudent()
    {
        if ($this->user->hasAnyRole($this->canSeeNamesRoles))
            $rules = [
                'newIndirizzo' => 'string|required',
                'newComuneIstat' => 'integer|required',
                'newSectionId' => 'integer|required'
            ];
        else
            $rules = [
                'newIndirizzo' => 'string|required',
                'newComuneIstat' => 'integer|required',
                'newSectionId' => 'integer|required'
            ];

        $this->validate($rules);

        $student = Student::create([
            'name' => $this->newName,
            'surname' => $this->newSurname,
            'school_id' => $this->selectedSchoolId,
            'section_id' => $this->newSectionId,
            'town_istat' => $this->newComuneIstat,
            'address' => $this->newIndirizzo
        ]);

        if (is_null($student)) {
            session()->flash('error', 'Errore nella creazione');
        }

        $this->addingNewStudent = false;
        $this->newIndirizzo = null;
        $this->newComuneIstat = null;
        $this->newSectionId = null;
    }

    public function closeModal()
    {
        $this->editSections = false;
//        dd($this->showSectionModal);
        $this->dispatchBrowserEvent('close-modal');
    }

    public function openTransportsModal($index)
    {
        $this->editStudentIndex = $index;
        $this->showTransportsModal = true;
    }

    public function closeTransportsModal()
    {
        $this->editStudentIndex = null;
        $this->showTransportsModal = false;
    }

    public function closeStudentModal()
    {
        $this->addingNewStudent = false;
    }

    public function saveTrip($index)
    {
        $trip = $this->students[$this->editStudentIndex]['trips'][$index] ?? NULL;


        if (!is_null($trip)) {
            if ($trip['transport_2'] == "") {
                $trip['transport_2'] = null;
            }
            optional(Trip::find($trip['id']))->update($trip);
        }
        $this->editTripIndex = null;
        $this->editingTripTransport = null;
    }

    public function deleteTrip($index)
    {
        $trip = $this->students[$this->editStudentIndex]['trips'][$index] ?? NULL;

        if (!is_null($trip))
            optional(Trip::find($trip['id']))->delete();

        $this->editTripIndex = null;
        $this->editingTripTransport = null;
    }

    public function createTrip()
    {
        $this->validate([
            'newTripTransport1' => 'int|nullable',
//            'newTripTransport2' => 'int|nullable',
            'newTripTownIstat' => 'int|nullable',
            'newTripAddress' => 'string|nullable'
        ]);

        $student = $this->students[$this->editStudentIndex] ?? null;
        if (!is_null($student)) {

            if ($this->newTripTownIstat == 0) {
                $section = Section::with('building', 'building.geometryPoint')->find($this->selectedSectionId);

                $address = $section->building->geometryPoint->address_request;
                $istat = $section->building->town_istat;
            } else {
                $address = $this->newTripAddress;
                $istat = $this->newTripTownIstat;
            }

            $order = Student::find($student['id'])->trips()->max('order');
            $order++;

            Trip::create([
                'student_id' => $student['id'],
                'transport_1' => $this->newTripTransport1,
                'order' => $order,
                'address' => $address,
//                'transport_2' => $this->newTripTransport2,
                'town_istat' => $istat,
            ]);

        $this->reset(['newComuneIstat','newTripTransport1','newTripAddress']);
            $this->editTripIndex = null;
            $this->addingNewTrip = false;
        }

    }

    public function deleteStudent($index)
    {
        $student = $this->students[$index] ?? NULL;

        if (!is_null($student))
            optional(Student::find($student['id']))->delete();
    }

    public function getCanSeeNamesRolesProperty()
    {
        return [RolesEnum::INSEGNANTE->value, RolesEnum::MM_SCOLASTICO->value];
    }
}
