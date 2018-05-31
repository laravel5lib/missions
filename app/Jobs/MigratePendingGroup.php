<?php

namespace App\Jobs;

use App\Models\v1\Lead;
use App\Models\v1\Group;
use App\Models\v1\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigratePendingGroup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        $note = $this->group->notes->first();

        if ($note) {
            $note = str_replace('Contact: ', '', $note->content);
            $note = str_replace('Position: ', '', $note);
            $note = str_replace('Spoken with MM Rep: ', '', $note);
            $note = str_replace('Campaign of Interest: ', '', $note);

            $array = explode(',', $note);

            $contact = isset($array[0]) ? trim($array[0]) : null;
            $position = isset($array[1]) ? trim($array[1]) : null;
            $spoke = isset($array[2]) ? trim($array[2]) : 'no';
            
            $campaign = isset($array[3]) ? optional(Campaign::whereId(trim($array[3]))->first())->name : 'null';
        }

        $lead = Lead::create([
            'category_id' => 1,
            'content' => [
                'organization' => $this->group->name,
                'type' => $this->group->type,
                'country' => country($this->group->country_code),
                'address_one' => $this->group->address_one,
                'address_two' => $this->group->address_two,
                'city' => $this->group->city,
                'state' => $this->group->state,
                'zip' => $this->group->zip,
                'phone_one' => $this->group->phone_one,
                'phone_two' => $this->group->phone_two,
                'email' => $this->group->email,
                'contact' => $contact ?? null,
                'position' => $position ?? null,
                'spoke_with_rep' => $spoke ?? 'no',
                'campaign_of_interest' => $campaign ?? null
            ],
            'created_at' => $this->group->created_at
        ]);
    }
}
