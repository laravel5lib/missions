@extends('admin.reservations.show')

@section('tab')
    
    @foreach($reservation->fundraisers as $fundraiser)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6"><h5>Fundraiser</h5></div>
                @if($fundraiser->url && $fundraiser->public)
                <div class="col-xs-6"><a href="/{{ $fundraiser->sponsor->slug->url }}/{{ $fundraiser->url }}" class="btn btn-sm btn-primary pull-right" target="_blank">View Page</a></div>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Name</label>
                    <p>{{ $fundraiser->name }}</p>
                </div>
                <div class="col-md-2">
                    <label>Visibility</label>
                    <p><span class="label label-default">{{ $fundraiser->public ? 'Public' : 'Private' }}</span></p>
                </div>
                <div class="col-md-4">
                    <label>Expires</label>
                    <p>{{ $fundraiser->ended_at->format('F j, Y h:i a') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Funding</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Funding Progress</label>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $reservation->getPercentRaised() }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 1%; width: {{ $reservation->getPercentRaised() }}%;">
                        </div>
                    </div>
                    <span class="text-success">{{ $reservation->getPercentRaised() }}%</span> <small>of ${{ number_format($reservation->totalCostInDollars(), 2) }} Raised</small>
                    <hr class="divider inv">
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Total In Fund</label>
                    <h2 class="text-success" style="margin-top:0;">${{ number_format($reservation->totalRaisedInDollars(),2) }}</h2>
                </div>
                <div class="col-sm-6 col-md-4 text-center">
                    <label>Remaining To Raise</label>
                    <h2 class="text-info" style="margin-top:0;">${{ number_format($reservation->totalOwedInDollars(),2) }}</h2>
                </div>
            </div><!-- end row -->
            <div class="row">
                <div class="col-sm-12">
                    <funding fund-id="{{ $reservation->fund->id }}"></funding>
                </div>
            </div>
        </div><!-- end panel-body -->
    </div><!-- end panel -->
@endsection