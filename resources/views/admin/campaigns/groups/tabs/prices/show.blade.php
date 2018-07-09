@extends('layouts.admin')
@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$group->campaign_id => $group->campaign->name.' - '.country($group->campaign->country_code),
        'admin/campaigns/'.$group->campaign_id.'/groups' => 'Groups',
        'admin/campaign-groups/'.$group->uuid.'/prices' => $group->organization->name,
        'active' => $price->cost->name
    ]])
    @endbreadcrumbs
    
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
                            <p class="small text-muted"><strong>Trips</strong></p>
                        </div>
                        <div class="col-sm-6">
                            <h4 class="text-primary">{{ $price->reservations()->count() }}</h4>
                            <p class="small text-muted"><strong>Reservations</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="small text-muted">You can add this price to any existing trips where it has not been assigned yet.</p>
                            <price-push url="campaign-groups/{{ $group->uuid }}/prices/{{ $price->uuid }}/push">
                                <div slot-scope="{ addPrice }">
                                    <button @click="addPrice" class="btn btn-default btn-sm">Add only to existing trips</button>
                                </div>
                            </price-push>
                        </div>
                        @if($price->cost->type != 'incremental')
                        <div class="col-sm-6">
                            <p class="small text-muted">You can add this price to any existing trips and reservations where it has not been assigned yet.</p>
                            <price-push url="campaign-groups/{{ $group->uuid }}/prices/{{ $price->uuid }}/push" :params="{ with_reservations: true }">
                                <div slot-scope="{ addPrice }">
                                    <button @click="addPrice" class="btn btn-default btn-sm">Add to all trips &amp; reservations</button>
                                </div>
                            </price-push>
                        </div>
                        @endif
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