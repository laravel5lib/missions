@extends('admin.campaigns.show')

@section('tab')

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new cost was added.</template>
</alert-success>

<ajax-form method="post" action="/campaigns/{{ $campaign->id }}/costs">

<template slot-scope="props">
    @component('panel')
        @slot('title')
            <h5>New Cost</h5>
        @endslot
        @slot('body')
            <div class="row">
                <div class="col-md-6">

                    <input-select name="type" value="incremental" :options="{
                        'incremental': 'Registration', 'optional': 'Rooming', 'static': 'Other'
                    }">
                        <label slot="label">Cost Type</label>
                    </input-select>

                </div>
                <div class="col-md-6">

                    <input-text name="name">
                        <label slot="label">Cost Name</label>
                    </input-text>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                    <input-currency name="amount">
                        <label slot="label">Amount</label>
                    </input-currency>

                </div>
                <div class="col-md-6">

                    <input-date name="active_at">
                        <label slot="label">Effective Date</label>
                    </input-date>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">   
                    <input-textarea name="short_desc">
                        <label slot="label">Short Description</label>
                        <span class="help-block" slot="help-text">This short description will be shown to users.</span>
                    </input-textarea>
                </div>
            </div>
        @endslot
        @slot('footer')
            <div class="text-right">
                <a href="{{ url('/admin/campaigns/' . $campaign->id . '/prices') }}" class="btn btn-link">Cancel</a>
                <button type="submit" class="btn btn-md btn-primary">Add</button>
            </div>
        @endslot
    @endcomponent
</template>

</ajax-form>

@endsection