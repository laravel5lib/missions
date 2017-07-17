<?php

namespace App\Models\v1;

use App\CampaignTransport;
use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'reservation_id', 'transport_id',
        'seat_no'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function transport()
    {
        return $this->belongsTo(Transport::class);
    }

    public function campaignTransport()
    {
        return $this->belongsTo(CampaignTransport::class, 'transport_id');
    }

    public function transportCompanions()
    {
        return $this->reservation
            ->companionReservations()
            ->whereHas('transports', function ($transport) {
                return $transport->where('transports.id', $this->transport_id);
            })
            ->get([
                'reservations.id',
                'reservations.given_names',
                'reservations.surname'
            ]);
    }

    public function validate($transportId, $reservationId)
    {
        $transport = Transport::with('passengers')->find($transportId);

        if (in_array($reservationId, $transport->passengers->pluck('reservation_id')->all()))
            abort(422, 'Passenger is already on this transport.');

        $reservation = Reservation::find($reservationId);
        $designations = $reservation->transports->pluck('designation')->all();

        if (in_array($transport->designation, $designations))
            abort(422, "Passenger is already on a $transport->designation transport");

        return true;
    }
}
