@extends('admin.layouts.default')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'admin/reservations/'.$reservation->id.'/costs' => 'Pricing',
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

<alert-success redirect="/admin/reservations/{{ $reservation->id }}">
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

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Manage</h5>
            <p class="text-muted">Lock in the price or extend the grace period.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <div class="row">
                    <div class="col-md-6">
                        <label>Lock this price</label>
                        <span class="help-block">Lock this reservation into the current price. Even if the user is late on payment, their pricing will not change.</span>
                        <button class="btn btn-sm btn-default"><i class="fa fa-unlock"></i> Unlocked</button>
                    </div>
                    <div class="col-md-6">
                        <label>Extend Grace Period</label>
                        <span class="help-block">Increase the grace period to give the user a little more time before they default.</span>
                        <div class="input-group input-group-sm col-xs-8">
                            <span class="input-group-addon">
                                Days:
                            </span>
                            <input type="number" class="form-control" value="3">
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-default">Update</button>
                            </span>
                        </div>
                    </div>
                </div>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@if($price->model_type === 'reservations' && $price->model_id === $reservation->id)
<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Edit Price</h5>
            <p class="text-muted">USE CAUTION! Making changes will affect fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-edit priceable-type="reservations" 
                            priceable-id="{{ $reservation->id }}" 
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
			<p class="text-muted">USE CAUTION! Removing this cost will affect fundraising goals and deadlines.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                <price-delete priceable-type="reservations" 
                              priceable-id="{{ $reservation->id }}" 
                              id="{{ $price->uuid }}"
                              cost-name="{{ $price->cost->name}}">
                </price-delete>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection