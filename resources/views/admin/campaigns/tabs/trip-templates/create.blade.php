@inject('types', App\Utilities\v1\TripType)

@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$campaign->id.'/trip-templates' => $campaign->name,
        'active' => 'New Template'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaigns/{{ $campaign->id }}/trip-templates">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new template was added.</template>
    <template slot="cancel">Add Another</template>
    <template slot="confirm">Done</template>
</alert-success>

<ajax-form method="post" action="campaigns/{{$campaign->id}}/trip-templates" :initial="{{ json_encode([
    'name' => '',
    'country_code' => $campaign->country_code,
    'type' => 'ministry',
    'difficulty' => 'level_1',
    'started_at' => $campaign->started_at->toDateString(),
    'ended_at' => $campaign->ended_at->toDateString(),
    'companion_limit' => 0,
    'spots' => 25,
    'closed_at' => null,
    'team_roles' => ['MISS'],
    'prospects' => ['adults', 'young adults (18-29)', 'men', 'women'],
    'tags' => [],
    'description' => ''
]) }}">

<template slot-scope="{ form }">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">New Trip Template</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Template Details</h5>
            <p class="text-muted">These details appear to the end-user on the trip details page.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <div class="row">
                    <div class="col-md-6">
                        <input-text name="name" v-model="form.name">
                            <label slot="label">Name</label>
                        </input-text>
                    </div>
                </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Trip Details</h5>
            <p class="text-muted">These details appear to the end-user on the trip details page.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <div class="row">
                    <div class="col-sm-6">
                        <input-select name="type" v-model="form.type" :options="{{ json_encode($types->all()) }}">
                            <label slot="label">Trip Type</label>
                        </input-select>
                    </div>
                    <div class="col-sm-6">
                        <input-select name="difficulty" v-model="form.difficulty" :options="{
                            'level_1' : 'Level 1',
                            'level_2' : 'Level 2',
                            'level_3' : 'Level 3',
                        }">
                            <label slot="label">Difficulty Rating</label>
                        </input-select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <input-date name="started_at" v-model="form.started_at">
                            <label slot="label">Start Date</label>
                        </input-date>
                    </div>
                    <div class="col-sm-6">
                        <input-date name="ended_at" v-model="form.ended_at">
                            <label slot="label">End Date</label>
                        </input-date>
                    </div>
                </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Tags</h5>
            <p class="text-muted">Tags provide additional identifying information and will help you better organize trips. These tags are seen by the end-user.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <div class="row">
                    <div class="col-xs-12">
                        <select-tags name="tags" v-model="form.tags">
                            <label slot="label">Select Tag(s)</label>
                            <span class="help-block" slot="help-text">
                                <a href="{{ url('admin/tags/trip') }}"><i class="fa fa-edit"></i> Manage tags</a>
                            </span>
                        </select-tags>
                    </div>
                </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Additional Configuration</h5>
            <p class="text-muted">Other details and configurations for the trip.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')

                    <select-roles name="team_roles" v-model="form.team_roles">
                        <label slot="label">Roles Available at Registration</label>
                        <span class="help-block" slot="help-text">
                            Exclude leadership roles; those should be assigned by MM staff. Select all that apply.
                        </span>
                    </select-roles>

                    <select-prospects name="prospects" v-model="form.prospects">
                        <label slot="label">Who is this trip ideal for? (optional)</label>
                        <span class="help-block" slot="help-text">
                            Helps users find trips right for them. Select all that apply.
                        </span>
                    </select-prospects>

                    <div class="row">
                        <div class="col-sm-6">
                            <input-number name="companion_limit" v-model="form.companion_limit">
                                <label slot="label">Default Companion Limit</label>
                                <span class="help-block" slot="help-text">Default number of companions a reservation can have. Leave at 0 to disable companions.</span>
                            </input-number>
                        </div>
                    </div>

                @endslot
            @endcomponent
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Registration Settings</h5>
            <p class="text-muted">Registration will close or remain open based on these settings.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <div class="row">
                        <div class="col-sm-4">
                            <input-number name="spots" v-model="form.spots">
                                <label slot="label">Spots Available</label>
                                <span class="help-block" slot="help-text">
                                    When spots left reaches 0, registration will automatically close.
                                </span>
                            </input-number>
                        </div>
                        <div class="col-sm-8">
                            <input-datetime name="closed_at" v-model="form.closed_at">
                                <label slot="label">Close Registration (optional)</label>
                                <span class="help-block" slot="help-text">
                                    Registration will automatically close at this date and time across all time zones.
                                </span>
                            </input-datetime>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Public Page</h5>
            <p class="text-muted">Registration will close or remain open based on these settings.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <div class="row">
                        <div class="col-xs-12">
                            <input-markdown name="description" 
                                            placeholder="Enter page content here..." 
                                            v-model="form.description">
                                <label slot="label">Page Content</label>
                                <span class="help-block" slot="help-text">
                                    Use "Markdown" to format text.
                                    <a type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                        [ <i class="fa fa-info-circle"></i> Markdown Tutorial ]
                                    </a>
                                </span>
                            </input-markdown>
                            <markdown-example-modal></markdown-example-modal>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="{{ url('/admin/campaigns/'.$campaign->id.'/trip-templates') }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</div>

</template>
</ajax-form>

@endsection