<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\v1\Fundraiser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadFeaturedImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Fundraiser::whereUuid($this->image->uploadable_id)
            ->first()
            ->clearMediaCollection('featured')
            ->addMediaFromUrl('https://missions.me/api/'.$this->image->source)
            ->usingFileName('featured_photo.jpg')
            ->toMediaCollection('featured');
    }
}
