@extends('dashboard.reservations.show')

@section('tab')
    <div class="row">
        <div class="col-xs-12 tour-step-payments">
            <reservation-dues id="{{ $reservation->id }}"></reservation-dues>
        </div>
        <div class="col-xs-12 tour-step-costs">
            <reservation-costs id="{{ $reservation->id }}"></reservation-costs>
        </div>
    </div>
@stop

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'payments',
                title: 'Payments Due',
                text: 'Each cost applied to your trip is broken down into a series of payments.',
                attachTo: {
                    element: '.tour-step-payments',
                    on: 'top'
                },
            },
            {
                id: 'payment-details',
                title: 'Payments Due',
                text: 'Every payment has a running balance, a due date, and a status. Please note that if a payment is overdue, your costs might increase.',
                attachTo: {
                    element: '.tour-step-payment-details',
                    on: 'top'
                },
            },
            {
                id: 'costs',
                title: 'Applied Costs',
                text: 'See all the costs that have been applied to your reservation.',
                attachTo: {
                    element: '.tour-step-costs',
                    on: 'top'
                },
            },
            {
                id: 'addons',
                title: 'Trip Additions',
                text: 'Add or remove additional perks to your trip.',
                attachTo: {
                    element: '.tour-step-addons',
                    on: 'top'
                },
            }
        ];
    </script>
@endsection