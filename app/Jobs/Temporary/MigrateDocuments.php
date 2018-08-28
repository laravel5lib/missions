<?php

namespace App\Jobs\Temporary;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MigrateDocuments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $chunks = DB::table('reservation_requirements')
                ->whereNotNull('document_id')
                ->get(['reservation_id', 'document_type', 'document_id'])
                ->map(function ($doc) {
                    return [
                        'reservation_id' => $doc->reservation_id, 
                        'documentable_type' => $doc->document_type, 
                        'documentable_id' => $doc->document_id
                    ];
                })
                ->chunk(100)
                ->toArray();

        foreach($chunks as $chunk) {
            DB::table('reservation_documents')->insert($chunk);
        }
    }
}
