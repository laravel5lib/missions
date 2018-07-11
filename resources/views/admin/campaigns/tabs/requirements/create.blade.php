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

<ajax-form :horizontal="true" 
           action="/campaigns/{{ $campaign->id }}/requirements" 
           method="post" 
           :initial="{{ json_encode([
                'name' => null, 
                'short_desc' => null, 
                'document_type' => null, 
                'due_at' => null, 
                'requester_type' => 'campaigns', 
                'requester_id' => $campaign->id
            ]) }}"
>
<template slot-scope="{ form }">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Details</h5>
            <p class="text-muted">Provide a unique name and short description that the end-user will see.</p>
            <p class="text-muted">The <strong>document type</strong> determines the kind of document expected and online form to use.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <input-text name="name" :horizontal="true" classes="col-md-8" v-model="form.name">
                        <label slot="label" class="control-label col-md-4">Name</label>
                    </input-text>

                    <input-textarea name="short_desc" :horizontal="true" classes="col-md-8" v-model="form.short_desc">
                        <label slot="label" class="control-label col-md-4">Short Description</label>
                    </input-textarea>

                    <input-select name="document_type" 
                                  :horizontal="true" 
                                  classes="col-md-8" 
                                  v-model="form.document_type" 
                                  :options="{{ json_encode($docTypes) }}"
                    >
                        <label slot="label" class="control-label col-md-4">Document Type</label>
                    </input-select>

                    <input-date name="due_at" :horizontal="true" classes="col-md-8" v-model="form.due_at">
                        <label slot="label" class="control-label col-md-4">Due Date &amp; Time</label>
                    </input-date>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

{{-- <div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Rules (optional)</h5>
            <p class="text-muted">You can define a set of rules that determine when and why this requirement should be applied to a reservation.</p>
            <p class="text-muted">If <strong>no rules</strong> are set, the requirement will always be applied to every reservation.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                
                @endslot
            @endcomponent
        </div>
    </div>
</div> --}}

<div class="container">
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="{{ url('admin/campaigns/'.$campaign->id.'/requirements') }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">Add Requirement</button>
        </div>
    </div>
</div>

</template>
</ajax-form>

@endsection