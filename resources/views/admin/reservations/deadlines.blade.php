@extends('admin.reservations.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Deadlines</h5>
        </div>
        <div class="panel-body">
            <admin-reservation-deadlines id="{{ $reservation->id }}"></admin-reservation-deadlines>
        </div>
    </div>
@endsection