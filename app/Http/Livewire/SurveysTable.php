<?php

namespace App\Http\Livewire;

use App\Exports\StatisticsSectionExport;
use App\Exports\SurveyEntriesExport;
use App\Models\Survey;
use App\Traits\SelectedSchool;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SurveysTable extends Component
{
    use LivewireAlert, SelectedSchool;

    public $user;
    public Collection $schools;
    public $selectedSchoolId;

    public bool $showSurveyModal = false;

    protected $queryString = [
        'selectedSchoolId' => ['except' => 1, 'as' => 'scuola'],
    ];

    public function render()
    {
        return view('livewire.surveys-table');
    }

    public function mount($surveyId)
    {
        $this->user = \Auth::user();
        $this->schools = getUserSchools(true);
        $this->selectedSchoolId = $this->selectedSchoolId ?? optional($this->schools->first())->id;
    }


    public function getSurveysProperty()
    {
        return $this->selectedSchool->surveys;

    }

    public function copyUuid()
    {
        $this->alert('success', 'Codice copiato!');
    }

    public function goToEntries(int $surveyId)
    {
        $this->emitTo('surveys', 'switch', 'surveys-entries', $surveyId);
    }

    public function downloadEntriesExport(int $surveyId): BinaryFileResponse
    {
        $survey = Survey::findOrFail($surveyId);
        $this->alert('success','Download iniziato');
        return Excel::download(new SurveyEntriesExport($survey), sanitize($survey->name).'_risposte.xlsx');
    }
}
