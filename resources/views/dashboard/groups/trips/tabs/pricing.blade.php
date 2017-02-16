<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Pricing</h5>
    </div>
    @foreach($trip->costs as $cost)
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
                    <p>${{ number_format($cost->amount, 2) }}</p>
                </div>
            </div>
            <hr class="divider">
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
                        <td>${{ number_format($payment->amount_owed, 2) }}</td>
                        <td>{{ number_format($payment->percent_owed, 2) }}%</td>
                        <td>{{ $payment->upfront ? 'Upfront' : date('F d, Y', strtotime($payment->due_at)) }}</td>
                        <td>{{ $payment->upfront ? 'N/A' : $payment->grace_period }} {{ $payment->upfront ? '' : ($payment->grace_period > 1 ? 'days' : 'day') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <hr class="divider">
    @endforeach
</div>