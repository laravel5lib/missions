
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

        <group-reservation-list 
            campaign-id="{{ $campaign->id }}"
            group-id="{{ $group->id }}"
            :totals="{{ json_encode($totals) }}"
        ></group-reservation-list>

        </div>
    </div>
</div>

@endsection