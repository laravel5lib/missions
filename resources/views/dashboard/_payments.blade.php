<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Payments Due Soon</h5>
    </div>
    <div style="height:250px;overflow:scroll;margin-top:-1px;">
        <table class="table table-hover table-responsive">
            <tbody>
            @forelse(auth()->user()->upcomingPayments() as $payment)
                <tr>
                    <td style="padding:10px 15px;">
                        <h4 style="margin:0px;">
                            ${{ $payment->outstanding_balance }} &middot; 
                            <small>
                                {{ $payment->payment->percent_owed }}% of {{ $payment->payment->cost->name }}
                            </small>
                        </h4>
                        <small>
                            <a href="{{ url('/dashboard/reservations/' . $payment->payable->id . '/dues') }}">
                            {{ $payment->payable->name }}'s Trip to 
                            {{ country($payment->payable->trip->country_code) }}
                            </a>
                        </small>
                    </td>
                    <td class="text-right" style="padding:10px 15px;">
                        @if ($payment->getStatus() == 'late' or $payment->getStatus() == 'overdue')
                            <span class="text-danger">Late</span>
                        @elseif($payment->getStatus() == 'paid')
                            <span class="text-success">Paid</span>
                        @else
                            <span class="text-info">Due in {{ $payment->due_at->diffForHumans(null, true) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <div style="padding: 40px 0;">
                    <p class="text-muted text-center"><em>Congrats, you're all paid up!<br/><small>Start a new project or go on a trip!</small></em></p>
                    <p class="text-center"><a class="btn btn-link btn-sm" href="/campaigns">Trips</a>
                        <a class="btn btn-link btn-sm" href="/sponsor-a-project">Projects</a></p>
                </div>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel-footer text-center text-muted small" style="padding:10px;">
        Showing {{ auth()->user()->upcomingPayments()->count() }} Payments
    </div>
</div>