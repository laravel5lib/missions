@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/flights' => 'Flights',
        'active' => $flight->flight_no
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
                
            </div>
            <div class="col-sm-10">
                @component('panel')
                    @slot('title')<h5>Details</h5> @endslot
                    @component('list-group', ['data' => [
                        'flight_number' => $flight->flight_no,
                        'city' => $flight->iata_code,
                        'date' => $flight->date->format('F j, Y'),
                        'time' => $flight->time
                    ]])@endcomponent
                @endcomponent
            </div>
        </div>

</div>

@endsection