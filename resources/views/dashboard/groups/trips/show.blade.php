@extends('dashboard.layouts.default')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            @breadcrumbs(['links' => [
                'dashboard/groups' => 'Organizations',
                'dashboard/groups/'.$group->id => $group->name,
                'active' => $trip->campaign->name.' - '.ucwords($trip->type). ' Trip'
            ]])
            @endbreadcrumbs
            <hr class="divider">
            <hr class="divider inv">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            @sidenav(['links' => [
                'dashboard/groups/'.$group->id.'/trips/'.$trip->id => 'Trip Details',
                'dashboard/groups/'.$group->id.'/trips/'.$trip->id.'/pricing' => 'Pricing',
                'dashboard/groups/'.$group->id.'/trips/'.$trip->id.'/requirements' => 'Requirements',
                'dashboard/groups/'.$group->id.'/trips/'.$trip->id.'/resources' => 'Resources',
                'dashboard/groups/'.$group->id.'/trips/'.$trip->id.'/reservations' => 'Reservations'
            ]])
            @endsidenav
        </div>
        <div class="col-sm-7">
            @include('dashboard.groups.trips.tabs.'.$tab)
        </div>
    </div>

</div>
@endsection