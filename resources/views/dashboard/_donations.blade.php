<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Recent Donations</h5>
    </div>
    <div style="height:250px;overflow:scroll;margin-top:-1px;">
        <table class="table table-hover table-responsive">
            <tbody>
            @forelse(auth()->user()->recentDonations() as $donation)
                <tr>
                    <td style="padding:5px 15px;vertical-align:middle;"><h4 class="text-success" style="margin:0px;">${{ $donation->amount }}</h4><span class="small">{{ $donation->donor->name }}</span></td>
                    <td class="text-right text-muted" style="vertical-align:middle;font-size:10px;">{{ $donation->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                    <div style="padding: 40px 0;">
                        <p class="text-muted text-center"><em>No recent donations but don't give up!<br/><small>Check out these fundraising tips.</small></em></p>
                        <hr class="divider inv">
                        <p class="text-center"><a class="btn btn-link btn-sm" href="/fundraisers#fundraising">Fundraising Tips</a></p>
                    </div>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel-footer text-center" style="padding:10px;">
        <a class="small" style="color:#bcbcbc;" href="#">View All Donations</a>
    </div>
</div>