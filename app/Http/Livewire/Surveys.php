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
    public int $selectedSurveyId;
    private $component = '';
    protected $listeners = [
        'switch'
    ];
    public function render()
    {
        return view('livewire.surveys', ['component' => $this->component]);
    }

    public function mount(string $component = 'surveys-table')
    {
        $this->component = $component;
    }

    public function switch(string $component, int $surveyId)
    {
        $this->component = $component;
        $this->selectedSurveyId = $surveyId;
    }

}
