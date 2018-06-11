@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/flights' => 'Flights',
        'active' => $itinerary->record_locator
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
            <div class="col-xs-12 col-md-7 col-md-offset-2">
                @component('panel')
                    @slot('title')
                    <div class="row">
                        <div class="col-xs-8">
                            <h5>Details</h5> 
                        </div>
                        <div class="col-xs-4 text-right">
                            <a type="button" class="btn btn-sm btn-default">Edit</a>
                        </div>
                    </div>
                    @endslot
                    @component('list-group', ['data' => [
                        'record_locator' => $itinerary->record_locator,
                        'type' => $itinerary->type
                    ]])@endcomponent
                @endcomponent

                <itinerary-flight-list itinerary-id="{{ $itinerary->uuid }}"></itinerary-fight-list>

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
                        <notes type="itineraries"
                            id="{{ $itinerary->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10"
                            :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="itineraries"
                            id="{{ $itinerary->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                        </todos>
                    </div>
                </div>
            </div>
        </div>

</div>

@endsection