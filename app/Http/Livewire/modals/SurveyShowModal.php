<?php

namespace App\Http\Livewire\Modals;

use App\Models\Survey;
use LivewireUI\Modal\ModalComponent;

class SurveyShowModal extends ModalComponent
{
    public int $selectedSurveyId;
    public function render()
    {
        return view('livewire.modals.survey-show-modal');
    }

    public function mount(int $selectedSurveyId)
    {
        $this->selectedSurveyId = $selectedSurveyId;
    }

    public function getSurveyProperty()
    {
        return Survey::find($this->selectedSurveyId);
    }
}
