@extends('admin.reservations.show')

@section('tab')

    <reservation-requirements id="{{ $reservation->id }}" :age="{{ $reservation->age }}"></reservation-requirements>

@endsection