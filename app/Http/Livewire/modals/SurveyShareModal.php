<?php

namespace App\Http\Livewire\modals;

use App\Models\School;
use App\Models\Section;
use App\Models\Survey;
use App\Traits\SelectedSchool;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class SurveyShareModal extends ModalComponent
{

    use LivewireAlert, SelectedSchool;
    public $selectedSchoolId;
    public $selectedSurveyId;
    public array $selectedSectionIds;
    public function render()
    {
        return view('livewire.modals.survey-share-modal');
    }

    public function mount(int $selectedSchoolId, int $selectedSurveyId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
        $this->selectedSurveyId = $selectedSurveyId;
        $surveyable = $this->selectedSurvey->sections;
        $this->selectedSectionIds = $surveyable ? $surveyable->pluck('id')->toArray() : [];
    }

    public function shareToSections()
    {
        $this->selectedSurvey->mySections()->detach();
        $this->selectedSurvey->mySections()->attach($this->selectedSections);
        $this->alert('success', 'Sondaggio condiviso con successo.');
        $this->emit('closeModal');
    }

    public function selectWholeSchool()
    {
        $this->selectedSectionIds = $this->selectedSchool->sections->pluck('id')->toArray();
    }


    public function getSelectedSurveyProperty()
    {
        return Survey::with('sections')->find($this->selectedSurveyId);
    }

    public function getSelectedSectionsProperty()
    {
        return Section::whereIn('id', $this->selectedSectionIds)->get();
    }
}
