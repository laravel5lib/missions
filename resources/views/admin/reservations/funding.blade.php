@extends('admin.reservations.show')

@section('tab')

    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="#funds" data-toggle="tab">Funds</a></li>
        <li role="presentation"><a href="#fundraiser" data-toggle="tab">Fundraiser</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="funds">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h5>Funding</h5>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="/donate/{{ $reservation->fund->slug }}" target="_blank" class="btn btn-sm btn-primary">
                                Make Donation
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
                    <div class="row">
                        <div class="col-sm-12">
                            <funding fund-id="{{ $reservation->fund->id }}"></funding>
                        </div>
                    </div>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div>
        <div role="tabpanel" class="tab-pane" id="fundraiser">
            <fundraiser-collection fund-id="{{ $reservation->fund->id }}"></fundraiser-collection>
        </div>
    </div>
@endsection