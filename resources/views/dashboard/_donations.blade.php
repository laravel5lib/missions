<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Recent Donations</h5>
    </div>
    <table class="table table-hover" style="min-height:220px;">
        <tbody>
        @foreach(auth()->user()->recentDonations() as $donation)
            <tr>
                <td class="text-success">${{ $donation->amount }}</td>
                <td class="text-right text-muted">{{ $donation->created_at->format('M j') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer text-center">
        <a class="btn btn-default-hollow btn-xs">See All</a>
    </div>
</div>