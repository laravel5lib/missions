@extends('admin.layouts.default')

@section('content')

<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000" redirect="/admin/campaigns/">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new campaign was started.</template>
    <template slot="cancel">Start Another</template>
    <template slot="confirm">Continue</template>
</alert-success>

<ajax-form method="post" action="/campaigns">

<template slot-scope="props">

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">Start New Campaign</h3>
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
                        <input-text name="name">
                            <label slot="label">Campaign Name</label>
                        </input-text>
                    </div>
                    <div class="col-sm-6">
                        <select-country name="country_code"></select-country>
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

<div class="container">
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="{{ url('campaigns') }}" class="btn btn-link">Cancel</a>
            <button type="submit" class="btn btn-primary">Start Campaign</button>
        </div>
    </div>
</div>

</template>
</ajax-form>
@endsection