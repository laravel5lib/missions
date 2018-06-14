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
    <div class="col-xs-12">

        <div class="row">
            <div class="col-sm-2">
                @include('admin.reservations._sidenav')
            </div>
            <div class="col-sm-10">
                <missionary-list campaign-id="{{ $campaign->id }}" :totals="{{ json_encode($totals) }}"></missionary-list>
            </div>
        </div>
    </div>
</div>
@endsection