<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\User;
use App\Models\v1\Report;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;
    protected $user;
    protected $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, $filename, User $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->filename = $filename.'_'.time();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Excel $excel, Report $report, Mailer $mail)
    {
        $content = $excel->create($this->filename, function($excel)
        {
            $excel->sheet('Report', function($sheet)
            {
                $sheet->fromArray($this->data);
                $sheet->freezeFirstRow();
            });
        })->string('csv');

        $path = $this->user->id ? 'export/reports/'.$this->user->id.'/' : 'export/reports/system/';
        $file = $this->filename.'.csv';

        Storage::disk('s3')->put($path.$file, $content, 'public');

        $report->create([
            'name' => $this->filename,
            'type' => 'csv',
            'source' => $path.$file,
            'user_id' => $this->user->id
        ]);

        $mail->send('emails.reports.export', ['file' => $this->filename.'.csv'], function ($message) {
            $message->to($this->user->email);
            $message->subject('Your report is ready!');
        });

    }
}
