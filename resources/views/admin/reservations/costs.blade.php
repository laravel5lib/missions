@extends('admin.reservations.show')

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
    @slot('body')
    <div class="row">
        <div class="col-sm-3">
            <h4 class="text-primary">${{ $reservation->totalCostInDollars() }}</h4>
            <p class="small text-muted">Current Goal</p>
        </div>
        <div class="col-sm-4">
            <h4>{{ $reservation->activeCosts()->whereType('incremental')->first()->name }}</h4>
            <p class="small text-muted">Current Rate</p>
        </div>
        <div class="col-sm-5">
            <h4>50% June 1, 2018 11:59 pm</h4>
            <p class="small text-muted">Upcoming Deadline</p>
        </div>
    </div>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>Add a New Cost</h5>
    @endslot
    @slot('body')
    <div class="row">
        <div class="col-sm-6">
            <button class="btn btn-link btn-block">Use Trip Pricing</button>
        </div>
        <div class="col-sm-6">
            <button class="btn btn-link btn-block">Add Custom Pricing</button>
        </div>
    </div>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>New Custom Cost</h5>
    @endslot
    @slot('body')
<ajax-form method="post" action="/reservations/{{ $reservation->id }}/costs">

    <template slot-scope="props">
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
                <input-textarea name="short_desc">
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
            <div class="col-md-12">
                <div class="text-right">
                    <a class="btn btn-link" @click="$emit('open:add-cost-modal')">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Add</button>
                </div>
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
    <reservation-price-list reservation-id="{{ $reservation->id }}"></reservation-price-list>
@endcomponent

    <!-- <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments Due</h5>
        </div>
        <div class="panel-body">
            <admin-reservation-dues id="{{ $reservation->id }}"></admin-reservation-dues>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Applied Costs</h5>
        </div>
        <div class="panel-body">
            <admin-reservation-costs id="{{ $reservation->id }}"></admin-reservation-costs>
        </div>
    </div> -->
@endsection