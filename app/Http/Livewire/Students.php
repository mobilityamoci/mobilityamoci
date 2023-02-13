<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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
        $this->transports = Transport::all()->keyBy('id')->toArray();
        self::cacheComuni();
        $this->reloadStudents();
    }


    public function getSectionsProperty()
    {
        return Section::where('school_id', $this->selectedSchoolId)->get()->keyBy('id')->toArray();
    }

    public function reloadStudents()
    {
        $school = School::where('id',$this->$this->selectedSchoolId)->first();

        if ($school) {
            $students = School::find($this->selectedSchoolId)->students()->with('trips', 'trips.transport1', 'trips.transport2')->get();

            $students->transform(function ($item) {
                $string = 'Da ' . $this->comuni[$item->town_istat]['comune'] . ' in ';
                $i = 0;

                foreach ($item->trips as $trip) {
                    if ($i != 0) {
                        $string .= ' -> ';
                    }
                    $string .= $trip->transport1->name;
                    $trip->transport2 ? $string .= '/' . $trip->transport2->name : $string .= '';
                    $string .= ' fino a ' . $this->comuni[$trip->town_istat]['comune'];
                    $i++;
                }
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

//    public function saveTrips($index, $tripNr)
//    {
//
//        $trip_arr = $this->students[$index][$tripNr] ?? NULL;
//
//        if (!is_null($trip_arr)) {
//            $trip = optional(Trip::find($trip_arr['id']));
//            $trip->update($trip_arr);
//            $trip->transports()->sync(array((int)$this->editingTripTransport));
//        }
//
//        $this->editStudentField = null;
//        $this->editStudentIndex = null;
//        $this->reloadStudents();
//    }

    public function getComuniProperty()
    {
        return Cache::get('comuni');
    }

    public function schoolChanged()
    {
        $this->students = School::find($this->selectedSchoolId)->students->toArray();
    }

    public static function cacheComuni()
    {
        return Cache::remember('comuni', 60 * 24, function () {
            $response = Http::withToken(config('openapi.towns_token'))
                ->retry(3, 100)
                ->throw()
                ->get('https://cap.openapi.it/cerca_comuni?provincia=piacenza');

            $arr = $response->json()['data']['result'];

            $response = Http::withToken('63cff56cabd5b551b243e868')
                ->retry(3, 100)
                ->throw()
                ->get('https://cap.openapi.it/cerca_comuni?provincia=parma');

            $arr = array_merge($arr, $response->json()['data']['result']);

            $response = Http::withToken(config('openapi.towns_token'))
                ->retry(3, 100)
                ->throw()
                ->get('https://cap.openapi.it/cerca_comuni?provincia=cremona');

            $arr = array_merge($arr, $response->json()['data']['result']);

            $arr = collect($arr)->keyBy('istat');

            return $arr;
        });
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

        if (!is_null($trip))
            optional(Trip::find($trip['id']))->update($trip);

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


}
