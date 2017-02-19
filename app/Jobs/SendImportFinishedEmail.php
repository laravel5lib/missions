<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendImportFinishedEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $email;
    public $records;
    public $list;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $records, $list)
    {
        $this->email = $email;
        $this->records = $records;
        $this->list = $list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $email = $this->email;
        $list = strtolower(str_plural(class_basename($this->list)));
        $records = $this->records;

        $mailer->send('emails.imports.complete', ['list' => $list, 'records' => $records], function ($m) use ($email) {
            $m->to($email)->subject('Import Completed!');
        });
    }
}
