@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/squads' => 'Regions',
        'active' => 'New Region'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success redirect="/admin/campaigns/{{ $campaign->id }}/reservations/squads">
        <template slot="title">Nice Work!</template>
        <template slot="message">A new region was created.</template>
        <template slot="cancel">Create Another</template>
        <template slot="confirm">Done</template>
    </alert-success>

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            @component('panel')
            @slot('title')
                <h5>Create New Region</h5>
            @endslot
            @slot('body')
            <div class="alert alert-warning" style="display:flex; justify-content: flex-start; align-items: center;">
                <i class="fa fa-info-circle fa-lg" style="margin-right: 1em"></i> Before you start creating squads, you have to create a region first. A region doesn't have to be geographic location. Feel free to get creative!</i>
            </div>

            <ajax-form 
                method="post" 
                action="regions" 
                :initial="{{ json_encode(['name' => null, 'campaign_id' => $campaign->id ]) }}" 
                :horizontal="true"
            >
                <template slot-scope="{ form }">

                    <input-text 
                        :horizontal="true" 
                        classes="col-md-6" 
                        name="name" 
                        placeholder="Matagalpa"
                        v-model="form.name"
                    >
                        <label slot="label" class="col-md-4 control-label">
                            Name
                        </label>
                        <span slot="help-text" class="help-block">Must be unique.</span>
                    </input-text>

                    <hr class="divider">

                    <div class="pull-right">
                        <a href="/admin/campaigns/{{ $campaign->id }}/reservations/squads" class="btn btn-link">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>

                </template>
            </ajax-form>
            @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection