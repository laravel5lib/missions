@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/flights' => 'Flights',
        'admin/campaigns/'.$campaign->id.'/itineraries/'.$itinerary->uuid => $itinerary->record_locator,
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <alert-error>
                <template slot="title">Oops!</template>
                <template slot="message">Please check the form for errors and try again.</template>
            </alert-error>

            <alert-success redirect="/admin/campaigns/{{ $campaign->id }}/itineraries/{{ $itinerary->uuid }}">
                <template slot="title">Nice Work!</template>
                <template slot="message">Your changes have been saved.</template>
                <template slot="cancel">Keep Working</template>
                <template slot="confirm">Done</template>
            </alert-success>

            <ajax-form method="put" 
                       action="campaigns/{{ $campaign->id }}/flights/itineraries/{{ $itinerary->uuid }}" 
                       :horizontal="true" 
                       :initial="{{ json_encode([
                        'record_locator' => $itinerary->record_locator, 
                        'type' => $itinerary->type
                       ]) }}"
            >
                <template slot-scope="{ form }">
                    <div class="panel panel-default panel-body">
                        
                        <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Making changes to the itinerary will affect any flights and reservations assigned to it.</div>

                        <input-text name="record_locator" 
                                    v-model="form.record_locator" 
                                    :horizontal="true"
                                    classes="col-sm-4">
                        >
                            <label slot="label" class="control-label col-sm-4">
                                Record Locator
                            </label>
                        </input-text>
                        <input-select name="type"
                                      v-model="form.type"
                                      :horizontal="true"
                                      classes="col-sm-4"
                                      :options="{group: 'Group', individual: 'Individual'}"
                        >
                            <label slot="label" class="control-label col-sm-4">
                                Type
                            </label>
                        </input-select>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <a href="/admin/campaigns/{{ $campaign->id }}/itineraries/{{ $itinerary->uuid }}"
                               class="btn btn-link"
                            >Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </template>
            </ajax-form>
        </div>
    </div>
</div>

@endsection