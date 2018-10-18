<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class InterestConversionMetricController extends Controller
{
    public function byUser(Request $request)
    {
        $records = Activity::query()
            ->selectRaw('causer_id, causer_type, COUNT(*) as conversion_count')
            ->where([
                ['causer_type', '=', 'users'],
                ['subject_type', '=', 'trip_interests'],
                ['properties->attributes->status', '=', 'converted']
            ])
            ->when($request->input('start'), function($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($request->input('end'), function($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->when($request->input('min'), function($query, $min) {
                $query->having('conversion_count', '>=', $min);
            })
            ->when($request->input('limit'), function($query, $limit) {
                $query->take($limit);
            })
            ->groupBy('causer_id')
            ->orderBy('conversion_count', 'desc')
            ->with('causer')
            ->get();

        return [
            'data' => $records->map(function($record) {
                return [
                    'conversion_count' => $record->conversion_count,
                    'user' => $record->causer->name
                ];
            })->all()
        ];
    }
}
