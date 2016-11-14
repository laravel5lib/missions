<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Outstanding Requirements</h5>
    </div>
    <div style="height:250px;overflow:scroll;margin-top:-1px;">
        <table class="table table-hover table-responsive">
            <tbody>
            @forelse(auth()->user()->outstandingRequirements() as $requirement)
                <tr>
                    <td style="padding:10px 15px;">{{ $requirement->name }}</td>
                    <td style="padding:10px 15px;font-size:10px;vertical-align:middle;" class="text-right text-muted">{{ $requirement->due_at->format('M j') }}</td>
                </tr>
                @empty
                    <tr><td><p class="text-muted text-center lead"><strong>Everything is in order!</strong><br /><small>No requirements pending.</small></p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="panel-footer text-center" style="padding:10px;">
        <a class="small" style="color:#bcbcbc;" href="#">View All Requirements</a>
    </div>
</div>