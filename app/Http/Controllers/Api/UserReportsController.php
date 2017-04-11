<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\User;
use App\Models\v1\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Transformers\v1\ReportTransformer;

class UserReportsController extends Controller
{
    private $user;
    private $report;

    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Get all user reports
     * 
     * @param  string  $userId
     * @param  Request $request
     * @return response
     */
    public function index($userId, Request $request)
    {
        $reports = $this->user
             ->findOrFail($userId)
             ->reports()
             ->filter($request->all())
             ->orderBy('created_at', 'desc')
             ->paginate($request->get('per_page', 10));

        return $this->response->paginator($reports, new ReportTransformer);
    }

    /**
     * Delete the report
     * 
     * @param  string $id
     * @return response
     */
    public function destroy($id)
    {
        $report = $this->report->findOrFail($id);

        Storage::disk('s3')->delete($report->source);

        $report->delete();
        
        return $this->response->noContent();
    }
}
