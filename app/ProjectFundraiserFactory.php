<?php
namespace App;

use App\Models\v1\Project;

class ProjectFundraiserFactory 
{
    protected $project;
    
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function make()
    {
        return [
            'type' => $this->project->initiative->cause->name,
            'goal_amount' => $this->project->costs()->sum('amount')/100,
            'sponsor_id' => $this->project->sponsor_id,
            'sponsor_type' => $this->project->sponsor_type,
            'started_at' => \Carbon\Carbon::today()->toDateTimeString(),
            'ended_at' => $this->getDeadline()
        ];
    }

    private function getDeadline()
    {
        $cost = $this->project->costs()->with('payments')->get();
        
        return $cost->pluck('payments')->flatten()->last()->due_at->toDateTimeString();
    }
}