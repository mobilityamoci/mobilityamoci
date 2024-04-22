<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Survey;
use Illuminate\Support\Collection;
use Livewire\Component;

class Surveys extends Component
{

    public $user;
    public Collection $schools;
    public  $selectedSchoolId;
    public int $selectedSurveyId;

    public bool $showSurveyModal = false;


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

    public function schoolChanged()
    {
        $this->selectedSectionId = optional(optional($this->sections)->first())->id;
    }

    public function getSelectedSchoolProperty()
    {
        return School::with('surveys')->find($this->selectedSchoolId);
    }

    public function getSelectedSurveyProperty()
    {
        return Survey::find($this->selectedSurveyId);
    }

    public function getSurveysProperty()
    {
        return $this->selectedSchool->surveys;

    }
}
