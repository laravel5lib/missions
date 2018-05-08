@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$group->campaign_id => $group->campaign->name.' - '.country($group->campaign->country_code),
        'admin/campaigns/'.$group->campaign_id.'/groups' => 'Groups',
        'admin/campaign-groups/'.$group->uuid => $group->organization->name,
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

<alert-success redirect="/admin/campaign-groups/{{ $group->uuid }}/prices">
    <template slot="title">Saved</template>
    <template slot="message">The price was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>


<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">{{ $price->cost->name }} Price</h3>
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

@if($price->model_type === 'campaign-groups' && $price->model_id === $group->uuid)
<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Edit Price</h5>
            <p class="text-muted">USE CAUTION! Making changes will affect trips and reservations including fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-edit priceable-type="campaign-groups" 
                            priceable-id="{{ $group->uuid }}"
                            :price="{{ $price }}">
                </price-edit>
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
			<p class="text-muted">USE CAUTION! Deleting this cost will also remove it from trips and reservations. This will affect fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-delete priceable-type="campaign-groups" 
                              priceable-id="{{ $group->uuid }}" 
                              id="{{ $price->uuid }}"
                              cost-name="{{ $price->cost->name}}">
                </price-delete>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection