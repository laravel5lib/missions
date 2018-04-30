@extends('admin.layouts.default')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns/'.$trip->campaign->id.'/trips' => 'Trips', 
        'admin/trips/'.$trip->id =>  $trip->group->name.' - '.ucfirst($trip->type).' Trip',
        'admin/trips/'.$trip->id.'/prices' =>  'Pricing',
        'active' => $price->cost->name
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<hr class="divider inv lg">
<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/trips/{{ $trip->id }}/pricing">
    <template slot="title">Saved</template>
    <template slot="message">The price was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">Price Details</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Usage</h5>
			<p class="text-muted">A total count of trips and reservations where this price is assigned.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="text-primary">{{ $price->trips()->count() }}</h4>
                            <p class="small text-muted">Trips</p>
                        </div>
                        <div class="col-sm-6">
                            <h4 class="text-primary">{{ $price->reservations()->count() }}</h4>
                            <p class="small text-muted">Reservations</p>
                        </div>
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@if($price->model_type === 'trips' && $price->model_id === $trip->id)
<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Edit Price</h5>
            <p class="text-muted">USE CAUTION! Making changes will affect the trip and reservations including fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-edit priceable-type="trips" priceable-id="{{ $trip->id }}" id="{{ $price->uuid }}"></price-edit>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endif

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Delete</h5>
			<p class="text-muted">USE CAUTION! Removing this cost will also remove it from the trip and it's reservations. This will affect fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-delete priceable-type="trips" 
                              priceable-id="{{ $trip->id }}" 
                              id="{{ $price->uuid }}"
                              cost-name="{{ $price->cost->name}}">
                </price-delete>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection