@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/squads' => 'Squads',
        'admin/campaigns/'.$campaign->id.'/reservations/squads/'.$squad->uuid => $squad->callsign,
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success redirect="/admin/campaigns/{{ $campaign->id }}/reservations/squads/{{ $squad->uuid }}">
        <template slot="title">Nice Work!</template>
        <template slot="message">Your changes have been saved.</template>
        <template slot="cancel">Keep Working</template>
        <template slot="confirm">Done</template>
    </alert-success>

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            @component('panel')
            @slot('title')
                <h5>Edit Squad</h5>
            @endslot
            @slot('body')
            <div class="alert alert-warning" style="display:flex; justify-content: flex-start; align-items: center;">
                <i class="fa fa-warning fa-lg" style="margin-right: 1em"></i> Making changes to a squad will affect all members assigned to it.</i>
            </div>

            <ajax-form 
                method="put" 
                action="squads/{{ $squad->uuid }}" 
                :initial="{{ json_encode(['callsign' => $squad->callsign, 'region_id' => $squad->region_id]) }}" 
                :horizontal="true"
            >
                <template slot-scope="{ form }">

                    <input-text 
                        :horizontal="true" 
                        classes="col-md-6" 
                        name="callsign" 
                        placeholder="Ministry Squad 1"
                        v-model="form.callsign"
                    >
                        <label slot="label" class="col-md-4 control-label">
                            Callsign
                        </label>
                        <span slot="help-text" class="help-block">Must be unique per region.</span>
                    </input-text>

                    <input-select 
                        :horizontal="true" 
                        classes="col-md-6" 
                        name="region_id" 
                        v-model="form.region_id" 
                        :options="{{ json_encode($regions) }}"
                    >
                        <label slot="label" class="col-md-4 control-label">
                            Region
                        </label>
                        <span slot="help-text" class="help-block">A squad must be assigned to a specific region.</span>
                    </input-select>

                    <hr class="divider">

                    <div class="pull-right">
                        <a href="/admin/campaigns/{{ $campaign->id }}/reservations/squads/{{ $squad->uuid }}" 
                           class="btn btn-link"
                        >Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                </template>
            </ajax-form>
            @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection