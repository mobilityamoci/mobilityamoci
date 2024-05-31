<?php

namespace App\Http\Livewire\Modals;

use App\Models\Survey;
use App\Traits\SelectedSchool;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class SurveyImportModal extends ModalComponent
{

    use LivewireAlert, SelectedSchool;

    public int $selectedSchoolId;
    public string $uuid = '';

    protected $rules = [
        'uuid' => 'uuid|required'
    ];

    public function render()
    {
        return view('livewire.survey-import-modal');
    }

    public function mount(int $selectedSchoolId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
    }

    public function importSurvey()
    {
        $this->validate();
        $survey = Survey::where('uuid', $this->uuid)->first();

        if ($survey->school_id == $this->selectedSchoolId) {
            $this->alert('error', 'Sondaggio già presente nella scuola!');
        } else {
            $newSurvey = $survey->replicate(['school_id', 'uuid']);
            $newSurvey->school_id = $this->selectedSchoolId;
            $newSurvey->save();

            $questions = $survey->questions()->get();

            foreach ($questions as $question)
            {
                $newQ = $question->replicate(['survey_id']);
                $newQ->survey_id = $newSurvey->id;
                $newQ->save();
            }

            $this->alert('success', 'Sondaggio importato con successo.');
            $this->emitUp('$refresh');
            $this->emit('closeModal');
        }

    }
}
