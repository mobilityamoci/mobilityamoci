<?php

namespace App\Http\Livewire;

use App\Charts\CaloriesStudentGraph;
use App\Charts\TypeOfTransportStudentChart;
use App\Exports\StatisticsSchoolExport;
use App\Exports\StatisticsSectionExport;
use App\Models\Archive;
use App\Models\Section;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
use App\Services\QgisService;
use App\Traits\SelectedSchool;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Auth;
use Excel;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ShowGraphs extends Component
{

    use LivewireAlert, SelectedSchool;

    public User $user;

    public $selectedSchoolId;
    public $selectedSectionId;

    public Collection $schools;

    public $transports;
    public $selectedArchiveId;

    protected $queryString = [
        'selectedSchoolId' => ['except' => 0, 'as' => 'scuola'],
        'selectedSectionId' => ['except' => 0, 'as' => 'sezione'],
        'selectedArchiveId' => ['except' => 0, 'as' => 'archivio']
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
        $this->user = Auth::user();
        $this->schools = getUserSchools(true);
        if (Auth::user()->hasAnyPermission(['admin', 'all_schools']))
            $this->selectedSchoolId = $this->selectedSchoolId ?? 0;
        else
            $this->selectedSchoolId = $this->selectedSchoolId ?? optional($this->schools->first())->id;
        $this->selectedSectionId = $this->selectedSectionId ?? 0;
        $this->selectedArchiveId = $this->selectedArchiveId ?? 0;
        $this->transports = Transport::all()->keyBy('id')->toArray();

    }

    public function refresh()
    {
        return redirect()->route('graphs-show', ['scuola' => $this->selectedSchoolId, 'sezione' => $this->selectedSectionId, 'archivio' => $this->selectedArchiveId]);
    }

    public function getChartTransportProperty()
    {
        if (!$this->selectedArchiveId) {
            if ($this->selectedSectionId)
                $chartVar = new TypeOfTransportStudentChart($this->selectedSchool, new LarapexChart(), [$this->selectedSectionId]);
            else
                $chartVar = new TypeOfTransportStudentChart($this->selectedSchool, new LarapexChart());
        } else {
            if ($this->selectedSectionId) {
                $data = collect($this->selectedArchive->graph_data->get($this->selectedSectionId));
            } else {
                $data = $this->selectedArchive->graph_data->collapse();
            }
            $chartVar = new TypeOfTransportStudentChart($this->selectedSchool, new LarapexChart(), data: $data);
        }
        return $chartVar->build();
    }

    public function getChartCaloriesProperty()
    {
        if (!$this->selectedArchiveId) {
            if ($this->selectedSectionId)
                $chartVar = new CaloriesStudentGraph($this->selectedSchool, new LarapexChart(), [$this->selectedSectionId]);
            else
                $chartVar = new CaloriesStudentGraph($this->selectedSchool, new LarapexChart());
        } else {
            if ($this->selectedSectionId) {
                $data = collect($this->selectedArchive->graph_data->get($this->selectedSectionId));
            } else {
                $data = $this->selectedArchive->graph_data->collapse();
            }
            $chartVar = new CaloriesStudentGraph($this->selectedSchool, new LarapexChart(), data: $data);
        }
        return $chartVar->build();
    }

    public function getPollutionArrayProperty()
    {
        if (!$this->selectedArchiveId) {
            if ($this->selectedSectionId)
                return QgisService::calculatePollutionAndCaloriesForSection($this->selectedSection);
            else {
                if ($this->selectedSchoolId)
                    return QgisService::calculatePollutionAndCaloriesForSchool($this->selectedSchool);
                else
                    return QgisService::calculatePollutionAndCaloriesForAllSchools();
            }
        } else {
            if ($this->selectedSectionId) {
                $data = collect($this->selectedArchive->graph_data->get($this->selectedSectionId));
            } else {
                $data = $this->selectedArchive->graph_data->collapse();
            }
            $trip_ids = $data->pluck('trip_id');
            $trips = Trip::withTrashed()->whereIn('id', $trip_ids)->get();
            return QgisService::calculatePollutionAndCaloriesForTripsSum($trips);
        }
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
        if (!$this->selectedArchiveId) {
            if ($this->user->hasAnyPermission(['all_schools', 'school', 'admin']))
                return Section::where('school_id', $this->selectedSchoolId)->get()->keyBy('id');
            else
                return $this->user->sections;
        } else {
            return Section::whereIn('id', $this->selectedArchive->graph_data->keys())->get()->keyBy('id');
        }
    }

    public function downloadExport()
    {

//        $this->emit('refresh');
        if ($this->selectedSectionId) {
            $trips = $this->selectedSection->trips;
            return Excel::download(new StatisticsSectionExport($trips), $this->selectedSection->name . '_mobilita.xlsx');
        } else {
            return Excel::download(new StatisticsSchoolExport($this->selectedSchool), str_replace(' ', '', $this->selectedSchool->name) . '_mobilita.xlsx');
        }
    }


    public function getArchivesProperty()
    {
        if ($this->selectedSchool)
            return $this->selectedSchool->archives->sortByDesc('created_at');
        else
            return collect();
    }

    public function getSelectedArchiveProperty()
    {
        if ($this->selectedArchiveId)
            return Archive::find($this->selectedArchiveId);
        else
            return null;
    }


}
