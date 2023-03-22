<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AcceptedUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('MobilityAmoci - La tua richiesta è stata accettata')
            ->from('noreply@mobilityamoci.it')
            ->greeting(new HtmlString('Ciao! La tua richiesta di ammissione al sito MobilityAmoci è stata accettata.'))
                    ->line('Clicca il bottone qui sotto, accedi con le tue credenziali e compila il tuo viaggio!.')
                    ->action('Vai al Sito!', url('/'))
            ->salutation(new HtmlString('A presto,<br> il team MobilityAmoci.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
