@extends('admin.layouts.default')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        '/admin/campaigns/'.$trip->campaign->id.'/groups' => $trip->campaign->name.' - '.country($trip->country_code),
        '/admin/campaign-groups/'.$group->uuid.'/trips' => $trip->group->name,
        'admin/trips/'.$trip->id.'/prices' =>  ucfirst($trip->type).' Trip',
        'active' => $price->cost->name
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/trips/{{ $trip->id }}/prices">
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
			<p class="text-muted">A total count of reservations where this price is assigned.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <div class="row">
                        <div class="col-sm-4">
                            <h4 class="text-primary">
                                {{ $price->reservations_count }} <span class="text-muted">/ {{ $trip->reservations_count }}<span>
                            </h4>
                            <p class="small text-muted">Reservations</p>
                        </div>
                        @if($price->cost->type != 'incremental')
                        <div class="col-sm-8">
                            <p class="small text-muted">You can add this price to any existing reservations where it has not been assigned yet.</p>
                            <price-push url="trips/{{ $trip->id }}/prices/{{ $price->uuid }}/push">
                                <div slot-scope="{ addPrice, processing }">
                                    <button @click="addPrice" class="btn btn-default btn-sm">
                                        <span v-if="processing" v-cloak><i class="fa fa-spinner fa-spin"></i> Processing...</span>
                                        <span v-else>Add to all existing reservations</span>
                                    </button>
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

@can('update', $price->cost)
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
                <price-edit priceable-type="trips" 
                            priceable-id="{{ $trip->id }}" 
                            :price="{{ $price }}">
                </price-edit>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endif
@endcan

<hr class="divider inv">

@can('delete', $price->cost)
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
@endcan

@endsection