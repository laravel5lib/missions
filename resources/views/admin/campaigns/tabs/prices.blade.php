@extends('admin.campaigns.show')

@section('tab')

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new price was added.</template>
</alert-success>

@component('panel')
    @slot('body')
    <div class="row">
        <div class="col-sm-3">
            <h4 class="text-primary">${{ number_format($campaign->current_starting_cost, 2, '.', ',') }}</h4>
            <p class="small text-muted">Current Starting Cost</p>
        </div>
        <div class="col-sm-4">
            <h4>{{ optional(optional($campaign->getCurrentRate())->cost)->name }}</h4>
            <p class="small text-muted">Current Rate</p>
        </div>
        <div class="col-sm-5">
            <h4>N/A</h4>
            <p class="small text-muted">Upcoming Deadline</p>
        </div>
    </div>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>Add New Price</h5>
    @endslot
    @slot('body')
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <div class="row">
                        <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                        <div class="col-xs-11">Campaign prices will be set as the default pricing for it's trips.</div>
                    </div>
                </div>
            </div>
        </div>
        <price-add-new priceable-type="campaigns" priceable-id="{{ $campaign->id }}"></price-add-new>
    @endslot
@endcomponent

<price-list priceable-type="campaigns" priceable-id="{{ $campaign->id }}"></price-list>

@endsection