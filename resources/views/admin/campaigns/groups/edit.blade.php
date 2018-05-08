@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$group->campaign_id => $group->campaign->name.' - '.country($group->campaign->country_code),
        'admin/campaigns/'.$group->campaign_id.'/groups' => 'Groups',
        'active' => $group->organization->name
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/groups/{{ $group->uuid }}/prices">
    <template slot="title">Saved</template>
    <template slot="message">The group was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>


<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">{{ $group->organization->name }}</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Change Status</h5>
			<p class="text-muted">Set the current status of the group.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <ajax-form method="put" action="/campaign-groups/{{ $group->uuid }}" :horizontal="true">
                    <input-select name="status" :horizontal="true" classes="col-sm-8">
                        <label slot="label" class="control-label col-sm-4">Status</label>
                    </input-select>
                    <hr class="divider">
                    <div class="from-group text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </ajax-form>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Meta Information</h5>
			<p class="text-muted">Manage additional details about this group.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <meta-form method="put" 
                           action="/campaign-groups/{{ $group->uuid }}" 
                           meta="{{ $group->meta }}">
                </meta-form>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endsection