@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$group->campaign_id.'/groups' => $group->campaign->name.' - '.country($group->campaign->country_code),
        'admin/campaign-groups/'.$group->uuid.'/requirements' => $group->organization->name,
        'admin/campaign-groups/'.$group->uuid.'/requirements/'.$requirement->id => $requirement->name,
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaign-groups/{{ $group->uuid }}/requirements/{{ $requirement->id }}">
    <template slot="title">Changes Saved!</template>
    <template slot="message">The requirement was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">Edit Travel Requirement</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<travel-requirement-form 
    requester-id="{{ $group->uuid }}" 
    requester-type="campaign-groups" 
    :requirement="{{ $requirement }}"
></travel-requirement-form>

@endsection