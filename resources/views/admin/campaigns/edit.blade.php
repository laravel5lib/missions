@extends('admin.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection

@section('content')
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaigns/">
    <template slot="title">Saved</template>
    <template slot="message">The campaign was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<ajax-form method="put" action="/campaigns/{{ $campaign->id }}">
<template slot-scope="props">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">Edit Campaign</h3>
        </div>
    </div>
</div>

<hr class="divider inv">
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Campaign Details</h5>
            <p class="text-muted">These details appear to the end-user in the <a href="{{ url('trips') }}" target="_blank">campaign cards</a>.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <div class="row">
                    <div class="col-sm-6">
                        <input-text name="name" value="{{ $campaign->name }}">
                            <label slot="label">Campaign Name</label>
                        </input-text>
                    </div>
                    <div class="col-sm-6">
                        <select-country name="country_code" value="{{ $campaign->country_code }}"></select-country>
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
            <h5>Campaign Dates</h5>
			<p class="text-muted">The start and end of the campaign. These dates are visible to the end-user.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <div class="row">
                    <div class="col-sm-6">
                        <input-date name="started_at" value="{{ $campaign->started_at->toDateString() }}">
                            <label slot="label">Start Date</label>
                        </input-date>
                    </div>
                    <div class="col-sm-6">
                        <input-date name="ended_at" value="{{ $campaign->ended_at->toDateString() }}">
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
            <h5>Campaign Page (optional)</h5>
			<p class="text-muted">Manage settings for the public landing page that is visible to the end-user.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <input-text name="page_url" value="{{ $campaign->slug->url }}">
                        <label slot="label">Page Url</label>
                        <span class="input-group-addon" slot="prefix">{{ url('/') }}/</span>
                    </input-text>

                    <div class="row">
                        <div class="col-md-6">
                            <input-text name="page_src" value="{{ $campaign->page_src }}">
                                <label slot="label">Page Source File</label>
                                <span class="help-block" slot="help-text">Source files are stored at <code>/views/site/campaigns/partials/</code></span>
                                <span class="input-group-addon" slot="suffix">.blade.php</span>
                            </input-text>
                        </div>
                    </div>

                    <input-textarea name="short_desc" value="{{ $campaign->short_desc }}">
                        <label slot="label">Short Description</label>
                        <span class="help-block" slot="help-text">This short description will appear on the trips page.</span>
                    </input-textarea>

                    <div class="row">
                        <div class="col-md-6">
                            <input-datetime name="published_at" value="{{ $campaign->published_at }}">
                                <label slot="label">Publish Page</label>
                                <span class="help-block" slot="help-text">Set a date and time to make the page public or leave blank to keep hidden.</span>
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
            <a href="{{ url('campaigns/' . $campaign->id) }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</div>

</template>
</ajax-form>
@endsection