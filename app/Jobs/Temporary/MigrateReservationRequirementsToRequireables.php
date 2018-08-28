<?php

namespace App\Jobs\Temporary;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateReservationRequirementsToRequireables implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = DB::table('reservation_requirements')
            ->get(['requirement_id', 'reservation_id', 'status', 'created_at', 'updated_at'])
            ->map(function ($req) {
                return [
                    'requirement_id' => $req->requirement_id,
                    'requireable_type' => 'reservations',
                    'requireable_id' => $req->reservation_id,
                    'status' => $req->status,
                    'created_at' => $req->created_at,
                    'updated_at' => $req->updated_at
                ];
            })
            ->chunk(100)
            ->toArray();

        foreach($data as $chunk) {
            DB::table('requireables')->insert($chunk);
        }
    }
}
