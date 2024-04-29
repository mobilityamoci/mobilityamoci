<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Livewire\Component;

class SurveysEntries extends Component
{

    public int $surveyId;

    public function render()
    {
        return view('livewire.surveys-entries');
    }

    public function mount($surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function getSelectedSurveyProperty()
    {
        return Survey::find($this->surveyId);
    }
}
