<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\v1\FlightItinerary;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FlightItineraryPublished extends Notification
{
    use Queueable;

    protected $itinerary;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(FlightItinerary $itinerary)
    {
        $this->itinerary = $itinerary;
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
        $url = url("dashboard/reservations/$notifiable->id/squads");

        return (new MailMessage)
                    ->subject('Flight Itinerary')
                    ->markdown('emails.flights.published', [
                        'name' => $notifiable->name, 
                        'url' => $url, 
                        'itinerary' => $this->itinerary
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
