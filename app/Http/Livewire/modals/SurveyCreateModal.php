<?php

namespace App\Http\Livewire\modals;

use LivewireUI\Modal\ModalComponent;
use App\Models\Survey;

class SurveyCreateModal extends ModalComponent
{

    public int $selectedSchoolId;
    public string $surveyName = 'Inserisci nome';

    public array $questions = [1 => ['content' => 'Inserisci testo domanda', 'type' => 'text', 'options' => [1 => 'Risposta 1']]];
    public array $defaultQuestion = ['content' => 'Inserisci testo domanda', 'type' => 'text', 'options' => [1 => 'Risposta 1']];

    public function render()
    {
        return view('livewire.survey-create-modal');
    }

    public function mount(int $selectedSchoolId)
    {
        $this->questions = json_decode('{"1":{"content":"Domanda 1","type":"text","options":{"1":"Risposta 1"}},"2":{"content":"Domanda 2","type":"number","options":{"1":"Risposta 1"}},"3":{"content":"Domanda 3","type":"radio","options":{"1":"Risposta 1","2":"Risposta 2"}},"4":{"content":"Domanda 4","type":"multiselect","options":{"1":"Risposta 1","2":"Risposta 2","3":"Risposta 3","4":"Risposta 4"}}}', true);
        $this->selectedSchoolId = $selectedSchoolId;
    }

    public function getQuestionTypesProperty()
    {
        return [
            'Testo Libero' => 'text',
            'Numerico' => 'number',
            'Scelta Esclusiva' => 'radio',
            'Scelta Multipla' => 'multiselect'
        ];
    }


    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function removeOption($questionKey, $optionKey)
    {
        unset($this->questions[$questionKey]['options'][$optionKey]);
    }

    public function addOption($questionKey)
    {
        $optionKey = array_key_last($this->questions[$questionKey]['options']) + 1;
        $this->questions[$questionKey]['options'][$optionKey] = "Risposta $optionKey";
    }

    public function addQuestion($lastQuestionKey)
    {
        $questionKey = $lastQuestionKey + 1;
        $this->questions[$questionKey] = $this->defaultQuestion;
    }

    public function createSurvey()
    {
        $survey = Survey::create(['name' => $this->surveyName, 'school_id' => $this->selectedSchoolId]);
        $survey->questions()->createMany($this->questions);
        $this->emit('closeModal');
    }


}
