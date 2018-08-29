<?php

namespace App\Jobs\Temporary;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateRequirementsToRequireables implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('requireables')->insert(
            
            DB::table('requirements')
                ->get(['id', 'requester_id', 'requester_type', 'created_at', 'updated_at'])
                ->map(function ($req) {
                    return [
                        'requirement_id' => $req->id, 
                        'requireable_id' => $req->requester_id, 
                        'requireable_type' => $req->requester_type,
                        'created_at' => $req->created_at,
                        'updated_at' => $req->updated_at
                    ];
                })->all()
        );
    }
}
