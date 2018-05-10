@if($reservation->getTotalCost() > 0)
    @component('panel')
        <div class="panel-heading">
            <h5>Cost Breakdown</h5>
        </div>
        <table class="table table-condensed">
            <tbody>
            @foreach($reservation->itemizedPrices()->breakdown() as $cost)
                <tr>
                    <td>
                        {{ $cost['name'] }}
                        @if(isset($cost['amount_due']))
                        <br />
                        <span class="small text-muted">You must raise at least <strong>{{ $cost['amount_due'] }}</strong> before <strong>{{ $cost['due_at'] }}</strong> to keep this discount.</span>
                        @endif
                    </td>
                    <td>{{ $cost['amount'] }}</td>
                </tr>
            @endforeach
                <tr class="active">
                    <td><strong>TOTAL</strong></td>
                    <td><strong>${{ $reservation->totalCostInDollars() }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endcomponent
@endif