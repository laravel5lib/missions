<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class Exporter extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var $fields
     */
    protected $fields;
    /**
     * @var
     */
    protected $email;
    /**
     * @var
     */
    protected $fileName;

    /**
     * Create a new job instance.
     *
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
        $this->fields = $request['fields'];
        $this->email = $request['email'];
        $this->fileName = $request['filename'] ? snake_case($request['filename'] .'_'. time()) : time();
    }

    /**
     * Execute the job.
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        Log::debug('request data', $this->request);
        
        $collection = $this->data($this->request);

        $data = $collection->map(function($collection) {
            return $this->filter($collection);
        })->all();

        $this->createExport($data, 'Funds')->sendEmail($mailer);
    }

    /**
     * Filter export file columns by fields.
     *
     * @param $collection
     * @return array
     */
    protected function filter($collection)
    {
        return collect($this->columns($collection))->filter(function($value, $key) {
            return in_array($key, $this->fields);
        })->all();
    }

    /**
     * Create the export file.
     *
     * @param $data
     * @param $sheetName
     * @return $this
     */
    protected function createExport($data, $sheetName)
    {
        Excel::create($this->fileName, function($excel) use($data, $sheetName) {

            $excel->sheet($sheetName, function($sheet) use($data) {

                $sheet->fromArray($data);
                $sheet->freezeFirstRow();

            });

        })->store('csv');

        return $this;
    }

    /**
     * Send the report as email attachment.
     *
     * @param $mailer
     */
    protected function sendEmail($mailer)
    {
        $data = ['file' => $this->fileName.'.csv'];

        $mailer->send('emails.reports.export', $data, function ($message) {
            $message->to($this->email);
            $message->subject('Your report is ready!');
            $message->attach(storage_path('exports/' . $this->fileName.'.csv'));
        });
    }

    /**
     * Get custom columns for export file.
     *
     * @param $collection
     * @return array
     */
    public function columns($collection)
    {
        return [];
    }
}
