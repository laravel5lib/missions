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

<reservation-pricing-overview id="{{ $reservation->id }}" ref="overview"></reservation-pricing-overview>

@component('panel')
    @slot('title')
        <h5>Add a New Cost</h5>
    @endslot
    @slot('body')
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#trip" aria-controls="home" role="tab" data-toggle="tab">Trip Pricing</a></li>
            <li role="presentation"><a href="#custom" aria-controls="profile" role="tab" data-toggle="tab">Custom Pricing</a></li>
        </ul>

        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Adding additional costs will affect fundraising goals.</div>
            </div>
        </div>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="trip">

                <ajax-form method="post" 
                           action="reservations/{{ $reservation->id }}/prices"
                           :initial="{price_id: null}" 
                           :horizontal="true"
                >
                    <template slot-scope="{ form }">
                        <div class="row">
                            <div class="col-sm-9">

                                <select-price name="price_id" 
                                            url="trips/{{ $reservation->trip_id }}/prices" 
                                            v-model="form.price_id"
                                            classes="col-sm-8"
                                            :horizontal="true">
                                    <label slot="label" class="control-label col-sm-4">Select a Price</label>
                                </select-price>

                            </div>
                            <div class="col-sm-3">
                                
                                <button type="submit" class="btn btn-md btn-primary">Add</button>

                            </div>
                        </div>
                    </template>
                </ajax-form>
            </div>
            
            <div role="tabpanel" class="tab-pane" id="custom">
                <price-add-new priceable-type="reservations" 
                               priceable-id="{{ $reservation->id }}" 
                               campaign-id="{{ $reservation->trip->campaign_id }}">
                </price-add-new>
            </div>
        </div>
    @endslot
@endcomponent

<price-list priceable-type="reservations" priceable-id="{{ $reservation->id }}"></price-list>
@endsection