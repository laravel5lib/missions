@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>Reservations <small>Details</small></h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
                <div class="btn-group pull-right">
                    <a href="/admin/trips/create" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ $reservation->id }}/edit">Edit</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">


    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{ url('admin/reservations') }}" class="btn btn-block btn-default"><span class="fa fa-chevron-left icon-left"></span> Reservations</a>
                </div>
                <!-- Nav tabs -->
                <ul class="nav nav-pills nav-stacked" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
                    <li role="presentation"><a href="#costs" aria-controls="costs" role="tab" data-toggle="tab">Costs</a></li>
                    <li role="presentation"><a href="#deadlines" aria-controls="deadlines" role="tab" data-toggle="tab">Due Dates / Deadlines</a></li>
                    <li role="presentation"><a href="#requirements" aria-controls="requirements" role="tab" data-toggle="tab">Requirements</a></li>
                    <li role="presentation"><a href="#funding" aria-controls="funding" role="tab" data-toggle="tab">Funding</a></li>
                    {{--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>--}}
                    {{--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>--}}
                </ul>
            </div><!-- end panel -->
        </div>
        <div class="col-sm-8">
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        </a>
                        <div class="media-body">
                            <h3 class="media-heading">
                                {{ $reservation->trip->campaign->name }}
                                <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                            </h3>
                            <h4>
                                Details

                            </h4>
                        </div>
                    </div>
                    <hr class="divider">
                    <dl class="dl-horizontal">
                        <dt>Reservation ID</dt>
                        <dd>{{ $reservation->id }}</dd>
                        <dt>Surname</dt>
                        <dd>{{ $reservation->surname }}</dd>
                        <dt>Given Names</dt>
                        <dd>{{ $reservation->given_names }}</dd>
                        <dt>Gender</dt>
                        <dd>{{ $reservation->gender }}</dd>
                        <dt>Marital Status</dt>
                        <dd>{{ $reservation->status }}</dd>
                        <dt>Shirt Size</dt>
                        <dd>{{ $reservation->shirt_size }}</dd>
                        <dt>Age</dt>
                        <dd>{{ $reservation->birthday->age }}</dd>
                        <dt>Group</dt>
                        <dd>{{ $reservation->trip->group->name }}</dd>
                        <dt>Trip Type</dt>
                        <dd>{{ $reservation->trip->type }} Missionary</dd>
                        <dt>Start Date</dt>
                        <dd>{{ $reservation->trip->started_at->toFormattedDateString() }}</dd>
                        <dt>End Date</dt>
                        <dd>{{ $reservation->trip->ended_at->toFormattedDateString() }}</dd>
                        <dt>Trip Starts In</dt>
                        <dd>{{ $reservation->trip->started_at->diffInDays() }} days</dd>
                    </dl>
                </div>
                <div role="tabpanel" class="tab-pane" id="costs">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        </a>
                        <div class="media-body">
                            <h3 class="media-heading">
                                {{ $reservation->trip->campaign->name }}
                                <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                            </h3>
                            <h4>
                                Costs

                            </h4>
                        </div>
                    </div>
                    <hr class="divider">
                    {{--            {{ $reservation->costs }}--}}
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
                            {{--<tfoot style="border-top: 2px solid #000000">
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
                                <th>${{ $totalAmountDue - $totalAmountRaised > 0 ? number_format($totalAmountDue - $totalAmountRaised, 2) : number_format(0, 2) }}</th>
                            </tr>
                            </tfoot>--}}
                        </table>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="deadlines">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        </a>
                        <div class="media-body">
                            <h3 class="media-heading">
                                {{ $reservation->trip->campaign->name }}
                                <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                            </h3>
                            <h4>
                                Deadlines

                            </h4>
                        </div>
                    </div>
                    <hr class="divider">
                    <admin-reservation-deadlines id="{{ $reservation->id }}"></admin-reservation-deadlines>
                    {{--@foreach($reservation->deadlines as $deadline)
                    <ul class="list-group">
                    	<li class="list-group-item">
                            <h4 class="list-group-item-heading">
                                <i class="fa {{ now()->gt(carbon($deadline['due_at'])) ? 'fa-times text-danger' : 'fa-exclamation text-warning' }}"></i>&nbsp;
                            {{ !empty($deadline['name']) ? $deadline['name'] : (!empty($deadline['cost_name']) ? $deadline['cost_name'] : $deadline['item'] . ' Submission') }}
                                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{ carbon($deadline['due_at'])->toFormattedDateString() }}</small>
                            </h4>
                        </li>
                    </ul>
                    @endforeach--}}
                    {{--{{ $reservation->deadlines }}--}}
                </div>
                <div role="tabpanel" class="tab-pane" id="requirements">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        </a>
                        <div class="media-body">
                            <h3 class="media-heading">
                                {{ $reservation->trip->campaign->name }}
                                <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                            </h3>
                            <h4>
                                Requirements

                            </h4>
                        </div>
                    </div>
                    <hr class="divider">
                    <ul class="list-group">
                        @foreach($reservation->requirements as $requirement)

                            <h4>
                                {{ $requirement->item }} <small> Due: {{ carbon($requirement->due_at)->toFormattedDateString() }} <i class="fa fa-calendar"></i></small>
                                @if($requirement->pivot->status == 'complete')
                                    <span class="badge {{ $requirement->pivot->status }} badge-success pull-right"><i class="fa fa-check"></i> Complete</span>
                                @elseif($requirement->pivot->status == 'reviewing')
                                    <span class="badge {{ $requirement->pivot->status }} badge-info pull-right"><i class="fa fa-circle-o-notch fa-spin"></i> Reviewing</span>
                                @elseif($requirement->pivot->status == 'incomplete')
                                    <span class="badge {{ $requirement->pivot->status }} badge-danger pull-right"><i class="fa fa-exclamation"></i> Incomplete</span>
                                @endif
                            </h4>

                            @if($requirement->item === 'Passport')
                                <reservations-passports-manager
                                        reservation-id="{{ $reservation->id }}"
                                        passport-id="{{ $reservation->passport_id }}">
                                </reservations-passports-manager>
                            @endif

                            @if($requirement->item === 'Visa')
                                <reservations-visas-manager
                                        reservation-id="{{ $reservation->id }}"
                                        visa-id="{{ $reservation->passport_id }}">
                                </reservations-visas-manager>
                            @endif

                            <hr />
                        @endforeach
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane" id="funding">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        </a>
                        <div class="media-body">
                            <h3 class="media-heading">
                                {{ $reservation->trip->campaign->name }}
                                <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                            </h3>
                            <h4>
                                Funding
                            </h4>
                        </div>
                    </div>
                    <hr class="divider">
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
                            <h4>Fund Transaction</h4>
                            <hr>
                        </div>
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        {{ $reservation->fund->name }}
{{--                                        <span class="pull-right">Ends: {{ carbon($reservation->fund->created_at)->toFormattedDateString() }}</span>--}}
                                    </div>
                                    <div class="panel-body">
                                        {{--<p>{{ $fundraiser->description or 'No Description'}}</p>--}}
                                        <h6>
                                            Amount in fund
                                            <span class="pull-right">Total: ${{ number_format($reservation->fund->balance, 2) }}</span>
                                        </h6>

                                        <ul class="list-group">
                                        @foreach($reservation->fund->transactions as $transaction)
                                        	<li class="list-group-item">
                                                <h4 class="list-group-item-heading">${{ $transaction->amount }} {{ $transaction->type }}</h4>
                                                <div class="list-group-item-text">
                                                    {{ $transaction->comment }}
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
                </div>
                </div>
                {{--<div role="tabpanel" class="tab-pane" id="settings">

                </div>
                <div role="tabpanel" class="tab-pane" id="settings">

                </div>--}}
            </div>

        </div>
    </div>
</div>
@endsection