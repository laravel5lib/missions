@extends('admin.reservations.show')

@section('tab')
<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Details</h5>
    </div>
    <div class="panel-body">
        <div class="col-md-7">
            <label>Reservation ID</label>
            <p>{{ $reservation->id }}</p>
            <hr class="divider">
            <div class="row">
                <div class="col-md-6">
                    <label>Surname</label>
                    <p>{{ $reservation->surname }}</p>
                </div>
                <div class="col-md-6">
                    <label>Given Names</label>
                    <p>{{ $reservation->given_names }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-md-6">
                    <label>Gender</label>
                    <p>{{ $reservation->gender }}</p>
                </div>
                <div class="col-md-6">
                    <label>Marital Status</label>
                    <p>{{ $reservation->status }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-md-6">
                    <label>Shirt Size</label>
                    <p>{{ $reservation->shirt_size }}</p>
                </div>
                <div class="col-md-6">
                    <label>Age</label>
                    <p>{{ $reservation->birthday->age }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-md-6">
                    <label>Birthday</label>
                    <p>{{ $reservation->birthday->format('F j, Y') }}</p>
                </div>
                <div class="col-md-6">
                    <label>Group</label>
                    <p><a href="{{ url('/admin/groups/' . $reservation->trip->group->id) }}">{{ $reservation->trip->group->name }}</a> <sup class="text-muted"><i class="fa fa-external-link"></i></p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-md-6">
                    <label>Trip Type</label>
                    <p><a href="{{ url('/admin/trips/' . $reservation->trip->id) }}">{{ ucwords($reservation->trip->type) }} Trip</a> <sup class="text-muted"><i class="fa fa-external-link"></i></sup></p>
                </div>
                <div class="col-md-6">
                    <label>Start Date</label>
                    <p>{{ $reservation->trip->started_at->toFormattedDateString() }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-md-6">
                    <label>End Date</label>
                    <p>{{ $reservation->trip->ended_at->toFormattedDateString() }}</p>
                </div>
                <div class="col-md-6">
                    <label>Trip Starts In</label>
                    <p>{{ $reservation->trip->started_at->diffInDays() }} days</p>
                </div>
            </div>
        </div>
        <div class="col-md-5 panel panel-default panel-body text-center">
            <label>Email</label>
            <p>{{ $reservation->email }}</p>
            <label>Home Phone</label>
            <p>{{ $reservation->phone_one }}</p>
            <label>Mobile Phone</label>
            <p>{{ $reservation->phone_two }}</p>
            <label>Address</label>
            <p>{{ $reservation->address }}</p>
            <label>City</label>
            <p>{{ $reservation->city }}</p>
            <label>State/Providence</label>
            <p>{{ $reservation->state }}</p>
            <label>Zip/Postal Code</label>
            <p>{{ $reservation->zip }}</p>
            <label>Country</label>
            <p>{{ country($reservation->country_code) }}</p>
        </div>
    </div>
    <div class="panel-footer text-right">
        <send-email label="Resend confirmation email" 
                 icon="fa fa-envelope"
                 class="btn btn-default btn-sm"
                 command="email:send-reservation-confirmation" 
                 :parameters="{id: '{{ $reservation->id }}', email: '{{ $reservation->user->email }}'}">
        </send-email>
    </div>
</div><!-- end panel -->
@endsection