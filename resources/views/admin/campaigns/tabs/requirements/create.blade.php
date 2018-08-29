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

<alert-success redirect="/admin/campaigns/{{ $campaign->id }}/requirements">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new requirement was added.</template>
    <template slot="cancel">Add Another</template>
    <template slot="confirm">Done</template>
</alert-success>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">New Travel Requirement</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<travel-requirement-form requester-id="{{ $campaign->id }}" requester-type="campaigns"></travel-requirement-form>

@endsection