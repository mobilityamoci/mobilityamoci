<?php

namespace App\Http\Livewire\Modals;

use App\Imports\SchoolsBuildingsImport;
use App\Imports\SectionsSchoolImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SectionsSchoolImportModal extends ModalComponent
{

    use WithFileUploads, LivewireAlert;

    public int $selectedSchoolId;
    public $importFile;


    public function mount(int $selectedSchoolId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
    }

    public function render()
    {
        return view('livewire.generic-import-modal', ['title' => 'Carica '.config('custom.lang.section')]);
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(public_path('templates/template_sezioni.xlsx'));
    }

    public function submitImport()
    {
        $this->validate([
            'importFile' => 'file|required'
        ]);

        try {
            $this->alert('success', 'Inizio caricamento!');
            $import = (new SectionsSchoolImport($this->selectedSchoolId))->import($this->importFile);
            $this->emit('closeModal');
        } catch (\Exception $exception) {
            \Log::error($exception);
            $this->alert('error', 'File con formato sbagliato!');
        }
    }
}
