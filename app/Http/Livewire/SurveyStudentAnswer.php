<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Livewire\Component;

class SurveyStudentAnswer extends Component
{

    public int $surveyId;

    public function render()
    {
        return view('livewire.survey-student-answer');
    }

    public function mount(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function getSurveyProperty()
    {
        return Survey::find($this->surveyId);
    }

}
