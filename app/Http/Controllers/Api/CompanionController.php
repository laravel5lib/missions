<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Companion;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\CompanionResource;

class CompanionController extends Controller
{
    public function index()
    {
        $query = Companion::join('reservations', 'reservations.id', '=', 'companions.companion_id');

        $companions = QueryBuilder::for($query)
            ->allowedFilters([
                Filter::exact('reservation_id'),
                Filter::scope('not_in_squad'),
            ])
            ->select([
                'reservations.id', 
                'reservations.surname', 
                'reservations.given_names', 
                'reservations.gender', 
                'reservations.birthday', 
                'reservations.status', 
                'reservations.desired_role',
                'companions.relationship', 
                'companions.reservation_id', 
                'companions.companion_id'
            ])
            ->allowedIncludes([
                'companion-reservation.trip.group', 
                'companion-reservation.squad-memberships.squad.region'
            ])
            ->paginate(request()->input('per_page', 25));
        
        return CompanionResource::collection($companions);
    }
}
