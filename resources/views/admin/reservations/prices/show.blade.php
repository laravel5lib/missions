@extends('admin.layouts.default')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'admin/reservations/'.$reservation->id.'/costs' => 'Pricing',
        'active' => $price->cost->name
    ]])
    @endbreadcrumbs
    
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
                <div class="alert alert-warning">
                    <div class="row">
                        <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                        <div class="col-xs-11">USE CAUTION! Deleting this price will affect fundraising goals and deadlines.</div>
                    </div>
                </div>
                <delete-form url="reservations/{{ $reservation->id }}/prices/{{ $price->uuid }}" 
                            redirect="/admin/reservations/{{ $reservation->id}}/costs"
                            label="Enter the price name to delete it"
                            match-value="{{ $price->cost->name }}">
                </delete-form>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

@endsection