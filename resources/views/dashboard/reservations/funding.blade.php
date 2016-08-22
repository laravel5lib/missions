@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu')
            </div>
            <div class="col-sm-8">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" style="width:100px; height:100px" src="{{ $reservation->trip->campaign->thumb_src }}" alt="{{ $reservation->trip->campaign->name }}">
                    </a>
                    <div class="media-body">
                        <h3 class="media-heading">
                            {{ $reservation->trip->campaign->name }}
                            <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                        </h3>
                        <h4>Funding</h4>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <h4>
                            Funding Progress
                            {{--<span class="pull-right">${{ number_format($totalAmountDue,2) }}</span>--}}
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{--{{ ($totalAmountRaised/$totalAmountDue) * 100 }}--}}" aria-valuemin="0" aria-valuemax="100" style="min-width: 30%; width: {{--{{ ($totalAmountRaised/$totalAmountDue) * 100 }}--}}%;">
                                {{--{{ number_format(($totalAmountRaised/$totalAmountDue) * 100, 2) }}--}}% of ${{ number_format($totalAmountDue,2) }} Raised
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <table class="table table-striped table-hover">
                                <caption>Breakdown</caption>
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reservation->costs as $cost)
                                    <tr style="border-top: 2px solid #cccccc">
                                        <td>{{ $cost->name }}</td>
                                        <td></td>
                                        <td><b>${{ number_format($cost->amount, 2) }}</b></td>
                                    </tr>
                                    @foreach($cost->payments as $payment)
                                        <tr class="@if($cost->type === 'incremental' && $payment->upfront){{'success'}}@endif">
                                            <td>
                                                @if($cost->type === 'incremental' && $payment->upfront)
                                                    <small class="badge badge-success">Paid</small>
                                                @endif
                                                @if($cost->type === 'incremental' && !$payment->upfront && $payment->due_next)
                                                    <small class="badge badge-danger">Next Defaulting Amount</small>
                                                @endif
                                                @if($cost->type === 'incremental' && $payment->due_at->between(now()->startOfMonth(), now()->endOfMonth()))
                                                    <small class="badge badge-info">Due this month</small>
                                                @endif
                                                @if($cost->type === 'incremental' && $payment->due_at->between(now()->addMonth()->startOfMonth(),now()->addMonth()->endOfMonth()))
                                                    <small class="badge badge-warning">Due next month</small>
                                                @endif
                                            </td>
                                            <td>{{ carbon($payment->due_at)->toFormattedDateString() }}</td>
                                            <td>${{ number_format($payment->amount_owed) }}</td>
                                        </tr>
                                        {{--<li class="list-group-item">{{ $payment->amount_owed }}</li>--}}
                                    @endforeach
                                @endforeach

                                </tbody>
                                <tfoot style="border-top: 2px solid #000000">
                                <tr>
                                    <th>Total Amount Due</th>
                                    <th></th>
                                    <th>${{ number_format($totalAmountDue, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Amount Raised</th>
                                    <th></th>
                                    <th>${{ number_format($totalAmountRaised, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Amount Remaining</th>
                                    <th></th>
                                    <th>${{ number_format($totalAmountDue - $totalAmountRaised, 2) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{--<div class="col-sm-12">
                        <h4>Costs</h4>
                        <hr>
                    </div>
                    @foreach($reservation->costs as $cost)
                    <div class="col-sm-12 col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ $cost->name }}
                                <span class="pull-right">Ends: {{ carbon($cost->expires_at)->toFormattedDateString() }}</span>
                            </div>
                            <div class="panel-body">
                                --}}{{--{{ $cost }}--}}{{--
                                {{ $cost->description or 'No Description'}}
                                <ul class="list-group">
                                    @foreach($cost->payments as $payment)
                                        <li class="list-group-item">{{ $payment->amount_owed }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="panel-footer">

                            </div>
                        </div>
                    </div>
                    @endforeach--}}


                    <div class="col-sm-12">
                        <h4>Fundraisers</h4>
                        <hr>
                    </div>
                    @foreach($reservation->fundraisers as $fundraiser)
                        <div class="col-sm-12 col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ $fundraiser->name }}
                                    <span class="pull-right">Ends: {{ carbon($fundraiser->expires_at)->toFormattedDateString() }}</span>
                                </div>
                                <div class="panel-body">
                                    <p>{{ $fundraiser->description or 'No Description'}}</p>
                                    <h6>
                                        Amount Raised
                                        <span class="pull-right">Goal: ${{ number_format($fundraiser->goal_amount, 2) }}</span>
                                    </h6>
                                    <div class="progress">
                                        <?php $fundraiser->raised_percent = (($fundraiser->raised() /100) > $fundraiser->goal_amount) ? 100 : $fundraiser->raised() /100 ?>
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ $fundraiser->raised_percent }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: {{ $fundraiser->raised_percent }}%;">
                                            ${{ number_format($fundraiser->raised() / 100, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <a href="{{ url()->current() . '/' . $fundraiser->id . '/donations' }}" class="btn btn-sm btn-block btn-primary">View Donations</a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection