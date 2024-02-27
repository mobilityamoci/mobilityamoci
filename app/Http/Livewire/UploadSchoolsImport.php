<?php

namespace App\Http\Livewire;

use App\Imports\SchoolsBuildingsImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UploadSchoolsImport extends ModalComponent
{
    use WithFileUploads, LivewireAlert;

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

        try {
            Excel::import(new SchoolsBuildingsImport(), $this->importFile);
            $this->emit('closeModal');
        } catch (\Exception $exception) {
            \Log::error($exception);
            $this->alert('error', 'File con formato sbagliato!');
        }
    }
}
