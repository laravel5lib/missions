@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        '/admin/campaigns/'.$trip->campaign->id.'/groups' => $trip->campaign->name.' - '.country($trip->country_code),
        '/admin/campaign-groups/'.$group->uuid.'/trips' => $trip->group->name,
        '/admin/trips/'.$trip->id.'/reservations' => ucfirst($trip->type).' Trip',
        'active' => 'New Reservation'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><h5>New Reservation</h5></div>
                <admin-reservation-create trip-id="{{ $trip->id }}"></admin-reservation-create>
            </div>
        </div>
    </div>
</div>

@endsection