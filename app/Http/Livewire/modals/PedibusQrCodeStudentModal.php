<?php

namespace App\Http\Livewire\Modals;

use App\Models\Student;
use App\Notifications\SendQrCodeParentNotification;
use App\Services\PedibusService;
use Illuminate\Support\Facades\Notification;
use LivewireUI\Modal\ModalComponent;

class PedibusQrCodeStudentModal extends ModalComponent
{

    public $student_id;

    public string $emails = '';

    public function render()
    {
        return view('livewire.modals.pedibus-qr-code-student-modal');
    }

    public function mount($student_id)
    {
        $this->student_id = $student_id;
    }

    public function sendMail()
    {
        $this->validate(['emails' => 'string|required']);
        //explode $this->emails based on ',' and trim each element
        $emailsArr = array_map('trim', explode(',', $this->emails));
        foreach ($emailsArr as $email) {
            Notification::route('mail', $email)
                ->notify(new SendQrCodeParentNotification($this->student));
        }
        $this->dispatchBrowserEvent('close-modal');
    }

    public function getStudentProperty()
    {
        return Student::find($this->student_id);
    }

    public function getQrCodeProperty()
    {
        return base64_encode(PedibusService::generateQrCodePdf($this->student)->output());
    }
}
