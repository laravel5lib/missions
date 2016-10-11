@extends('dashboard.reservations.show')

@section('tab')
    <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Funding</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>
                            Funding Progress
                            {{--<span class="pull-right">${{ number_format($$reservation->getTotalOwed(),2) }}</span>--}}
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ $reservation->getPercentRaised() }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 30%; width: {{ $reservation->getPercentRaised() }}%;">
                                {{ $reservation->getPercentRaised() }}% of ${{ $reservation->getTotalCost() }} Raised
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <h4>Fund Transactions</h4>
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default panel-primary">
                            <div class="panel-heading">
                                <a <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><h5>{{ $reservation->fund->name }}</h5></a>
                                {{-- <span class="pull-right">Ends: {{ carbon($reservation->fund->created_at)->toFormattedDateString() }}</span>--}}
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                {{--<p>{{ $fundraiser->description or 'No Description'}}</p>--}}
                                <ul class="list-group">
                                    <li class="list-group-item" style="background:#05ce7b;">
                                        <h4 class="text-center text-white">${{ number_format($reservation->fund->balance, 2) }} <small class="text-white">Total In Fund</small></h4>
                                    </li>
                                    @foreach($reservation->fund->transactions as $transaction)
                                        <li class="list-group-item">
                                            <hr class="divider inv sm">
                                            <h4 class="list-group-item-heading text-success">
                                                ${{ $transaction->amount }} {{ $transaction->type }}</h4>
                                            <hr class="divider inv sm">
                                            <div class="well">
                                                <div class="list-group-item-text">
                                                    <p class="small"><b>Description:</b> {{ $transaction->description }}</p>
                                                </div>
                                                <hr class="divider">
                                                <div class="list-group-item-text">
                                                    <p class="small"><b>Comment:</b> {{ $transaction->comment }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                                {{--<donations-list fundraiser-id="{{ $fundraiser->id }}" donations="{{ json_encode($fundraiser->donations) }}"></donations-list>--}}

                            </div>
                            {{--<div class="panel-footer">
                                <a href="{{ url()->current() . '/' . $fundraiser->id . '/donations' }}" class="btn btn-sm btn-block btn-primary">View Donations</a>

                            </div>--}}
                        </div>
                    </div>
                </div>
            </div><!-- end panel-body -->
        </div><!-- end panel -->
@endsection