@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/flights' => 'Flights',
        'admin/flights/'.$flight->uuid => $flight->flight_no . ' (' . $flight->flightSegment->name . ')',
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <ajax-form method="put" 
                       action="flights/{{ $flight->uuid}}" 
                       :horizontal="true" 
                       :initial="{{ json_encode([
                            'flight_no' => $flight->flight_no,
                            'iata_code' => $flight->iata_code,
                            'date' => $flight->date->toDateString(),
                            'time' => $flight->time
                       ]) }}"
            >
                <template slot-scope="{ form }">
                    <div class="panel panel-default panel-body">
                        <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Making changes to the flight will affect it's itinerary and any reservations assigned to it.</div>

                        <input-text name="flight_no" v-model="form.flight_no" :horizontal="true" classes="col-sm-4">
                            <label slot="label" class="control-label col-sm-4">Flight No.</label>
                        </input-text>

                        <input-text name="iata_code" v-model="form.iata_code" :horizontal="true" classes="col-sm-4">
                            <label slot="label" class="control-label col-sm-4">City</label>
                        </input-text>

                        <input-date name="date" v-model="form.date" :horizontal="true" classes="col-sm-4">
                            <label slot="label" class="control-label col-sm-4">Date</label>
                        </input-date>

                        <input-time name="time" v-model="form.time" :horizontal="true" classes="col-sm-4">
                            <label slot="label" class="control-label col-sm-4">24-hour Time</label>
                        </input-time>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Flight Segment</label>
                            <div class="col-sm-8">
                            <p class="form-control-static">{{ $flight->flightSegment->name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Record Locator</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{ $flight->flightItinerary->record_locator }}</p>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <a href="{{ url('/admin/flights/'.$flight->uuid) }}" class="btn btn-link">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </template>
            </ajax-form>

            <alert-error>
                <template slot="title">Oops!</template>
                <template slot="message">Please check the form for errors and try again.</template>
            </alert-error>

            <alert-success redirect="/admin/flights/{{ $flight->uuid }}">
                <template slot="title">Nice Work!</template>
                <template slot="message">Your changes have been saved.</template>
                <template slot="cancel">Keep Working</template>
                <template slot="confirm">Done</template>
            </alert-success>
        </div>
    </div>
</div>

@endsection