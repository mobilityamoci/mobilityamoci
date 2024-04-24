<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Auth;
use Livewire\Component;

class SurveysStudent extends Component
{
    public int $selectedSurveyId;
    private $component = '';
    protected $listeners = [
        'switch'
    ];

    public function render()
    {
        return view('livewire.surveys-student', ['component' => $this->component])->layout('layouts.student-layout');
    }

    public function mount(string $component = 'surveys-student-list')
    {
        $this->component = $component;
    }

    public function switch(string $component, int $selectedSurveyId = null)
    {
        $this->selectedSurveyId = $selectedSurveyId;
        $this->component = $component;
    }



}
