@extends('dashboard.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Funding</h5>
                </div>
                <div class="col-xs-6 text-right">
                    <a href="/donate/{{ $project->fund->slug }}" target="_blank" class="btn btn-sm btn-primary">
                        Make Donation
                    </a>
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
                    <span class="text-success">{{ $project->percent_raised }}% of ${{ number_format($project->goalInDollars(),2) }}</span> <small>Raised</small>
                    <hr class="divider inv">
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Total In Fund</label>
                    <h2 class="text-success" style="margin-top:0;">${{ number_format($project->amountRaisedInDollars(),2) }}</h2>
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Remaining To Raise</label>
                    <h2 class="text-info" style="margin-top:0;">${{ number_format($project->amountOutstandingInDollars(),2) }}</h2>
                </div>
            </div><!-- end row -->
            <div class="row">
                <div class="col-sm-12">
                    <funding fund-id="{{ $project->fund->id }}"></funding>
                </div>
            </div>
        </div>
    </div>
@endsection