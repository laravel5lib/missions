<?php
namespace App\Models\Presenters;

trait ReservationPresenter
{
    public function getFullNameAttribute(): string
    {
        return $this->given_names . ' ' . $this->surname;
    }

    public function assignmentsArePublished()
    {
        return $this->squadsArePublished() || $this->roomsArePublished() || $this->regionsArePublished() ? true : false;
    }

    public function squadsArePublished()
    {
        return (boolean) $this->trip->campaign->publish_squads;
    }

    public function roomsArePublished()
    {
        return (boolean) $this->trip->campaign->publish_rooms;
    }

    public function regionsArePublished()
    {
        return (boolean) $this->trip->campaign->publish_regions;
    }

    public function transportsArePublished()
    {
        return (boolean) $this->trip->campaign->publish_transports;
    }

    public function internationalTransports()
    {
        return $this->transports()->whereNotNull('designation')->orderBy('depart_at')->get();
    }
}
