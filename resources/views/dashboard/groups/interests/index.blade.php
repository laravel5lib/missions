
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

            <group-interest-list 
                group-id="{{ $group->id }}"
                campaign-id="{{ $campaign->id }}"
                cache-key="dashboard.group.{{$group->id}}.campaign.{{$campaign->id}}.interests.interestList"
            ></group-interest-list>

        </div>
    </div>
</div>

@endsection