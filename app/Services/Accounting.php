<?php
namespace App\Services;

use App\Models\v1\Trip;
use App\Models\v1\Project;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use App\Models\v1\ProjectCause;
use App\Models\v1\AccountingItem;
use App\Models\v1\AccountingClass;

class Accounting {
    
    protected $accountingClass;
    protected $accountingItem;

    function __construct()
    {
        $this->accountingClass = new AccountingClass;
        $this->accountingItem = new AccountingItem;
    }

    public function getOrMakeClass($instance)
    {
        if ($instance instanceof Reservation) {
            return $this->getOrMakeReservationAccountingClass($instance);
        }

        else if ($instance instanceof Trip) {
           return $this->getOrMakeTripAccountingClass($instance);
        }

        else if ($instance instanceof Campaign) {
            return $this->getOrMakeCampaignAccountingClass($instance);
        }

        else if ($instance instanceof Project) {
            return $this->getOrMakeProjectAccountingClass($instance);
        }

        else if ($instance instanceof ProjectCause) {
            return $this->getOrMakeCauseAccountingClass($instance);
        }

        else {
            throw new \Exception('Does not recognize instance type.');
        }
    }

    public function getOrMakeItem($instance)
    {
        if ($instance instanceof Reservation) {
            return $this->getOrMakeReservationAccountingItem($instance);
        }

        else if ($instance instanceof Trip) {
           return $this->getOrMakeTripAccountingItem($instance);
        }

        else if ($instance instanceof Campaign) {
            return $this->getOrMakeCampaignAccountingItem($instance);
        }

        else if ($instance instanceof Project) {
            return $this->getOrMakeProjectAccountingItem($instance);
        }

        else if ($instance instanceof ProjectCause) {
            return $this->getOrMakeCauseAccountingItem($instance);
        }

        else {
            throw new \Exception('Does not recognize instance type.');
        }
    }

    public function getOrMakeReservationAccountingClass(Reservation $reservation)
    {
        return $reservation->trip->campaign->fund->accountingClass;
    }

    public function getOrMakeTripAccountingClass(Trip $trip)
    {
        return $this->accountingClass->firstOrCreate([
            'name' => $trip->campaign
                           ->fund
                           ->accountingClass
                           ->name.':Team'
        ]);
    }

    public function getOrMakeCampaignAccountingClass(Campaign $campaign)
    {
        return $this->accountingClass->firstOrCreate([
            'name' => 'Missions.Me:'
                        .country($campaign->country_code)
                        .' - '
                        .$campaign->started_at->format('F Y')
        ]);
    }

    public function getOrMakeProjectAccountingClass(Project $project)
    {
        return $project->initiative->cause->fund->accountingClass;
    }

    public function getOrMakeCauseAccountingClass(ProjectCause $cause)
    {
        return $this->accountingClass->firstOrCreate([
            'name' => 'Angel House:'.str_plural($cause->name)
        ]);
    }

    public function getOrMakeReservationAccountingItem(Reservation $reservation)
    {
        return $reservation->trip->campaign->fund->accountingItem;
    }

    public function getOrMakeTripAccountingItem(Trip $trip)
    {
        return $this->accountingItem->firstOrCreate([
            'name' => 'Missionary Donation'
        ]);
    }

    public function getOrMakeCampaignAccountingItem(Campaign $campaign)
    {
        return $this->accountingItem->firstOrCreate([
            'name' => 'General Donation'
        ]);
    }

    public function getOrMakeProjectAccountingItem(Project $project)
    {
        return $this->accountingItem->firstOrCreate([
            'name' => $project->name .' - '. $project->initiative->cause->name
        ]);
    }

    public function getOrMakeCauseAccountingItem(ProjectCause $cause)
    {
        return $this->accountingItem->firstOrCreate([
            'name' => 'General Donation'
        ]);
    }

}