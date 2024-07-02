<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendQrCodeParentNotification extends Notification
{
    use Queueable;

    public Student $student;
    public mixed $pdf;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Student $student, $pdf = null,)
    {
        $this->student = $student;
        $this->pdf = $pdf;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Codice di accesso')
            ->from('noreply@mobilityamoci.it', 'Pedibus')
            ->greeting(new HtmlString('Ciao! In allegato troverai il documento per accedere sull\'app pedibus relativo a ' . $this->student->name . '.'))
            ->salutation('A presto!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
