@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$campaign->id.'/requirements' => $campaign->name,
        'active' => 'New Requirement'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaigns/{{ $campaign->id }}/requirements/{{ $requirement->id }}">
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
    requester-id="{{ $campaign->id }}" 
    requester-type="campaigns" 
    :requirement="{{ $requirement }}"
></travel-requirement-form>

@endsection