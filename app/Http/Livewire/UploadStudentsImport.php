<?php

namespace App\Http\Livewire;

use App\Imports\StudentsImport;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadStudentsImport extends ModalComponent
{

    use WithFileUploads;

    public $importFile;
    public int $selectedSectionId;

    public function mount(int $selectedSectionId)
    {
        $this->selectedSectionId = $selectedSectionId;
    }

    public function render()
    {
        return view('livewire.upload-students-import');
    }

    public function submitImport()
    {
        $this->validate([
            'importFile' => 'file|required'
        ]);


        \Maatwebsite\Excel\Facades\Excel::import(new StudentsImport($this->selectedSectionId), $this->importFile);

    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(public_path('templates/template_studenti.xlsx'));
    }
}
