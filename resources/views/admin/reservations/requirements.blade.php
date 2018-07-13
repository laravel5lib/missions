@extends('admin.reservations.show')

@section('tab')
    <requirements-manager 
        inheritable-id="{{ $reservation->trip_id }}" 
        inheritable-type="trips" 
        requester-type="reservations" 
        requester-id="{{ $reservation->id }}"
    ></requirements-manager>
@endsection