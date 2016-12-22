@extends('dashboard.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Funding</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Funding Progress</label>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $project->percent_raised }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 30%; width: {{ $project->percent_raised }}%;">
                            {{ $project->percent_raised }}% of ${{ $project->goal }} Raised
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Total In Fund</label>
                    <h2 class="text-success" style="margin-top:0;">${{ number_format($project->amount_raised,2) }}</h2>
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Remaining To Raise</label>
                    <h2 class="text-info" style="margin-top:0;">${{ number_format($project->amount_outstanding,2) }}</h2>
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