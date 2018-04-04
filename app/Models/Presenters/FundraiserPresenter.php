<?php
namespace App\Models\Presenters;

trait FundraiserPresenter
{
    public function getLocationAttribute(): string
    {
        if ($this->fund->fundable_type === 'reservations') {
            return country($this->fund->fundable->trip->country_code);
        }

        if ($this->fund->fundable_type === 'projects') {
            return country($this->fund->fundable->initiative->country_code);
        }
    }

    public function getTypeAttribute() : string
    {
        if (in_array($this->fund->fundable_type, ['reservations', 'trips', 'campaigns'])) {
            return 'Mission Trip';
        }

        if (in_array($this->fund->fundable_type, ['projects'])) {
            return $this->fund->fundable->initiative->cause->name;
        }
    }

    public function getDollarsRaisedAttribute() : string
    {
        return '$' . number_format($this->raisedAsDollars(), 2);
    }

    public function getDollarsGoalAttribute() : string
    {
        return '$' . number_format($this->goalAmountAsDollars(), 2);
    }
}
