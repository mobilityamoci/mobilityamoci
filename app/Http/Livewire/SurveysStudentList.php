<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Auth;
use Livewire\Component;

class SurveysStudentList extends Component
{
    public function render()
    {
        return view('livewire.surveys-student-list');
    }

    public function mount($surveyId)
    {

    }

    public function answerSurvey(int $surveyId)
    {
        $this->emitTo('surveys-student', 'switch', 'survey-student-answer', $surveyId);
    }

    public function getSurveysProperty()
    {
        /* @var Student $student */
        $student = Auth::user()->student;
        return $student->surveys()->get()->filter(function ($survey) {
            return !in_array($survey->id, $this->submittedSurveys->pluck('id')->toArray());
        });
    }

    public function getSubmittedSurveysProperty()
    {
        /* @var Student $student */
        $student = Auth::user()->student;
        return $student->submittedSurveys()->get();
    }
}
