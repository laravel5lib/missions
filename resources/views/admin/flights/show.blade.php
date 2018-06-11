@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/flights' => 'Flights',
        'active' => $flight->flight_no . ' (' . $flight->flightSegment->name . ')'
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
            <div class="col-xs-12 col-md-2">
                
            </div>
            <div class="col-xs-12 col-md-7">
                @component('panel')
                    @slot('title')
                        <div class="row">
                            <div class="col-xs-8">
                                <h5>Details</h5>
                            </div>
                            <div class="col-xs-4 text-right">
                                <a href="#" class="btn btn-sm btn-default">Edit</a>
                            </div>
                        </div>
                    @endslot
                    @component('list-group', ['data' => [
                        'flight_number' => $flight->flight_no,
                        'city' => $flight->iata_code,
                        'date' => $flight->date->format('F j, Y'),
                        'time' => $flight->time,
                        'segment' => $flight->flightSegment->name,
                        'record_locator' => '<a href="'.url('admin/campaigns/'.$campaign->id.'/itineraries/'.$flight->flightItinerary->uuid).'"><strong>'.$flight->flightItinerary->record_locator.'</strong></a>'
                    ]])@endcomponent
                @endcomponent
            </div>
            <div class="col-xs-12 col-md-3 small">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                    </li>
                    <li role="presentation">
                        <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Tasks</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="notes">
                        <notes type="flights"
                            id="{{ $flight->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10"
                            :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="flights"
                            id="{{ $flight->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                        </todos>
                    </div>
                </div>
            </div>
        </div>

</div>

@endsection