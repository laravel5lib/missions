@inject('types', App\Utilities\v1\TripType)

@extends('layouts.admin')

@section('content')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        '/admin/campaigns/'.$trip->campaign->id.'/groups' => $trip->campaign->name.' - '.country($trip->country_code),
        '/admin/campaign-groups/'.$group->uuid.'/trips' => $trip->group->name,
        '/admin/trips/'.$trip->id => ucfirst($trip->type).' Trip',
        'active' => 'Edit'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/trips/">
    <template slot="title">Good job!</template>
    <template slot="message">All changes saved.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<ajax-form method="put" action="/trips/{{ $trip->id }}" :initial="{{ $formData }}">

<template slot-scope="{ form }">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">Edit Trip</h3>
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
            <h5>Trip Rep</h5>
            <p class="text-muted">This is the representative that will be automatically assigned to any reservations created under this trip. The trip rep can be overridden for individual reservations.</p>
            
            <p class="text-muted">The trip rep's contact information will be shown to all registered user.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <select-rep name="rep_id" v-model="form.rep_id">
                        <label slot="label">Default Trip Rep (optional)</label>
                        <span class="help-block" slot="help-text">
                            Search trip rep by entering an email. Select the rep to assign them.
                        </span>
                    </select-rep>
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
        <div class="col-xs-12 text-right">
            <a href="{{ url('/admin/trips/'.$trip->id) }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</div>

</template>
</ajax-form>
@endsection