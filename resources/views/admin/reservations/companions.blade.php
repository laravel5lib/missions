@extends('admin.reservations.show')

@section('tab')
    <companion-manager reservation-id="{{ $reservation->id }}"></companion-manager>
@endsection