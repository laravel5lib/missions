@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'active' => 'Reservations'
    ]])
    @endbreadcrumbs
    
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                @include('admin.reservations._sidenav')
            </div>
            <div class="col-sm-10">
                <interest-list campaign-id="{{ $campaign->id }}"
                                cache-key="admin.campaign.{{$campaign->id}}.interests.interestList"
                                :totals="{{ json_encode($totals) }}"
                ></interest-list>
            </div>
        </div>
    </div>
@endsection