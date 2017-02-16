@extends('admin.reservations.show')

@section('tab')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments Due</h5>
        </div>
        <div class="panel-body">
            <admin-reservation-dues id="{{ $reservation->id }}"></admin-reservation-dues>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Applied Costs</h5>
        </div>
        <div class="panel-body">
            <admin-reservation-costs id="{{ $reservation->id }}"></admin-reservation-costs>
        </div>
    </div>
@endsection