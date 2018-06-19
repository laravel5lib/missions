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
                <div class="row">
                    <div class="col-xs-12">
                        @component('panel')
                            @slot('title')
                            <div class="row">
                                <div class="col-xs-8">
                                    <h5>Details</h5> 
                                </div>
                                <div class="col-xs-4 text-right">
                                    <a href="{{ url('/admin/campaigns/'.$campaign->id.'/itineraries/'.$itinerary->uuid.'/edit') }}" class="btn btn-sm btn-default">Edit</a>
                                </div>
                            </div>
                            @endslot
                            @component('list-group', ['data' => [
                                'record_locator' => $itinerary->record_locator,
                                'type' => $itinerary->type,
                                'passengers' => $itinerary->reservations()->count()
                            ]])@endcomponent
                        @endcomponent
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <itinerary-flight-manager 
                            itinerary-id="{{ $itinerary->uuid }}"
                            campaign-id="{{ $campaign->id }}"
                        ></itinerary-flight-manager>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        @component('panel')
                            @slot('title')
                                <h5>Delete itinerary</h5>
                            @endslot
                            @slot('body')
                                <div class="alert alert-warning">
                                    <div class="row">
                                        <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                        <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will delete the itinerary, all it's flights, and remove all passengers.</div>
                                    </div>
                                </div>
                                <delete-form url="campaigns/{{ $campaign->id }}/flights/itineraries/{{ $itinerary->uuid }}" 
                                                redirect="/admin/campaigns/{{ $campaign->id }}/reservations/flights"
                                                label="Enter the record locator to delete it"
                                                button="Delete"
                                                match-value="{{ $itinerary->record_locator }}">
                                </delete-form>
                            @endslot
                        @endcomponent
                    </div>
                </div>

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