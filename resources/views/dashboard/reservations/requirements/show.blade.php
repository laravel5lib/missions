@extends('dashboard.reservations.show')

@section('tab')

    <p>
        <a class="btn btn-link btn-sm" 
           href="{{ url('dashboard/reservations/'.$reservation->id.'/requirements') }}">
            <i class="fa fa-chevron-left text-muted"></i> Back
        </a>
    </p>

    <travel-document 
        type="{{ $requirement->document_type }}" 
        reservation-id="{{ $reservation->id }}"
        :requirement="{{ $requirement }}"
    ></travel-document>

@endsection