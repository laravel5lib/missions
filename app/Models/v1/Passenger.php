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

    /*public function transportCompanions()
    {
        return $this->reservation->companionReservations()->whereHas('transports', function ($transport) {
            return $transport->where('transports.id', $this->getAttribute('transport_id'));
        });
    }*/
}
