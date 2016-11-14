@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="text-capitalize">
                    <a href="#">
                        <img class="img-circle av-left img-sm" src="{{ image($trip->campaign->avatar->source . '?w=100') }}" alt="{{ $trip->campaign->name }}">
                    </a>
                    {{ $trip->campaign->name }} <small>&middot; Trip Details</small>
                </h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
                <hr class="divider inv">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{ Request::url() }}/edit">Edit</a></li>
                        <li><a data-toggle="modal" data-target="#addReservationModal" data-backdrop="static">Create Reservation</a></li>
                        <li><a data-toggle="modal" data-target="#duplicationModal">Duplicate</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a data-toggle="modal" data-target="#deleteConfirmationModal">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
                    <li role="presentation"><a href="#reservations" aria-controls="reservations" role="tab" data-toggle="tab">Reservations</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="panel panel-default">
                            <div class="list-group">
                                <a href="#details" class="list-group-item">Details</a>
                                <a href="#registration" class="list-group-item">Registration</a>
                                <a href="#pricing" class="list-group-item">Pricing</a>
                                <a href="#requirements" class="list-group-item">Requirements</a>
                                <a href="#deadlines" class="list-group-item">Deadlines</a>
                                <a href="#todos" class="list-group-item">Todos</a>
                                <a href="#notes" class="list-group-item">Notes</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9">
                        <div id="details"></div>
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
                                </dl>
                            </div>
                        </div>
                        <div id="registration"></div>
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

                        <admin-trip-facilitators trip-id="{{ $trip->id }}"></admin-trip-facilitators>
                        <div id="pricing"></div>
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
                        <div id="requirements"></div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Requirements</h5>
                            </div>
                            <div class="panel-body">
                                @foreach($trip->requirements as $requirement)
                                <div class="row">
                                  <div class="col-xs-8">
                                    <h5><a href="#">{{ $requirement->name }} {{ $requirement->document_type }}</a></h5>
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
                        <div id="deadlines"></div>
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
                        <div id="todos"></div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Todos</h5>
                            </div>
                            <div class="list-group">
                                @foreach($trip->todos as $todo)
                                <li class="list-group-item">{{$todo}}</li>
                                @endforeach
                            </div>
                        </div>
                        <div id="notes"></div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Description</h5>
                            </div>
                            <div class="panel-body">
                                {% $trip->description %}
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Notes</h5>
                            </div>
                            <div class="list-group">
                                @foreach($trip->notes as $note)
                                <div class="list-group-item">
                                    <div class="list-group-item-heading"><b>{{$note->subject}}</b> by <b>{{ $note->user->name }}</b> on <b>{{ $note->updated_at->format('F j, Y h:i:s a') }}</b></div>
                                    <div class="list-group-item-text">{{ $note->content }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="reservations">
                    <div class="col-xs-12">
                        <admin-reservations-list trip-id="{{ $trip->id }}"></admin-reservations-list>
                        {{--<admin-trip-reservations trip-id="{{ $trip->id }}"></admin-trip-reservations>--}}
                    </div>
                </div>
            </div>
        </div>

        <admin-trip-duplicate trip-id="{{ $trip->id }}"></admin-trip-duplicate>
        <admin-trip-delete trip-id="{{ $trip->id }}"></admin-trip-delete>
        <div class="modal fade" id="addReservationModal" tabindex="-1" role="dialog" aria-labelledby="addReservationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create Reservation</h4>
                    </div>
                    <div class="modal-body">
                        <admin-reservation-create trip-id="{{ $trip->id }}"></admin-reservation-create>
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