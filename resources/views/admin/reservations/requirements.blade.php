@extends('admin.reservations.show')

@section('tab')
    <reservation-requirements id="{{ $reservation->id }}" 
                              user-id="{{ $reservation->user_id }}" 
                              :age="{{ $reservation->age }}">
    </reservation-requirements>
@endsection