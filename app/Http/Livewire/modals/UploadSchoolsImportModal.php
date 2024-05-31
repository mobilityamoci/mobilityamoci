<?php

namespace App\Http\Livewire\Modals;

use App\Imports\SchoolsBuildingsImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadSchoolsImportModal extends ModalComponent
{
    use  WithFileUploads, LivewireAlert;

    public $importFile;


    public function render()
    {
        return view('livewire.generic-import-modal', ['title' => 'Carica scuole con sedi']);
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(public_path('templates/template_scuole.xlsx'));
    }

    public function submitImport()
    {
        $this->validate([
            'importFile' => 'file|required'
        ]);

        try {
            $this->alert('success', 'Inizio caricamento!');
            $import = (new SchoolsBuildingsImport())->import($this->importFile);
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
}
