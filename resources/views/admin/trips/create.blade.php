@extends('admin.layouts.default')

@section('content')

<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/trips/">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new trip was created.</template>
    <template slot="confirm">Continue Setup</template>
</alert-success>

<ajax-form method="post" action="/trips" :hidden-values="{{ json_encode([
    'campaign_id' => $campaign->id,
	'country_code' => $campaign->country_code
]) }}">

<template slot-scope="props">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">New Travel Group</h3>
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
                <select-group name="group_id">
                    <label slot="label">Organization</label>
                    <span class="help-block" slot="help-text">
                        Search organizations by entering a name. Select an organization to assign it.
                    </span>
                </select-group>

                <div class="row">
                    <div class="col-sm-6">
                        <input-select name="type" :options="{
                            'ministry': 'Ministry',
                            'family': 'Family',
                            'international': 'International',
                            'media': 'Media',
                            'medical': 'Medical',
                            'leader': 'Leader',
                            'sports': 'Sports',
                            'water': 'Water'
                        }">
                            <label slot="label">Trip Type</label>
                        </input-select>
                    </div>
                    <div class="col-sm-6">
                        <input-select name="difficulty" :options="{
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
                        <input-date name="started_at">
                            <label slot="label">Start Date</label>
                        </input-date>
                    </div>
                    <div class="col-sm-6">
                        <input-date name="ended_at">
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
            <h5>Additional Configuration</h5>
            <p class="text-muted">Other details and configurations for the trip.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <select-roles name="team_roles" :value="['MISS']">
                        <label slot="label">Roles Available at Registration</label>
                        <span class="help-block" slot="help-text">
                            Exclude leadership roles; those should be assigned by MM staff. Select all that apply.
                        </span>
                    </select-roles>

                    <select-prospects name="prospects">
                        <label slot="label">Who is this travel group ideal for? (optional)</label>
                        <span class="help-block" slot="help-text">
                            Helps users find teams right for them. Select all that apply.
                        </span>
                    </select-prospects>

                    <div class="row">
                        <div class="col-sm-6">
                            <input-number name="companion_limit" :value="0">
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
            <h5>Trip Rep</h5>
            <p class="text-muted">This is the representative that will be automatically assigned to any reservations created under this travel group. The trip rep can be overridden for individual reservations.</p>
            
            <p class="text-muted">The trip rep's contact information will be shown to all registered user.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <select-rep name="rep_id">
                        <label slot="label">Default Trip Rep (optional)</label>
                        <span class="help-block" slot="help-text">
                            Search trip rep by entering a name. Select the rep to assign them.
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
            <h5>Registration Settings</h5>
            <p class="text-muted">Registration will close or remain open based on these settings.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <div class="row">
                        <div class="col-sm-4">
                            <input-number name="spots" :value="0">
                                <label slot="label">Spots Available</label>
                                <span class="help-block" slot="help-text">
                                    When spots left reaches 0, registration will automatically close.
                                </span>
                            </input-number>
                        </div>
                        <div class="col-sm-8">
                            <input-datetime name="closed_at">
                                <label slot="label">Close Registration (optional)</label>
                                <span class="help-block" slot="help-text">
                                    Registration will automatically close at this date and time across all timezones.
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
            <a href="{{ url('/admin/campaigns/'.$campaign->id) }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</div>

</template>
</ajax-form>
@endsection