<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\User;
use App\Models\v1\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
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
     * @var Report
     */
    public $report;

    /**
     * @var File
     */
    public $file;

    /**
     * Create a new job instance.
     *
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = collect($request);
    }

    /**
     * Execute the job.
     * @param Report $report
     */
    public function handle(Report $report, Mailer $mailer)
    {
        $filename = $this->getFilename();

        $this->create($this->getFilteredData(), 'Export', $filename)
             ->saveReport($report, $filename, $this->file['full'], $this->getUserId())
             ->sendEmail($mailer, $filename, $this->getEmail());
    }

    /**
     * Get the filtered data.
     *
     * @return array
     */
    public function getFilteredData()
    {
        $data = $this->getData()->map(function ($data) {
            return $this->filter($data, $this->getFields());
        })->all();

        return $data;
    }

    /**
     * Filter columns by requested fields.
     *
     * @param $collection
     * @return array
     */
    public function filter($data, array $fields)
    {
        return $this->getColumns($data)->filter(function ($value, $key) use ($fields) {
            return in_array($key, array_keys($fields), true);
        })->all();
    }

    /**
     * Get exportable columns.
     *
     * @param  Collection $data
     * @return Collection
     */
    public function getColumns($data)
    {
        return collect($this->columns($data));
    }

    /**
     * Get requested fields.
     *
     * @return Collection
     */
    public function getFields()
    {
        return $this->request->get('fields', []);
    }

    /**
     * Get the requested data.
     *
     * @return Collection
     */
    public function getData()
    {
        $data = $this->data($this->request->all());

        return collect($data);
    }

    /**
     * Get the requested filename
     *
     * @return string
     */
    public function getFilename()
    {
        $filename = $this->request->get('filename', 'report');

        return snake_case($filename) .'_'. time();
    }

    /**
     * Get report's author
     *
     * @return User
     */
    public function getAuthor()
    {
        return User::find($this->request->get('author_id'));
    }

    /**
     * Get the author's user id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->getAuthor() ? $this->getAuthor()->id : null;
    }

    /**
     * Get email to notify
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getAuthor() ? $this->getAuthor()->email : 'mail@missions.me';
    }

    /**
     * Create the export file.
     *
     * @param $data
     * @param $sheetName
     * @return $this
     */
    public function create($data, $sheetname, $filename)
    {
        $content = Excel::create($filename, function ($excel) use ($data, $sheetname) {

            $excel->sheet($sheetname, function ($sheet) use ($data) {

                $sheet->fromArray($data);
                $sheet->freezeFirstRow();
            });
        })->string('csv');

        $this->putInStorage($filename, $content);

        return $this;
    }

    /**
     * Save the document in storage
     *
     * @param  string $filename
     * @param  string $content raw file content
     */
    public function putInStorage($filename, $content)
    {
        $path = $this->getUserId() ? 'export/reports/'.$this->getUserId().'/' : 'export/reports/system/';
        $file = $filename.'.csv';

        Storage::disk('s3')->put(
            $path.$file,
            $content,
            'public'
        );

        $this->setFile([
            'full' => $path.$file,
            'path' => $path,
            'name' => $filename,
            'ext' => 'csv'
        ]);
    }

    /**
     * Save a report record.
     *
     * @param  Report $report
     */
    public function saveReport(Report $report, $filename, $source, $userId = null)
    {
        $data = $report->create([
            'name' => $filename,
            'type' => 'csv',
            'source' => $source,
            'user_id' => $userId
        ]);

        $this->setReport($data);

        return $this;
    }

    /**
     * Send the report as email attachment.
     *
     * @param $mailer
     */
    public function sendEmail(Mailer $mailer, $filename, $email)
    {
        $mailer->send('emails.reports.export', ['file' => $filename.'.csv'], function ($message) use ($filename, $email) {
            $message->to($email);
            $message->subject('Your report is ready!');
            // $message->attach(storage_path('exports/' . $filename .'.csv'));
        });

        return $this;
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

    /**
     * Get requested data.
     * @param  array  $request
     * @return array
     */
    public function data(array $request)
    {
        return [];
    }

    /**
     * Set the report property
     *
     * @param Model $report;
     * @return Models
     */
    protected function setReport($report)
    {
        return $this->report = $report;
    }

    /**
     * Set the report property
     *
     * @param array $file;
     * @return array
     */
    protected function setFile($file)
    {
        return $this->file = $file;
    }
}
