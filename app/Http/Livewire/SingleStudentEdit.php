<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
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
    public $newTripIstat = 0;
    public ?string $newTripAddress = '';

    protected $rules = [
        'student.name' => 'required|string',
        'student.surname' => 'required|string',
        'student.address' => 'required|string',
        'student.section_id' => 'required|integer',
        'student.town_istat' => 'required|integer',
        'student.trips.*.transport_1' => 'required|integer|exists:transports,id',
        'student.trips.*.address' => 'nullable|string',
        'student.trips.*.town_istat' => 'nullable|integer'
    ];

    protected $validationAttributes = [
        'student.name' => 'nome',
        'student.surname' => 'cognome',
        'student.address' => 'indirizzo',
        'student.section_id' => 'sezione',
        'student.town_istat' => 'comune di domicilio',
        'student.trips.*.transport_1' => 'primo mezzo di trasporto',
//        'student.trips.*.transport_2' => 'secondo mezzo di trasporto',
        'student.trips.*.address' => 'indirizzo scalo',
        'student.trips.*.town_istat' => 'comune scalo'
    ];

    public function render()
    {
        return view('livewire.single-student-edit');
    }


    public function mount()
    {
        $this->alert('success', 'Viaggio aggiornato');

        $this->user = auth()->user();
        $this->student = $this->user->student;
        $this->sections = $this->user->schools->first()->sections;
        $this->transports = Transport::all();
    }

    public function reloadTrips()
    {
        $this->trips = Trip::where('student_id', $this->student['id'])->get()->toArray();
    }


    public function getComuniProperty()
    {
        return getComuniArray();
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
        $this->alert('success', 'Dati aggiornati');
        $this->validate([
            'student.name' => 'required|string',
            'student.surname' => 'required|string',
            'student.address' => 'required|string',
            'student.section_id' => 'required|integer',
            'student.town_istat' => 'required|integer',
        ]);
        $this->student->save();

    }

    public function test()
    {
        $this->alert('error', 'Dati aggiornati');

    }

    public function addNewTrip()
    {
        $this->validate([
            'newTripTrans1' => 'required|integer|exists:transports,id',
            'newTripAddress' => 'nullable|string',
            'newTripIstat' => 'nullable|integer'
        ]);

        $order = Student::find($this->student['id'])->trips()->max('order');
        $order++;

        if ($this->newTripIstat == 0) {
            $this->newTripAddress = $this->student->section->building->address;
            $this->newTripIstat = $this->student->section->building->town_istat;
        }

        Trip::create([
            'student_id' => $this->student['id'],
            'transport_1' => $this->newTripTrans1,
            'address' => $this->newTripAddress,
            'order' => $order,
            'town_istat' => $this->newTripIstat,
        ]);

        $this->newTripIstat = null;
        $this->newTripAddress = null;
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
        $this->alert('success', 'Tappa Eliminata');
        $this->mount();
    }

    public function getStudentTripsProperty()
    {
        return collect($this->student->trips);
    }


    public function getCanAddTripProperty()
    {
        return $this->studentTrips->isEmpty();
//        if ($this->studentTrips->isNotEmpty()) {
//            return !$this->studentTrips->search(function ($item) {
//                return $item->town_istat == 0;
//            });
//        } else {
//            return true;
//        }
    }


    //______________________________________________ROBE MAPPA__________________________________________

    public function getWGS84PointProperty()
    {
        return $this->student->geometryPoint->getWGS84Point();
    }

    public function getWGS84SchoolPointProperty()
    {
        return $this->student->schoolGeometryPoint->getWGS84Point();
    }

    public function getStudentGeometryLineProperty()
    {
        return $this->student->trip1->geometryLine->toArrayWGS84();
    }

    public function getStudentPointLatProperty()
    {
        return $this->WGS84Point->getLatitude();
    }

    public function getStudentPointLonProperty()
    {
        return $this->WGS84Point->getLongitude();
    }

    public function getCenterPointProperty()
    {
        return ['lat' => $this->studentPointLat, 'lon' => $this->studentPointLon];
    }

    public function getSchoolProperty()
    {
        return $this->user->schools->first();
    }

    public function getSchoolPointLonProperty()
    {
        return $this->WGS84SchoolPoint->getLongitude();

    }

    public function getSchoolPointLatProperty()
    {
        return $this->WGS84SchoolPoint->getLatitude();
    }

    public function getSchoolCenterPointProperty()
    {
        return ['lat' => $this->schoolPointLat, 'lon' => $this->schoolPointLon];

    }

    public function getMarkersProperty()
    {
        return [array_merge($this->centerPoint, ['title' => 'Casa Mia']), array_merge($this->schoolCenterPoint, ['title' => 'Scuola'])];
    }

    public function getPolylinesProperty()
    {
        return [['points' => $this->studentGeometryLine]];
    }


}
