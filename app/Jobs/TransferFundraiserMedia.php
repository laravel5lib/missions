<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Jobs\UploadFeaturedImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TransferFundraiserMedia implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
        select
        uploadables.uploadable_id,
        uploads.source
        from uploads
        join uploadables on uploadables.upload_id = uploads.id and uploadables.uploadable_type = 'fundraisers'
        where uploads.type = 'other'
        group by uploadables.uploadable_id 
        */

        $media = DB::table('uploads')
            ->join('uploadables', function ($join) {
                $join->on('uploads.id', '=', 'uploadables.upload_id')
                     ->where('uploadables.uploadable_type', '=', 'fundraisers');
            })
            ->join('fundraisers', function ($join) {
                $join->on('uploadables.uploadable_id', '=', 'fundraisers.uuid')
                     ->whereNull('fundraisers.deleted_at');
            })
            ->where('uploads.type', '=', 'other')
            ->groupBy('uploadables.uploadable_id')
            ->take(3)
            ->orderBy('uploads.source')
            ->select('uploadables.uploadable_id', 'uploads.source')
            ->get();

        $media->each( function($image) {
            dispatch(new UploadFeaturedImage($image));
        });
    }
}
