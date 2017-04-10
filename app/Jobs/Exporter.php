<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        $this->fileName = $request['filename'] ? snake_case($request['filename'] .'_'. time()) : time();
    }

    /**
     * Execute the job.
     * @param Report $report
     */
    public function handle(Report $report)
    {
        $collection = $this->data($this->request);

        $data = $collection->map(function($collection) {
            return $this->filter($collection);
        })->all();

        $this->createExport($data, 'Export')->saveReport($report);
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
            return in_array($key, $this->fields, true);
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
            // $message->to($this->email);
            $message->subject('Your report is ready!');
            $message->attach(storage_path('exports/' . $this->fileName.'.csv'));
        });
    }

    /**
     * Save a report record.
     * 
     * @param  Report $report
     */
    protected function saveReport(Report $report)
    {
        $report->create([
            'name' => $this->fileName,
            'type' => 'csv',
            'source' => storage_path('exports/' . $this->fileName.'.csv'),
            'user_id' => $this->request['author_id']
        ]);
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
