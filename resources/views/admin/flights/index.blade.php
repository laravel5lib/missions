@extends('layouts.admin')

@section('content')

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
                @sidenav(['links' => [
                    "admin/campaigns/{$campaign->id}/reservations" => 'Missionaries',
                    "admin/campaigns/{$campaign->id}/flights" => 'Flights'
                ]])
                @endsidenav
            </div>
            <div class="col-sm-10">
            </div>
        </div>

</div>

@endsection