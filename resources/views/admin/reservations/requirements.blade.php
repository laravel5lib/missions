@extends('admin.reservations.show')

@section('tab')

    <reservation-requirements id="{{ $reservation->id }}"></reservation-requirements>

@endsection