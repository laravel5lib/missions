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

@component('panel')
    @slot('title')
        <h5>Add a New Cost</h5>
    @endslot
    @slot('body')
    <ajax-form method="post" action="/campaigns/{{ $campaign->id }}/costs">

    <template slot-scope="props">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Campaign rates will be set as the default pricing for it's trips. Making changes to these rates will effect any trips using them. You can always override these rates for each trip.</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

                <input-select name="type" value="incremental" :options="{
                    'incremental': 'Registration', 'optional': 'Rooming', 'static': 'Other'
                }">
                    <label slot="label">Cost Applies To</label>
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

                <input-currency name="amount" :value="0">
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
                <input-textarea name="short_desc" :rows="2">
                    <label slot="label">Short Description</label>
                    <span class="help-block" slot="help-text">This short description will be shown to users.</span>
                </input-textarea>
            </div>
        </div>
        <div class="row" v-if="props.form.type == 'incremental'">
            <div class="col-md-12">
                <label>Payments</label>
                <div class="row">
                    <div class="col-xs-4 col-md-2">
                        <input-number name="payments.percent" :placeholder="50">
                            <span class="help-block" slot="help-text">Percentage due</span>
                        </input-number>
                    </div>
                    <div class="col-xs-8 col-md-4">
                        <input-date name="due_at">
                            <span class="help-block" slot="help-text">Due Date</span>
                        </input-date>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-md btn-primary">Add</button>
            </div>
    </div>
    </template>

    </ajax-form>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>Current Pricing</h5>
    @endslot
    <campaign-price-list campaign-id="{{ $campaign->id }}"></campaign-price-list>
@endcomponent

@endsection