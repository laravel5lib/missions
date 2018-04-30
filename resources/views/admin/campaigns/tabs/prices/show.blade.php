@extends('layouts.admin')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li><a href="{{ url('/admin/campaigns') }}">Campaigns</a></li>
                    <li><a href="{{ url('/admin/campaigns/'.$campaign->id.'/prices') }}">Pricing</a></li>
                    <li class="active">{{ $price->cost->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaigns/{{ $campaign->id }}/prices/">
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

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Edit Price</h5>
            <p class="text-muted">USE CAUTION! Making changes will effect trips and reservations including fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-edit priceable-type="campaigns" priceable-id="{{ $campaign->id }}" id="{{ $price->uuid }}"></price-edit>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Delete</h5>
			<p class="text-muted">USE CAUTION! Removing this cost will also remove it from any trips or reservations. This will affect fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-delete priceable-type="campaigns" 
                              priceable-id="{{ $campaign->id }}" 
                              id="{{ $price->uuid }}"
                              cost-name="{{ $price->cost->name}}">
                </price-delete>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection