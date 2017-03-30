@extends('dashboard.reservations.show')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/css/Jcrop.min.css" type="text/css">
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/js/Jcrop.min.js"></script>
@endsection

@section('tab')
    
    <div class="row">
        <div class="col-xs-12 tour-step-requirements">
            <reservation-requirements id="{{ $reservation->id }}" 
                                      user-id="{{ $reservation->user_id }}" 
                                      :age="{{ $reservation->age }}">
            </reservation-requirements>
        </div>
    </div>

@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'requirements',
                title: 'Travel Requirements',
                text: 'Every trip requires a certain set of documents and information to be submitted.',
                attachTo: {
                    element: '.tour-step-requirements',
                    on: 'top'
                },
            },
            {
                id: 'search',
                title: 'Find Requirements',
                text: 'Search or browse the list for a requirement to manage.',
                attachTo: {
                    element: '.tour-step-search',
                    on: 'top'
                },
            },
            {
                id: 'status',
                title: 'Check the Status',
                text: '<p>Requirements start as</p><p><span class="label label-danger small">incomplete</span></p><hr class="divider"><p>Submitted the required information and it will change to</p><p><span class="label label-default small">reviewing</span></p><hr class="divider"><p>Missions.Me staff will review your document and if everything is in order, the requirement will be statused as</p><p><span class="label label-success small">complete</span></p><hr class="divider"><p>If changes need to be made, look for a status of</p><p><span class="label label-info small">attention</span></p><hr class="divider"><p>You will need to achieve a complete status on all travel requirements to be travel ready.</p>',
                attachTo: {
                    element: '.tour-step-status',
                    on: 'top'
                },
            },
            {
                id: 'attach',
                title: 'Attach Documents',
                text: 'Attach an existing document from your records or create a new one.',
                attachTo: {
                    element: '.tour-step-attach',
                    on: 'top'
                },
            },
        ];
    </script>
@endsection