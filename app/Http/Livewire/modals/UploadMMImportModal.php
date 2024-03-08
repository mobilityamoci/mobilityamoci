<?php

namespace App\Http\Livewire\modals;

use App\Imports\SectionsSchoolImport;
use App\Imports\UsersRolesImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadMMImportModal extends ModalComponent
{
    use WithFileUploads, LivewireAlert;

    public $importFile;
    public function render()
    {
        return view('livewire.generic-import-modal', ['title' => 'Carica Utenti']);
    }


    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(public_path('templates/template_utenti.xlsx'));
    }

    public function submitImport()
    {
        $this->validate([
            'importFile' => 'file|required'
        ]);

        try {
            $this->alert('success', 'Inizio caricamento!');
            $import = (new UsersRolesImport())->import($this->importFile);
            $this->importErrors = $import->errors();
            $this->importFailures = $import->failures();
            if ($this->importErrors->isEmpty() && $this->importFailures->isEmpty()) {
                $this->emit('closeModal');
            } else {
                $this->alert('error', 'Non tutti i dati sono stati importati!');
            }
        } catch (\Exception $exception) {
            \Log::error($exception);
            $this->alert('error', 'File con formato sbagliato!');
        }
    }
}
