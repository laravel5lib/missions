@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs text-capitalize">
                    <img class="img-circle av-left img-sm" src="{{ image($trip->campaign->avatar->source . '?w=100') }}" alt="{{ $trip->campaign->name }}">
                    {{ $trip->campaign->name }} <small>&middot; Trip Details</small>
                </h3>
                <div class="visible-xs text-center">
                    <hr class="divider inv">
                    <img class="img-circle av-left img-sm" src="{{ image($trip->campaign->avatar->source . '?w=100') }}" alt="{{ $trip->campaign->name }}">
                    <h4 class="text-capitalize">{{ $trip->campaign->name }}</h4>
                    <label>Trip Details</label>
                </div>
            </div>
            <div class="col-sm-4 hidden-xs">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="/dashboard/groups/{{ $group->id }}" class="btn btn-primary pull-right">
                    Group Details
                </a>
            </div>
            <div class="col-sm-4 visible-xs text-center">
                <hr class="divider inv">
                <a href="/dashboard/groups/{{ $group->id }}" class="btn btn-primary">
                    Group Details
                </a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="panel panel-default">
                            <div class="nav nav-pills nav-stacked" role="tablist">
                                <li>
                                    <a href="#detailsA" role="tab" data-toggle="tab">Details</a>
                                </li>
                                <li>
                                    <a href="#registration" role="tab" data-toggle="tab">Registration</a>
                                </li>
                                <li>
                                    <a href="#pricing" role="tab" data-toggle="tab">Pricing</a>
                                </li>
                                <li>
                                    <a href="#requirements" role="tab" data-toggle="tab">Requirements</a>
                                </li>
                                <li>
                                    <a href="#deadlines" role="tab" data-toggle="tab">Deadlines</a>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="detailsA">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="text-capitalize">{{ $trip->campaign->name }}</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-8">
                                                <div class="row">
                                                    <div class="col-sm-6 text-center">
                                                        <label>Status</label>
                                                        <p>{{ ucfirst($trip->status) }}</p>
                                                    </div>
                                                    <div class="col-sm-6 text-center">
                                                        <label>Publish Date</label>
                                                        <p>{{ date('F d, Y', strtotime($trip->updated_at)) }}</p>
                                                    </div>
                                                </div>
                                                <hr class="divider">
                                                <div class="row">
                                                    <div class="col-sm-6 text-center">
                                                        <label>Group</label>
                                                        <p><a href="/admin/groups/{{ $trip->group->id }}">{{ $trip->group->name }}</a></p>
                                                    </div>
                                                    <div class="col-sm-6 text-center">
                                                        <label>Country</label>
                                                        <p>{{ country($trip->country_code) }}</p>
                                                    </div>
                                                </div>
                                                <hr class="divider">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <label>Start Date</label>
                                                        <p>{{ $trip->started_at->format('F d, Y') }}</p>
                                                    </div>
                                                    <div class="col-sm-4 text-center">
                                                        <label>End Date</label>
                                                        <p>{{ $trip->ended_at->format('F d, Y') }}</p>
                                                    </div>
                                                    <div class="col-sm-4 text-center">
                                                        <label>Updated Date</label>
                                                        <p>{{ $trip->updated_at->format('F d, Y') }}</p>
                                                    </div>
                                                </div>
                                                <hr class="divider">
                                                <label>Perfect For</label>
                                                <ul class="list-unstyled">
                                                    @foreach($trip->prospects as $prospect)
                                                        <li class="badge">{{ $prospect }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-sm-12 col-md-4 text-center">
                                                <div class="panel panel-default">
                                                    <div class="panel-body">
                                                        <label>Type</label>
                                                        <p class="text-capitalize">{{ $trip->type }}</p>
                                                        <hr class="divider">
                                                        <label>Trip Rep</label>
                                                        <p><a href="/admin/users/{{ $trip->rep_id }}">{{ $trip->rep->name or ''}}</a></p>
                                                        <hr class="divider">
                                                        <label>Tags</label>
                                                        <ul class="list-unstyled">
                                                            @forelse($trip->tags as $tag)
                                                                <li class="badge">{{ $tag }}</li>
                                                            @empty
                                                                None
                                                            @endforelse
                                                        </ul>
                                                        <hr class="divider">
                                                        <label>Difficulty</label>
                                                        <h4>{{ $trip->difficulty }}</h4>
                                                        <hr class="divider">
                                                        <label>Companion Limit</label>
                                                        <h4>{{ $trip->companion_limit }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <admin-trip-facilitators trip-id="{{ $trip->id }}"></admin-trip-facilitators>

                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="registration">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Registration</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                                                <label>Spots Available</label>
                                                <h4>{{ $trip->spots }}</h4>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                                                <label>Registration Closes</label>
                                                <h4>{{ date('F d, Y', strtotime($trip->ended_at)) }}</h4>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                                                <label>Starting Cost</label>
                                                <h4>${{ number_format($trip->starting_cost, 2, '.', ',') }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pricing">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Pricing</h5>
                                    </div>
                                    @foreach($trip->costs as $cost)
                                        <div class="panel-body">
                                            <h4>{{ $cost->name }}</h4>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class="small">{{ $cost->description }}</p>
                                                </div>
                                            </div>
                                            <hr class="divider">
                                            <div class="row">
                                                <div class="col-sm-4 text-center">
                                                    <label>Cost Type</label>
                                                    <p>{{ $cost->type }}</p>
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <label>Active Date</label>
                                                    <p>{{ date('F d, Y', strtotime($cost->active_at)) }}</p>
                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <label>Cost</label>
                                                    <p>${{ number_format($cost->amount, 2) }}</p>
                                                </div>
                                            </div>
                                            <hr class="divider">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Amount</th>
                                                    <th>Percent</th>
                                                    <th>Due</th>
                                                    <th>Grace</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($cost->payments as $payment)
                                                    <tr>
                                                        <td>${{ number_format($payment->amount_owed, 2) }}</td>
                                                        <td>{{ number_format($payment->percent_owed, 2) }}%</td>
                                                        <td>{{ $payment->upfront ? 'Upfront' : date('F d, Y', strtotime($payment->due_at)) }}</td>
                                                        <td>{{ $payment->upfront ? 'N/A' : $payment->grace_period }} {{ $payment->upfront ? '' : ($payment->grace_period > 1 ? 'days' : 'day') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr class="divider">
                                    @endforeach
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="requirements">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Requirements</h5>
                                    </div>
                                    <div class="panel-body">
                                        @foreach($trip->requirements as $requirement)
                                            <div class="row">
                                                <div class="col-xs-8">
                                                    <h5><a href="#">{{ $requirement->name }} {{ $requirement->item_type }}</a></h5>
                                                    <h6><small>Enforced: {{ $requirement->enforced ? 'Yes' : 'No' }}</small></h6>
                                                </div>
                                                <div class="col-xs-4 text-right">
                                                    <h5><i class="fa fa-calendar"></i> {{ date('F d, Y', strtotime($requirement->due_at)) }}</h5>
                                                    <h6><small>Grace Period: {{ $requirement->grace_period }} {{ $requirement->grace_period > 1 ? 'days' : 'day' }}</small></h6>
                                                </div>
                                            </div><!-- end row -->
                                            <hr class="divider">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="deadlines">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Other Deadlines</h5>
                                    </div>
                                    <div class="panel-body">
                                        @foreach($trip->deadlines as $deadline)
                                            <div class="row">
                                                <div class="col-xs-8">
                                                    <h5><a href="#">{{ $deadline->name }}</a></h5>
                                                    <h6><small>Enforced: {{ $deadline->enforced ? 'Yes' : 'No' }}</small></h6>
                                                </div>
                                                <div class="col-xs-4 text-right">
                                                    <h5><i class="fa fa-calendar"></i> {{ $deadline->grace_period }} {{ $deadline->grace_period > 1 ? 'days' : 'day' }}</h5>
                                                    <h6><small>Grace Period: {{ $deadline->grace_period }} {{ $deadline->grace_period > 1 ? 'days' : 'day' }}</small></h6>
                                                </div>
                                            </div><!-- end row -->
                                            <hr class="divider">
                                        @endforeach
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Description</h5>
                                    </div>
                                    <div class="panel-body">
                                        {% $trip->description %}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .panel dd {
            text-transform: capitalize;
        }
    </style>
@endsection