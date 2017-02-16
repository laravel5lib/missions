<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Outstanding Requirements</h5>
    </div>
    <div style="height:250px;overflow:scroll;margin-top:-1px;">
        <table class="table table-hover table-responsive">
            <tbody>
            @forelse(auth()->user()->outstandingRequirements() as $requirement)
                <tr>
                    <td style="padding:10px 15px;">
                        <h4 style="margin:0px;">{{ $requirement->requirement->name }}</h4>
                        <span class="small">{{ $requirement->reservation->name }}'s trip to 
                        {{ country($requirement->reservation->trip->country_code) }}</span>
                    </td>
                    
                    <td class="text-right" style="padding:10px 15px;">
                        @if ($requirement->requirement->due_at->isPast())
                            <span class="text-danger">Past Due</span>
                        @else
                            <span class="text-info">
                                Due in {{ $requirement->requirement->due_at->diffForHumans(null, true) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                    <div style="padding: 40px 0;">
                        <p class="text-muted text-center"><em>Everything is in order!<br/><small>No requirements pending.</small></em></p>
                    </div>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- <div class="panel-footer text-center" style="padding:10px;">
        <a class="small" style="color:#bcbcbc;" href="#">View All Requirements</a>
    </div> --}}
</div>