@extends('admin.reservations.show')

@section('tab')

    <todos type="reservations"
           id="{{ $reservation->id }}"
           user_id="{{ auth()->user()->id }}"
           :can-modify="{{ auth()->user()->can('modify-todos') }}">
    </todos>

@endsection