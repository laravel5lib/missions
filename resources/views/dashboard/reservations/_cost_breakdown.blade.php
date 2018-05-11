@if($reservation->getTotalCost() > 0)
    @component('panel')
        <div class="panel-heading">
            <h5>Cost Breakdown</h5>
        </div>
        @if($reservation->itemizedPrices()->hasPendingLateFee())
        <div class="panel-body">
            <div class="alert alert-warning" style="margin-bottom: 0">
                <div class="row">
                    <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                    <div class="col-xs-11">Raise <strong>${{ $reservation->totalCostInDollars() }}</strong> before <strong>{{ $reservation->itemizedPrices()->lateFee()['applied_at'] }}</strong> to avoid a <strong>{{ $reservation->itemizedPrices()->lateFee()['late_fee'] }}</strong> additional cost.</div>
                </div>
            </div>
        </div>
        @endif
        <table class="table table-condensed">
            <tbody>
            @foreach($reservation->itemizedPrices()->breakdown() as $cost)
                <tr>
                    <td>
                        {{ $cost['name'] }}
                        @if(isset($cost['amount_due']))
                        <br />
                        <span class="small text-muted">Raise at least <strong>{{ $cost['amount_due'] }}</strong> before <strong>{{ $cost['due_at'] }}</strong> to keep this discount.</span>
                        @endif
                    </td>
                    <td class="text-right">{{ $cost['amount'] }}</td>
                </tr>
            @endforeach
                <tr class="active">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right"><strong>${{ $reservation->totalCostInDollars() }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endcomponent
@endif