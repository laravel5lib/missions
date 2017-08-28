<?php

namespace App\Services\Reports;

use App\Models\v1\Report;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class CSVReport
{
    protected $data;
    protected $user;
    protected $content;
    protected $filename;

    function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function make($filename)
    {
        $this->filename = $filename;

        $this->content = Excel::create($filename, function ($excel) {
            $excel->sheet('Report', function ($sheet) {
                $sheet->fromArray($this->data);
                $sheet->freezeFirstRow();
            });
        })->string('csv');

        $this->save();

        return $this;
    }

    public function save()
    {
        $path = $this->user->id ? 'export/reports/'.$this->user->id.'/' : 'export/reports/system/';
        $file = $this->filename.'.csv';

        Storage::disk('s3')->put($path.$file, $this->content, 'public');

        Report::create([
            'name' => $this->filename,
            'type' => 'csv',
            'source' => $path.$file,
            'user_id' => $this->user->id
        ]);
    }

    public function notify()
    {
        Mail::send('emails.reports.export', ['file' => $this->filename.'.csv'], function ($message) {
            $message->to($this->user->email);
            $message->subject('Your report is ready!');
        });
    }
}
