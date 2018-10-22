@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$group->campaign_id.'/groups' => $group->campaign->name.' - '.country($group->campaign->country_code),
        'admin/campaign-groups/'.$group->uuid => $group->organization->name,
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaign-groups/{{ $group->uuid }}">
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
                <ajax-form method="put" action="/campaigns/{{ $group->campaign_id }}/groups/{{ $group->group_id }}" :horizontal="true" :initial="{{ json_encode($group->only(['status_id', 'commitment'])) }}">
                    <template slot-scope="{ form }">
                        <input-select name="status" 
                                      :horizontal="true" 
                                      classes="col-sm-8" 
                                      :options="{1:'Pending', 2:'Committed', 3:'Ready to Launch', 4:'Launched'}" v-model="form.status_id"
                        >
                            <label slot="label" class="control-label col-sm-4">Status</label>
                        </input-select>
                        <input-number 
                            name="commitment"
                            :horizontal="true" 
                            classes="col-sm-8"
                            v-model="form.commitment"
                        >
                            <label slot="label" class="control-label col-sm-4">Commitment</label>
                        </input-number>
                        <hr class="divider">
                        <div class="from-group text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </template>
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
                           action="/campaigns/{{ $group->campaign_id }}/groups/{{ $group->group_id }}" 
                           :meta="{{ json_encode($group->meta) }}">
                </meta-form>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endsection