@extends('admin.causes.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-4">
                    <h5>Funding</h5>
                </div>
                <div class="col-xs-8 text-right">
                    @if($project->fund->fundraisers->count())
                        <a class="btn btn-sm btn-default-hollow" href="/fundraisers/{{ $project->fund->fundraisers->first()->id }}/edit">Manage Fundraiser</a>
                    @else
                        @if($project->costs->count())
                            <a class="btn btn-sm btn-default-hollow" href="/funds/{{ $project->fund->id }}/fundraisers/create">Start a Fundraiser</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Funding Progress</label>
                    <div class="progress" style="margin-bottom:5px;">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $project->percent_raised }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 1%; width: {{ $project->percent_raised }}%;">
                        </div>
                    </div>
                    <span class="text-success">{{ $project->percent_raised }}% of ${{ number_format($project->goalInDollars(), 2) }}</span> <small>Raised</small>
                    <hr class="divider inv">
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Total In Fund</label>
                    <h2 class="text-success" style="margin-top:0;">${{ number_format($project->amountRaisedInDollars(), 2) }}</h2>
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Remaining To Raise</label>
                    <h2 class="text-info" style="margin-top:0;">${{ number_format($project->amountOutstandingInDollars(), 2) }}</h2>
                </div>
            </div><!-- end row -->
        </div>
    </div>

    <funding fund-id="{{ $project->fund->id }}"></funding>
@endsection