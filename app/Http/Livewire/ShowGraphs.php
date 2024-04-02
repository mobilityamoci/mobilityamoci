<?php

namespace App\Http\Livewire;

use App\Charts\CaloriesStudentGraph;
use App\Charts\PollutionStudentGraph;
use App\Charts\TypeOfTransportStudentChart;
use App\Exports\StatisticsSchoolExport;
use App\Exports\StatisticsSectionExport;
use App\Models\School;
use App\Models\Section;
use App\Models\Transport;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Excel;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ShowGraphs extends Component
{

    use LivewireAlert;

    public User $user;

    public $selectedSchoolId;
    public $selectedSectionId;

    public Collection $schools;

    public $transports;

    protected $queryString = [
        'selectedSchoolId' => ['except' => 0, 'as' => 'scuola'],
        'selectedSectionId' => ['except' => 0, 'as' => 'sezione'],
    ];

    protected $listeners = [
        'refresh'
    ];

    public function render()
    {
        return view('livewire.show-graphs');
    }

    public function mount()
    {
        $this->user = \Auth::user();
        $this->schools = getUserSchools(true);
        $this->selectedSchoolId = $this->selectedSchoolId ?? optional($this->schools->first())->id;
        $this->selectedSectionId = $this->selectedSectionId ?? 0;
        $this->transports = Transport::all()->keyBy('id')->toArray();

    }

    public function refresh()
    {
        return redirect()->route('graphs-show', ['scuola' => $this->selectedSchoolId, 'sezione' => $this->selectedSectionId]);
    }

    public function getChartTransportProperty()
    {
        if ($this->selectedSectionId)
            $chartVar = new TypeOfTransportStudentChart($this->selectedSchool, new LarapexChart(), [$this->selectedSectionId]);
        else
            $chartVar = new TypeOfTransportStudentChart($this->selectedSchool, new LarapexChart());
        return $chartVar->build();
    }

    public function getChartPollutionProperty()
    {
        if ($this->selectedSectionId)
            $chartVar = new PollutionStudentGraph($this->selectedSchool, new LarapexChart(), [$this->selectedSectionId]);
        else
            $chartVar = new PollutionStudentGraph($this->selectedSchool, new LarapexChart());
        return $chartVar->build();
    }

    public function getChartCaloriesProperty()
    {
        if ($this->selectedSectionId)
            $chartVar = new CaloriesStudentGraph($this->selectedSchool, new LarapexChart(), [$this->selectedSectionId]);
        else
            $chartVar = new CaloriesStudentGraph($this->selectedSchool, new LarapexChart());
        return $chartVar->build();
    }

    public function getSelectedSchoolProperty()
    {
        return School::find($this->selectedSchoolId);
    }

    public function getSelectedSectionProperty()
    {
        if ($this->selectedSectionId)
            return Section::find($this->selectedSectionId);
        else
            return null;
    }

    public function getSectionsProperty()
    {
        if ($this->user->hasAnyPermission(['all_schools', 'school', 'admin']))
            return Section::where('school_id', $this->selectedSchoolId)->get()->keyBy('id');
        else
            return $this->user->sections;
    }

    public function downloadExport()
    {

//        $this->emit('refresh');
        if ($this->selectedSectionId) {
            $trips = $this->selectedSection->trips;
            return Excel::download(new StatisticsSectionExport($trips), $this->selectedSection->name.'_mobilita.xlsx');
        } else {
            return Excel::download(new StatisticsSchoolExport($this->selectedSchool), str_replace(' ', '',$this->selectedSchool->name).'_mobilita.xlsx');
        }
    }


}
