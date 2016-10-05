<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Upcoming Payments</h5>
    </div>
    <table class="table table-hover" style="min-height:220px;">
        <tbody>
        @foreach(auth()->user()->upcomingPayments() as $payment)
            <tr>
                <td class="text-danger">${{ $payment->outstanding_balance }}</td>
                <td class="text-right">
                    @if ($payment->getStatus() == 'late' or $payment->getStatus() == 'overdue')
                        <span class="badge badge-danger">Late</span>
                    @elseif($payment->getStatus() == 'paid')
                        <span class="badge badge-success">Paid</span>
                    @else
                        <span class="badge badge-info">Due in {{ $payment->due_at->diffForHumans(null, true) }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer text-center">
        <a class="btn btn-default-hollow btn-xs">See All</a>
    </div>
</div>