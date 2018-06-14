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
                    @sidenav(['links' => [
                        "admin/campaigns/{$campaign->id}/reservations/missionaries" => 'Missionaries',
                        "admin/campaigns/{$campaign->id}/reservations/flights" => 'Flights',
                        "admin/campaigns/{$campaign->id}/reservations/dropped" => 'Dropped',
                    ]])
                    @endsidenav
                </div>
                <div class="col-sm-10">
                    <dropped-reservation-list 
                        campaign-id="{{ $campaign->id }}"
                        cache-key="admin.dropped.reservations.campaign.{{$campaign->id}}"
                    ></dropped-reservation-list>
                </div>
            </div>
        </div>
    </div>
@endsection