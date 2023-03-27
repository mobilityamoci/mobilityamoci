<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SingleStudentEdit extends Component
{

    use LivewireAlert;

    public User $user;

    public ?Student $student;

    public $sections;

    public $transports;

    public bool $addingTrip = false;

    public $newTripTrans1;
    public $newTripTrans2;
    public $newTripIstat;

    protected $rules = [
        'student.name' => 'required|string',
        'student.surname' => 'required|string',
        'student.address' => 'required|string',
        'student.section_id' => 'required|integer|exists:sections,id',
        'student.town_istat' => 'required|integer',
        'student.trips.*.transport_1' => 'required|integer|exists:transports,id',
        'student.trips.*.transport_2' => 'integer|nullable',
        'student.trips.*.town_istat' => 'nullable|integer'
    ];

    protected $validationAttributes = [
        'student.name' => 'nome',
        'student.surname' => 'cognome',
        'student.address' => 'indirizzo',
        'student.section_id' => 'sezione',
        'student.town_istat' => 'comune di domicilio',
        'student.trips.*.transport_1' => 'primo mezzo di trasporto',
        'student.trips.*.transport_2' => 'secondo mezzo di trasporto',
        'student.trips.*.town_istat' => 'comune di arrivo'
    ];


    public function mount()
    {
        $this->alert('success', 'Viaggio aggiornato');

        $this->user = auth()->user();
        $this->student = $this->user->student;
        $this->sections = $this->user->schools->first()->sections;
        $this->transports = Transport::all();
    }

    public function render()
    {
        return view('livewire.single-student-edit');
    }

    public function reloadTrips()
    {
        $this->trips = Trip::where('student_id', $this->student['id'])->get()->toArray();
    }


    public function getComuniProperty()
    {
        return Cache::get('comuni');
    }

    public function saveTrip($index)
    {
        $this->validate([
            'student.trips.*.transport_1' => 'required|integer|exists:transports,id',
            'student.trips.*.transport_2' => 'integer|nullable',
            'student.trips.*.town_istat' => 'nullable|integer'
        ]);
        $trip = $this->student->trips[$index];

        if (!is_null($trip)) {
            $trip['transport_2'] = empty($trip['transport_2']) ? NULL : $trip['transport_2'];
            $trip['town_istat'] = empty($trip['town_istat']) ? NULL : $trip['town_istat'];
            $trip->save();
        }
        $this->alert('success', 'Viaggio aggiornato');
    }

    public function saveStudent()
    {
        $this->validate([
            'student.name' => 'required|string',
            'student.surname' => 'required|string',
            'student.address' => 'required|string',
            'student.section_id' => 'required|integer|exists:sections,id',
            'student.town_istat' => 'required|integer',
        ]);
        $this->student->save();
        $this->alert('success', 'Dati aggiornati');

    }

    public function addNewTrip()
    {
        $this->validate([
            'newTripTrans1' => 'required|integer|exists:transports,id',
            'newTripTrans2' => 'integer|nullable',
            'newTripIstat' => 'nullable|integer'
        ]);

        $order = Student::find($this->student['id'])->trips()->max('order');
        $order++;

        Trip::create([
            'student_id' => $this->student['id'],
            'transport_1' => $this->newTripTrans1,
            'order' => $order,
            'transport_2' => $this->newTripTrans2,
            'town_istat' => $this->newTripIstat,
        ]);

        $this->addingTrip = false;
        $this->alert('success', 'Tappa Aggiunta');
        $this->mount();
    }

    public function deleteTrip($index)
    {
        $trip = $this->student->trips[$index];

        if (!is_null($trip)) {
            $trip->delete();
        }
        $this->mount();
        $this->alert('success', 'Tappa Eliminata');
    }



}
