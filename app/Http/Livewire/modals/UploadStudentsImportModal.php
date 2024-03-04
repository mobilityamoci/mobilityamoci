<?php

namespace App\Http\Livewire\modals;

use App\Imports\StudentsImport;
use App\Imports\WholeSchoolStudentImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadStudentsImportModal extends ModalComponent
{

    use WithFileUploads, LivewireAlert;

    public $importFile;
    public int $selectedSectionId;
    public int $selectedSchoolId;
    public $importErrors;
    public $importFailures;

    public function mount(int $selectedSectionId, int $selectedSchoolId)
    {
        $this->selectedSectionId = $selectedSectionId;
        $this->selectedSchoolId = $selectedSchoolId;
    }

    public function render()
    {
        return view('livewire.generic-import-modal');
    }

    public function submitImport()
    {
        $this->validate([
            'importFile' => 'file|required'
        ]);

        try {
            $this->alert('warning', 'Inizio caricamento!');
            $import = (new WholeSchoolStudentImport($this->selectedSchoolId));
            $import->import($this->importFile);
            $this->alert('success', 'Caricamento finito!');
            $this->importErrors = $import->errors();
            $this->importFailures = $import->failures();
            if ($this->importErrors->isEmpty() && $this->importFailures->isEmpty()) {
                $this->emit('closeModal');
            }
        } catch (\Exception $exception) {
            \Log::error($exception);
            $this->alert('error', 'File con formato sbagliato!');
        }
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(public_path('templates/template_studenti_scuola_intera.xlsx'));
    }
}
