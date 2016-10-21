@extends('dashboard.reservations.show')

@section('tab')
    <reservation-costs id="{{ $reservation->id }}"></reservation-costs>
    <reservation-dues id="{{ $reservation->id }}"></reservation-dues>
@stop