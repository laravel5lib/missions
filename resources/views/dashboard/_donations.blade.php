<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Recent Donations</h5>
    </div>
    <div style="height:250px;overflow:scroll;margin-top:-1px;">
        <table class="table table-hover table-responsive">
            <tbody>
            @forelse(auth()->user()->recentDonations() as $donation)
                <tr>
                    <td style="padding:20px;"><h3 class="text-success" style="margin:0px;">${{ $donation->amount }}</h3><span class="small">{{ $donation->donor->name }}</span></td>
                    <td class="text-right text-muted" style="padding:20px;vertical-align:middle;">{{ $donation->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                    <tr><td><p class="text-muted text-center lead"><strong>Don't give up!</strong><br /><small>No recent donations.</small></p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel-footer text-center" style="padding:10px;">
        <a class="small" style="color:#bcbcbc;" href="#">View All Donations</a>
    </div>
</div>