<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CoordinatorScoreboard extends Notification implements ShouldQueue
{
    use Queueable;

    protected $scores;
    protected $rank;
    protected $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $scores, $rank, $details)
    {
        $this->scores = $scores;
        $this->rank = $rank;
        $this->details = $details;
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
            ->subject('Weekly Progress Report: Registrations')
            ->markdown(
                'emails.coordinators.scoreboard', 
                [
                    'scores' => $this->scores, 
                    'url' => url('coordinators'), 
                    'rank' => $this->rank, 
                    'percentage' => $this->details['percentage'],
                    'campaign' => $this->details['campaign']['name'],
                    'commitment' => $this->details['commitment'],
                    'reservations' => $this->details['reservations']
                ]
            );
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
