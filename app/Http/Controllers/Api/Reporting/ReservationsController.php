<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Http\Requests;
use App\Models\v1\User;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;
use App\Jobs\Reports\Reservations\BasicReport;
use App\Jobs\Reports\Reservations\FundsReport;
use App\Jobs\Reports\Reservations\TravelReport;
use App\Jobs\Reports\Reservations\RequirementsReport;

class ReservationsController extends Controller
{
    protected $reservation;
    protected $user;
    
    function __construct(Reservation $reservation, User $user)
    {
        $this->reservation = $reservation;
        $this->user = $user;
    }

    public function store(Request $request, $type)
    {
        $user = $this->user->findOrFail($request->get('author_id'));

        $report = $this->getReport($type);

        $this->dispatch(new $report($request->all(), $user));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }

    private function getReport($type)
    {
        $reports = [
            'basic' => BasicReport::class,
            'funds' => FundsReport::class,
            'requirements' => RequirementsReport::class,
            'travel' => TravelReport::class
        ];

        return $reports[$type];
    }
}
