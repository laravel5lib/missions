<div class="panel panel-default" v-tour-guide="">
    <div class="panel-heading">
        <h5>Pricing</h5>
    </div>
    @foreach($trip->costs as $cost)
    <div class="row">
        <div class="col-xs-12 tour-step-costs">

        <div class="panel-body">
            <h4>{{ $cost->name }}</h4>
            <div class="row">
                <div class="col-sm-12">
                    <p class="small">{{ $cost->description }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-sm-4 text-center">
                    <label>Cost Type</label>
                    <p>{{ $cost->type }}</p>
                </div>
                <div class="col-sm-4 text-center">
                    <label>Active Date</label>
                    <p>{{ date('F d, Y', strtotime($cost->active_at)) }}</p>
                </div>
                <div class="col-sm-4 text-center">
                    <label>Cost</label>
                    <p>${{ number_format($cost->amountInDollars(), 2) }}</p>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
            <div class="col-xs-12 tour-step-payments">
            <table class="table">
                <thead>
                <tr>
                    <th>Amount</th>
                    <th>Percent</th>
                    <th>Due</th>
                    <th>Grace</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cost->payments as $payment)
                    <tr>
                        <td>${{ number_format($payment->amountOwedInDollars(), 2) }}</td>
                        <td>{{ number_format($payment->percent_owed, 2) }}%</td>
                        <td>{{ $payment->upfront ? 'Upfront' : date('F d, Y', strtotime($payment->due_at)) }}</td>
                        <td>{{ $payment->upfront ? 'N/A' : $payment->grace_period }} {{ $payment->upfront ? '' : ($payment->grace_period > 1 ? 'days' : 'day') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            </div>
        </div>
        <hr class="divider">
        </div>
    </div>
    @endforeach
</div>

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'costs',
                title: 'Trip Costs',
                text: 'Most trips have costs assigned to them. Costs will only be applied to a reservation or become available on or after the <strong>active date</strong>.',
                attachTo: {
                    element: '.tour-step-costs',
                    on: 'top'
                },
            },
            {
                id: 'types',
                title: 'Types of Costs',
                text: 'There may be different kinds of costs associated with your trip.<br /><br /><strong>Incremental Costs</strong> are amounts that "increment" each time a traveler defaults on a payment deadline.<br /><br /><strong>Static costs</strong> are amounts applied to a reservation by default.<br /><br /><strong>Optional costs</strong> comprise of additions or perks that can be added onto a reservation.'
            },
            {
                id: 'payments',
                title: 'Payments',
                text: 'Every cost is broken down into one or more payments, each with an amount due either upfront or by a specified date. Payment amounts can be determined either by percentages or fixed amounts.',
                attachTo: {
                    element: '.tour-step-payments',
                    on: 'top'
                },
            },
        ];
    </script>
@endsection