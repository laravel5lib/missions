@extends('layouts.admin')

@section('content')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/squads' => 'Squads',
        'active' => 'New Squad'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">

    @if(! $regions)

        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                @component('panel')
                    <div class="panel-body" 
                        style="min-height: 300px; display:flex; justify-content: center; align-items: center;" 
                    >
                        <div class="text-center">
                            <span class="lead">Oops!</span>
                            <p>You don't have any <strong>regions</strong> created. You must have at least one <strong>region</strong> to create a squad.</p>
                            <p>
                                <a href="/admin/campaigns/{{ $campaign->id }}/regions/create" 
                                class="btn btn-primary btn-sm"
                                >
                                    Create a Region
                                </a>
                            </p>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>

    @else
    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success redirect="/admin/campaigns/{{ $campaign->id }}/reservations/squads">
        <template slot="title">Nice Work!</template>
        <template slot="message">A new squad was created.</template>
        <template slot="cancel">Create Another</template>
        <template slot="confirm">Done</template>
    </alert-success>

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            @component('panel')
            @slot('title')
                <h5>Create New Squad</h5>
            @endslot
            @slot('body')
            <div class="alert alert-warning" style="display:flex; justify-content: flex-start; align-items: center;">
                <i class="fa fa-info-circle fa-lg" style="margin-right: 1em"></i> Before you can add missionaries to a squad, you have to create one! Think carefully how you want to name the squad. It is recommended to use descriptive names and keep them in a similar format.</i>
            </div>

            <ajax-form 
                method="post" 
                action="squads" 
                :initial="{ region_id: null, callsign: null }" 
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
                        <a href="/admin/campaigns/{{ $campaign->id }}/reservations/squads" class="btn btn-link">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>

                </template>
            </ajax-form>
            @endslot
            @endcomponent
        </div>
    </div>
    @endif
</div>

@endsection