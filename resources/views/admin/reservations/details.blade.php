@extends('admin.reservations.show')

@section('tab')

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Traveler Info</h5>
            </div>
            <div class="col-xs-4 text-right">
                <button class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit</button>
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Name' => $reservation->name,
        'Gender' => ucwords($reservation->gender),
        'Marital Status' => ucwords($reservation->status),
        'Birthday' => $reservation->birthday->format('F j, Y'),
        'Age' => $reservation->birthday->age,
        'Team Role' => teamRole($reservation->desired_role),
        'Shirt Size' => shirtSize($reservation->shirt_size),
        'Email' => $reservation->email,
        'Home Phone' => $reservation->phone_one,
        'Mobile Phone' => $reservation->phone_two,
        'Address' => $reservation->address.'<br />'.$reservation->city.', '.$reservation->state.' '.$reservation->zip.'<br />'.country($reservation->country_code)
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Trip Details</h5>
            </div>
            <div class="col-xs-4 text-right">
                <button class="btn btn-default btn-sm"><i class="fa fa-exchange"></i> Transfer</button>
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Campaign' => $reservation->trip->campaign->name,
        'Country' => country($reservation->trip->campaign->country_code),
        'Group' => $reservation->trip->group->name,
        'Trip Type' => '<a href="'.url('/admin/trips/'. $reservation->trip->id).'">'.ucfirst($reservation->trip->type).'</a>',
        'Start Date' => $reservation->trip->started_at->format('F j, Y'),
        'End Date' => $reservation->trip->ended_at->format('F j, Y')
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Registration Details</h5>
            </div>
            <div class="col-xs-4 text-right">
                <send-email label="Resend Confirmation"
                    icon="fa fa-envelope icon-left"
                    classes="btn btn-default btn-sm"
                    command="email:send-reservation-confirmation"
                    :parameters="{id: '{{ $reservation->id }}', email: '{{ $reservation->user->email }}'}">
                </send-email>
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Managing Account' => $reservation->user->name,
        'Date Registered' => $reservation->created_at->format('F j, Y h:i a'),
        'Reservation ID' => $reservation->id
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <h5>Drop Reservation</h5>
    @endslot
    @slot('body')
        <div class="alert alert-warning">
            <div class="row">
                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                <div class="col-xs-11">USE CAUTION! This will drop the reservation, remove it from any squads, flights, or rooms, and disable fundraising.</div>
            </div>
        </div>
        <delete-form url="reservations/{{ $reservation->id }}" 
                        redirect="/admin/reservations/{{ $reservation->id }}"
                        match-key="traveler's full name"
                        match-value="{{ $reservation->name }}">
        </delete-form>
    @endslot
@endcomponent

@endsection