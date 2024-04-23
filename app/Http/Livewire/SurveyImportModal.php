<?php

namespace App\Http\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SurveyImportModal extends Component
{

    use LivewireAlert;

    public int $selectedSchoolId;

    public function render()
    {
        return view('livewire.survey-import-modal');
    }

    public function mount(int $selectedSchoolId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
    }
}
