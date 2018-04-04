@extends('dashboard.reservations.show')

@section('tab')
<div class="row">    
    <div class="col-xs-12 tour-step-fundraiser">
    
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-4">
                            <h5>Funding</h5>
                        </div>
                        <div class="col-xs-8 text-right">
                            @if($reservation->fund->fundraisers->count())
                                <a class="btn btn-sm btn-default-hollow" href="/dashboard/fundraisers/{{ $reservation->fund->fundraisers->first()->uuid }}/edit">Manage Fundraiser</a>
                            @else
                                <a class="btn btn-sm btn-default-hollow" href="/dashboard/funds/{{ $reservation->fund->id }}/fundraisers/create">Start a Fundraiser</a>
                            @endif
                            <a href="/donate/{{ $reservation->fund->slug }}" target="_blank" class="btn btn-sm btn-primary">
                                Donate to this Trip
                            </a>
                        </div>
                    </div>
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
                </div><!-- end panel-body -->
            </div><!-- end panel -->

            <funding fund-id="{{ $reservation->fund->id }}"></funding>

    </div>
</div>
@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'fundraiser',
                title: 'Fundraiser',
                text: 'A fundraising page is automatically created for each new reservation. You can manage this page by visiting it from your profile.',
                attachTo: {
                    element: '.tour-step-fundraiser',
                    on: 'top'
                },
            },
            {
                id: 'progress',
                title: 'Track Progress',
                text: 'Monitor your fundraising progress. See donors and donations.',
                attachTo: {
                    element: '.tour-step-progress',
                    on: 'top'
                },
            }
        ];
    </script>
@endsection