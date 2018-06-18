
@extends('dashboard.layouts.default')

@section('content')

@breadcrumbs(['links' => [
    'dashboard/groups' => 'Organizations',
    'dashboard/groups/'.$group->id => $group->name,
    'active' => $campaign->name
]])
@endbreadcrumbs

<div class="container-fluid">

    <hr class="divider inv">

    <div class="row">
        <div class="col-md-2">
            @include('dashboard.groups._sidenav')
        </div>
        <div class="col-md-10">
            <group-flight-list group-id="{{ $group->id }}" 
                               campaign-id="{{ $campaign->id }}"
                               cache-key="dashboard.groups.{{$group->id}}.campaign.{{$campaign->id}}.flights.groupFlightList"
            ></group-flight-list>
        </div>
    </div>
</div>

@endsection