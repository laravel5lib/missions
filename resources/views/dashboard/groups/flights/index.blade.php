
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
            @sidenav(['links' => [
                'dashboard/groups/'.$group->id.'/campaigns/'.$campaign->id => 'Overview',
                'dashboard/groups/'.$group->id.'/campaigns/'.$campaign->id.'/reservations' => 'Missionaries',
                'dashboard/groups/'.$group->id.'/campaigns/'.$campaign->id.'/flights' => 'Flights'
            ]])
            @endsidenav
        </div>
        <div class="col-md-10">
            <group-flight-list group-id="{{ $group->id }}" campaign-id="{{ $campaign->id }}"></group-flight-list>
        </div>
    </div>
</div>

@endsection