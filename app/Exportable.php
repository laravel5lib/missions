<?php

namespace App;

use App\Jobs\ExportTrips;
use App\Jobs\ExportUsers;
use App\Jobs\ExportCauses;
use App\Jobs\ExportDonors;
use App\Jobs\ExportGroups;
use App\Jobs\ExportReservations;
use App\Http\Requests\v1\ExportRequest;

trait Exportable
{
    public function export(ExportRequest $request)
    {
        $this->getExportHandler($request);

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly in your reports.'
        ]);
    }

    private function getExportHandler($request)
    {
        switch ($request->segment(2)) {
            case 'reservations':
                $this->dispatch(new ExportReservations($request->all()));
                break;
            case 'trips':
                $this->dispatch(new ExportTrips($request->all()));
                break;
            case 'groups':
                $this->dispatch(new ExportGroups($request->all()));
                break;
            case 'groups':
                $this->dispatch(new ExportGroups($request->all()));
                break;
            case 'users':
                $this->dispatch(new ExportUsers($request->all()));
                break;
            case 'causes':
                $this->dispatch(new ExportCauses($request->all()));
                break;
            case 'donors':
                $this->dispatch(new ExportDonors($request->all()));
                break;
            case 'essays':
                $this->dispatch(new ExportGroups($request->all()));
                break;
            default:
                abort(405, 'Server is unable to export.');
        }
    }
}
