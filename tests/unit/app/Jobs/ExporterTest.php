<?php

use App\Jobs\Exporter;
use App\Models\v1\Report;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class ExporterTest extends TestCase
{
    /** @test */
    public function returns_a_collection_of_columns()
    {
        $exporter = new Exporter([]);

        $collection = $exporter->getColumns( collect([]) );

        $this->assertTrue($collection instanceOf Collection ?: false);
    }

    /** @test */
    public function returns_an_array_of_fields()
    {
        $exporter = new Exporter(['fields' => ['field1', 'field2']]);

        $fields = $exporter->getFields();

        $this->assertTrue(is_array($fields));
        $this->assertTrue(in_array('field1', $fields));
        $this->assertTrue(in_array('field2', $fields));
    }

    /** @test */
    public function returns_a_collection_of_data()
    {
        $exporter = new Exporter([]);

        $collection = $exporter->getData( collect([]) );

        $this->assertTrue($collection instanceOf Collection ?: false);
    }

    /** @test */
    public function returns_filename_in_snake_case_with_time_appended()
    {
        $exporter = new Exporter(['filename' => 'Test Report']);

        $filename = $exporter->getFilename();

        $this->assertContains('test_report_', $filename);
        $this->assertStringMatchesFormat('%s_%s', $filename);
    }

    /** @test */
    public function returns_filtered_data_array()
    {
        $exporter = new Exporter(['fields' => ['field1', 'field2']]);

        $data = $exporter->getFilteredData();

        $this->assertTrue(is_array($data));
    }

    /** @test */
    public function creates_a_csv_file()
    {
        $exporter = new Exporter([]);

        $data = $exporter->create([], 'Export', 'test_file');

        $this->assertTrue( Storage::disk('s3')->exists($data->file['full']) );

        // clean up
        Storage::disk('s3')->delete($data->file['full']);
    }

    /** @test */
    public function saves_the_report()
    {
        $exporter = new Exporter([]);

        $exporter->saveReport(new Report, 'Test File Name', 'export/reports');

        $this->seeInDatabase('reports', $exporter->report->toArray())
             ->assertTrue($exporter->report->type === 'csv');
    }
}
