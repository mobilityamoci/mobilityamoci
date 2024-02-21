<?php

namespace App\Http\Livewire;

use App\Charts\SchoolTypeOfTransportStudentChart;
use App\Models\School;
use App\Models\Transport;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ShowGraphs extends Component
{

    use LivewireAlert;

    private $chartVar;

    public User $user;

    public $selectedSchoolId;

    public Collection $schools;

    public $transports;

    protected $queryString = [
        'selectedSchoolId' => ['except' => 1, 'as' => 'scuola'],
    ];


    public function render()
    {
        return view('livewire.show-graphs');
    }

    public function mount(SchoolTypeOfTransportStudentChart $chart)
    {
        $this->user = \Auth::user();
        if ($this->user->can('all_schools')) {
            $this->schools = School::all();
        } else {
            $this->schools = $this->user->schools;
        }
        $this->selectedSchoolId = $this->selectedSchoolId ?? optional($this->schools->first())->id;
        $this->transports = Transport::all()->keyBy('id')->toArray();

    }

    public function refresh()
    {
        return redirect()->route('graphs-show', ['scuola' => $this->selectedSchoolId]);
    }

    public function getChartProperty()
    {
        $this->chartVar = new SchoolTypeOfTransportStudentChart($this->selectedSchool, new LarapexChart());
        return $this->chartVar->build();
    }

    public function getSelectedSchoolProperty()
    {
        return School::find($this->selectedSchoolId);
    }


}
