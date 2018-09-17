@extends('admin.reservations.show')

@section('tab')

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Traveler Info</h5>
            </div>
            <div class="col-xs-4 text-right">
                <a href="{{ url('admin/reservations/'.$reservation->id.'/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit</a>
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
        'Email' => '<a href="mailto:'.$reservation->email.'"><strong>'.$reservation->email.'</strong></a>',
        'Home Phone' => '<a href="tel:'.$reservation->phone_one.'"><strong>'.$reservation->phone_one.'</strong></a>',
        'Mobile Phone' => '<a href="tel:'.$reservation->phone_two.'"><strong>'.$reservation->phone_two.'</strong></a>',
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
                <a href="{{ url('admin/reservations/'.$reservation->id.'/transfer') }}" class="btn btn-default btn-sm"><i class="fa fa-exchange"></i> Transfer</a>
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Campaign' => '<a href="/admin/campaigns/'.$reservation->trip->campaign_id.'"><strong>'.$reservation->trip->campaign->name.'</strong></a>',
        'Country' => country($reservation->trip->campaign->country_code),
        'Group' => '<a href="/admin/campaign-groups/'.$group->uuid.'"><strong>'.$reservation->trip->group->name.'</strong></a>',
        'Trip Type' => '<a href="'.url('/admin/trips/'. $reservation->trip->id).'"><strong>'.ucfirst($reservation->trip->type).'</strong></a>',
        'Tags' => function() use($reservation) {
            foreach($reservation->trip->tags as $tag) {
                echo '<span class="label label-filter">'.ucwords($tag->name).'</span>';
            }
        },
        'Start Date' => $reservation->trip->started_at->format('F j, Y'),
        'End Date' => $reservation->trip->ended_at->format('F j, Y'),
        'Trip Rep' => '<a href="'.url('admin/representatives/'.optional($reservation->getRep())->id).'"><strong>'.optional($reservation->getRep())->name.'</strong></a>',
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
        'Managing Account' => '<a href="/admin/users/'.$reservation->user_id.'"><strong>'.$reservation->user->name.'</strong></a>',
        'Date Registered' => $reservation->created_at->format('F j, Y h:i a'),
        'Reservation ID' => $reservation->id
    ]])
    @endcomponent
@endcomponent

@unless($reservation->deleted_at)
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
                        label="Enter the missionary's full name to drop it"
                        button="Drop"
                        match-value="{{ $reservation->name }}">
        </delete-form>
    @endslot
@endcomponent
@endunless

@endsection