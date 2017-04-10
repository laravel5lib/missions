<?php

namespace App;

use App\Http\Requests\v1\ExportRequest;

trait Exportable
{
    public function export(ExportRequest $request)
    {   
        $this->dispatch(
            $this->getExportHandler($request)
        );

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly in your reports.'
        ]);
    }

    private function getExportHandler($request)
    {
        $class = 'App\Jobs\Export' . strtoupper($request->segment(2));

        if (class_exists($class)) {
            return new $class($request->all());
        }

        abort(405, 'Server is unable to export.');
    }
}