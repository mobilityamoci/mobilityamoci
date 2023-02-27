<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Optional;
use Livewire\Component;

class Students extends Component
{

    public $students;
    public int|null $editStudentIndex = null;
    public string|null $editStudentField = null;

    public bool $addingNewStudent = false;
    public bool $addingNewTrip = false;

    public int|null $newSectionId = null;
    public int|null $newComuneIstat = null;
    public string|null $newIndirizzo = null;

    public int|null $editingTripTransport = null;

    public int|null $newTripTownIstat = null;
    public int|null $newTripTransport1 = null;
    public int|null $newTripTransport2 = null;

    public int|null $editTripIndex = null;

    public $transports;

    public $recaptchaResponse = null;

//    public $test = true;

    public bool $editSections = false;

    public Collection $schools;
    public int $selectedSchoolId;
    public int|null $selectedSectionId;

    public bool $showTransportsModal = false;

    protected $rules = [
        'students.*.town_istat' => 'integer|required|',
        'students.*.section_id' => 'numeric|exists:sections,id'
    ];

    protected $validationAttributes = [
        'students.*.town_istat' => 'comune',
        'students.*.section_id' => 'sezione',
    ];

    public function render()
    {
        return view('livewire.students');
    }

    public function mount()
    {
        $this->schools = School::all();
        $this->selectedSchoolId = 1;
        $this->selectedSectionId = $this->sections->first()->id;
        $this->transports = Transport::all()->keyBy('id')->toArray();
        $this->reloadStudents();
    }


    public function getSectionsProperty()
    {
        return Section::where('school_id', $this->selectedSchoolId)->get()->keyBy('id');
    }

    public function reloadStudents()
    {
        if ($this->selectedSectionId) {
            $section = Section::with('students')->find($this->selectedSectionId);


            $students = $section->students()->with('trips', 'trips.transport1', 'trips.transport2')->get();


            $students = $students->map(function ($item) {
                if ($item->trips->isNotEmpty()) {
                    $string = '1) Da <b>' . $this->comuni[$item->town_istat]['comune'] . '</b> in ';
                    $i = 0;

                    foreach ($item->trips as $trip) {
                        if ($i != 0) {
                            $string .= '<br>' . $i + 1 . ') ';
                        }
                        $string .= '<b>' . $trip->transport1->name . '</b>';
                        $trip->transport2 ? $string .= '<b>/' . $trip->transport2->name . '</b>' : $string .= '';
                        $string .= ' fino a <b>' . $this->comuni[$trip->town_istat]['comune'] . '</b>';
                        $i++;
                    }

                    $string .= '<br>' . $i + 1 . ') <b>Scuola</b>';
                    $item['trip_string'] = $string;
                } else {
                    $item['trip_string'] = '<b>Percorso ancora da creare!</b>';
                }
                return $item;
            });


            $this->students = $students->toArray();
//        dd($this->students);
//        dd(collect($this->students[0]['trips'])->recursive()->where('order',1)->first()['transports']);
//        foreach (collect($this->students[0]['trips'])->recursive()->where('order',1)->first()['transports'] as $item) {
//            dd($item['name']);
//        }
        } else {
            $this->students = null;
        }

    }

    public function setEditStudentField($index, $fieldName)
    {
        $this->editStudentField = $index . '.' . $fieldName;
    }

    public function saveStudent($index)
    {
        $this->validate();
        $student = $this->students[$index] ?? NULL;

        if (!is_null($student))
            optional(Student::find($student['id']))->update($student);

        $this->editStudentField = null;
        $this->editStudentIndex = null;
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

    public function sectionChanged()
    {
        $section = Section::with('students')->find($this->selectedSectionId);
        $this->students = optional($section)->students;
    }



    public function startCreatingStudent()
    {
        $this->addingNewStudent = true;
    }

    public function createStudent()
    {
        $this->validate([
            'newIndirizzo' => 'string|required',
            'newComuneIstat' => 'integer|required',
            'newSectionId' => 'integer|required'
        ]);

        $student = Student::create([
            'name' => null,
            'surname' => null,
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

        $this->reloadStudents();
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
        $this->reloadStudents();
    }

    public function deleteTrip($index)
    {
        $trip = $this->students[$this->editStudentIndex]['trips'][$index] ?? NULL;

        if (!is_null($trip))
            optional(Trip::find($trip['id']))->delete();

        $this->editTripIndex = null;
        $this->editingTripTransport = null;
        $this->reloadStudents();
    }

    public function createTrip()
    {
        $this->validate([
            'newTripTransport1' => 'int|nullable',
            'newTripTransport2' => 'int|nullable',
            'newTripTownIstat' => 'int|required'
        ]);

        $student = $this->students[$this->editStudentIndex] ?? null;
        if (!is_null($student)) {

            $order = Student::find($student['id'])->trips()->max('order');
            $order++;

            Trip::create([
                'student_id' => $student['id'],
                'transport_1' => $this->newTripTransport1,
                'order' => $order,
                'transport_2' => $this->newTripTransport2,
                'town_istat' => $this->newTripTownIstat,
            ]);

            $this->reloadStudents();
            $this->editTripIndex = null;
            $this->addingNewTrip = false;
        }

    }

    public function deleteStudent($index)
    {
        $student = $this->students[$index] ?? NULL;

        if (!is_null($student))
            optional(Student::find($student['id']))->delete();

        $this->reloadStudents();
    }


}
