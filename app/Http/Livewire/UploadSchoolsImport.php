<?php

namespace App\Http\Livewire;

use App\Imports\SchoolsBuildingsImport;
use App\Imports\StudentsImport;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadSchoolsImport extends ModalComponent
{
    use WithFileUploads;

    public $importFile;


    public function render()
    {
        return view('livewire.upload-schools-import');
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
        Excel::import(new SchoolsBuildingsImport(), $this->importFile);
        dd('ei');
        $this->emit('closeModal');
    }
}
