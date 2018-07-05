<?php

namespace App\Notifications;

use App\Models\v1\Squad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SquadPublished extends Notification implements ShouldQueue
{
    use Queueable;

    protected $squad;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Squad $squad)
    {
        $this->squad = $squad;
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
        $url = url("dashboard/reservations/$notifiable->id/squad");

        return (new MailMessage)
                    ->subject('Squad Assignment')
                    ->markdown('emails.squads.published', [
                        'name' => $notifiable->name, 
                        'url' => $url,
                        'squad' => $this->squad,
                        'region' => $this->squad->region
                    ]);
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
