@extends('dashboard.reservations.show')

@section('tab')

    <p>
        <a class="btn btn-link btn-sm" 
           href="{{ url('dashboard/reservations/'.$reservation->id.'/requirements') }}">
            <i class="fa fa-chevron-left text-muted"></i> Back
        </a>
    </p>

    @if($requirement->upfront && $requirement->pivot->status <> 'complete')
        <div class="alert alert-warning">
            <strong>One More Thing!</strong> Please provide the following information so we can complete your reservation.
        </div>
    @endif

    @if($requirement->document_type == 'airport_preferences')

        <airport-preference 
            reservation-id="{{ $reservation->id }}"
            :document="{{ json_encode($reservation->airportPreference) }}"
        ></airport-preference>

    @elseif($requirement->document_type == 'arrival_designations')

        <arrival-designation 
            reservation-id="{{ $reservation->id }}"
            :document="{{ json_encode($reservation->arrivalDesignation) }}"
        ></arrival-designation>

    @elseif($requirement->document_type == 'minor_releases')

        <minor-release 
            reservation-id="{{ $reservation->id }}"
            :document="{{ json_encode($reservation->minorRelease) }}"
        ></minor-release>

    @else

        <travel-document 
            type="{{ $requirement->document_type }}" 
            reservation-id="{{ $reservation->id }}"
            :requirement="{{ $requirement }}"
        ></travel-document>

    @endif
    
    @if(session('alert'))
        @push('scripts')
            <script>
                swal ("One More Thing!" ,  "Please provide the following information so we can complete your reservation." ,  "warning" )
            </script>
        @endpush
    @endif

@endsection