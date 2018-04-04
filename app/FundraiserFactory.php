<?php
namespace App;

use App\Models\v1\Fund;

class FundraiserFactory 
{

    protected $fund;

    public function __construct(Fund $fund)
    {
        $this->fund = $fund;
    }

    public function build()
    {
        $defaults = [
            'name' => null,
            'url' => null,
            'fund_id' => $this->fund->id,
            'public' => false,
            'show_donors' => true,
            'description' => null,
            'short_desc' => null,
            'started_at' => \Carbon\Carbon::today(),
            'ended_at' => null,
            'sponsor_id' => null,
            'sponsor_type' => null,
            'goal_amount' => null,
            'type' => 'Fundraiser',
            'featured_image_id' => null
        ];
        
        return array_merge($defaults, $this->getFactory()->make());
    }

    private function getFactory()
    {
        if ($this->fund->fundable_type === 'reservations') {
            return new ReservationFundraiserFactory($this->fund->fundable);
        }

        if ($this->fund->fundable_type === 'projects') {
            return new ProjectFundraiserFactory($this->fund->fundable);
        }

        return [];
    }
}