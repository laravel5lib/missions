@extends('admin.reservations.show')

@section('tab')

    <notes type="reservations"
           id="{{ $reservation->id }}"
           user_id="{{ auth()->user()->id }}"
           :per_page="3"
           :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
    </notes>

@endsection