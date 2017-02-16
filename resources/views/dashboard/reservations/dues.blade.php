@extends('dashboard.reservations.show')

@section('tab')
    <reservation-dues id="{{ $reservation->id }}"></reservation-dues>
    <reservation-costs id="{{ $reservation->id }}"></reservation-costs>
@stop