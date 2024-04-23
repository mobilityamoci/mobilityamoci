<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Survey;
use App\Traits\SelectedSchool;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Surveys extends Component
{
    use LivewireAlert, SelectedSchool;

    public $user;
    public Collection $schools;
    public $selectedSchoolId;
    public int $selectedSurveyId;

    public bool $showSurveyModal = false;

    protected $queryString = [
        'selectedSchoolId' => ['except' => 1, 'as' => 'scuola'],
    ];

    public function render()
    {
        return view('livewire.surveys');
    }

    public function mount()
    {
        $this->user = \Auth::user();
        $this->schools = getUserSchools(true);
        $this->selectedSchoolId = $this->selectedSchoolId ?? optional($this->schools->first())->id;
    }


    public function getSelectedSurveyProperty()
    {
        return Survey::find($this->selectedSurveyId);
    }

    public function getSurveysProperty()
    {
        return $this->selectedSchool->surveys;

    }

    public function copyUuid()
    {
        $this->alert('success', 'Codice copiato!');
    }
}
