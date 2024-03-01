<?php

namespace App\Http\Livewire\modals;

use App\Imports\StudentsImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadStudentsImportModal extends ModalComponent
{

    use WithFileUploads, LivewireAlert;

    public $importFile;
    public int $selectedSectionId;
    public $importErrors;
    public $importFailures;

    public function mount(int $selectedSectionId)
    {
        $this->selectedSectionId = $selectedSectionId;
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
            $import = (new StudentsImport($this->selectedSectionId));
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
        return response()->download(public_path('templates/template_studenti.xlsx'));
    }
}
