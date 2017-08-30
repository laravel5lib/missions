<?php

namespace App\Http\Controllers\Api\Reporting;

use App\Jobs\Reports\Transports\PassengersByTransport;
use App\Models\v1\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransportsController extends Controller
{
    protected $user;

    function __construct(User $user)
    {
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
            'passengers' => PassengersByTransport::class,
        ];

        return $reports[$type];
    }
}
