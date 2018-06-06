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

        <alert-error>
            <template slot="title">Oops!</template>
            <template slot="message">Please check the form for errors and try again.</template>
        </alert-error>

        <alert-success :timer="3000">
            <template slot="title">Nice Work!</template>
            <template slot="message">Your changes have been saved.</template>
        </alert-success>

        <div class="row">
            <div class="col-sm-2">
                @sidenav(['links' => [
                    "admin/campaigns/{$campaign->id}/reservations" => 'Missionaries',
                    "admin/campaigns/{$campaign->id}/flights" => 'Flights'
                ]])
                @endsidenav
            </div>
            <div class="col-sm-10">
                <flight-list campaign-id="{{ $campaign->id }}" 
                             :totals="{{ json_encode($totals) }}"
                ></flight-list>
            </div>
        </div>

</div>

@endsection