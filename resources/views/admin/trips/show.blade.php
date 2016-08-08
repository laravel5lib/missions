@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="text-capitalize">
                    <a href="#">
                        <img class="av-left img-sm" src="{{ $trip->campaign->thumb_src }}" alt="{{ $trip->campaign->name }}">
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
            <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="details">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Details</h5>
                            </div>
                            <div class="panel-body">
                                <dl class="dl-horizontal">
                                    {{--<dt>Thumbnail</dt>--}}
                                    {{--<dd><a href="#">{{ $trip->campaign->thumb_src }}</a></dd>--}}

                                    <dt>Description</dt>
                                    <dd>{{ $trip->description }}</dd>

                                    <dt>Campaign</dt>
                                    <dd><a href="#">{{ $trip->campaign->name }}</a></dd>

                                    <dt>Type</dt>
                                    <dd>{{ $trip->type }} Missionary</dd>

                                    <dt>Group</dt>
                                    <dd><a href="#">{{ $trip->group->name }}</a></dd>

                                    <dt>Trip Rep</dt>
                                    <dd><a href="#">{{ $trip->rep_id }}</a></dd>

                                    <dt>Perfect For</dt>
                                    <dd>
                                        <ul class="list-inline">
                                            @foreach($trip->prospects as $prospect)
                                                <li class="badge">{{ $prospect }}</li>
                                            @endforeach
                                        </ul>
                                    </dd>

                                    {{--<dt>Requirements</dt>
                                    <dd>
                                        <ul class="list-inline">
                                            @foreach($trip->requirements as $requirement)
                                                <li class="badge">{{ $requirement->item_type }} {{ $requirement->item }}</li>
                                            @endforeach
                                        </ul>
                                    </dd>--}}

                                    <dt>Public Page</dt>
                                    <dd>{{ $trip->page_url }}</dd>

                                    <dt>Difficulty</dt>
                                    <dd>{{ $trip->difficulty }}</dd>

                                    <dt>Companion Limit</dt>
                                    <dd>{{ $trip->companion_limit }}</dd>

                                    <dt>Country</dt>
                                    <dd>{{ $trip->country_code }}</dd>

                                    <dt>Start Date</dt>
                                    <dd>{{ date('F d, Y', strtotime($trip->started_at)) }}</dd>

                                    <dt>End Date</dt>
                                    <dd>{{ date('F d, Y', strtotime($trip->ended_at)) }}</dd>

                                    <dt>Updated Date</dt>
                                    <dd>{{ date('F d, Y', strtotime($trip->updated_at)) }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Registration</h5>
                            </div>
                            <div class="panel-body">
                                <dl class="dl-horizontal">
                                    <dt>Spots Available</dt>
                                    <dd>{{ $trip->spots }}</dd>

                                    <dt>Registration Closes</dt>
                                    <dd>{{ date('F d, Y', strtotime($trip->ended_at)) }}</dd>

                                    <dt>Publish Date</dt>
                                    <dd>{{ date('F d, Y', strtotime($trip->updated_at)) }}</dd>

                                </dl>
                            </div>
                        </div>

                        <admin-trip-facilitators trip-id="{{ $trip->id }}"></admin-trip-facilitators>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Pricing</h5>
                            </div>
                            @foreach($trip->costs as $cost)
                                <div class="list-group">
                                    <div href="#" class="list-group-item">
                                        <h4 class="list-group-item-heading">{{ $cost->name }}</h4>
                                        <div class="list-group-item-text row">
                                            <div class="col-sm-6">
                                                {{ $cost->description }}
                                            </div>
                                            <div class="col-sm-6">
                                                <ul class="list-unstyled">
                                                    <li style="text-transform: capitalize">{{ $cost->type }}</li>
                                                    <li>{{ date('F d, Y', strtotime($cost->active_at)) }}</li>
                                                    <li>${{ number_format($cost->amount, 2) }}</li>
                                                </ul>
                                            </div>
                                        </div>
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
                                </div>
                            @endforeach
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Requirements</h5>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Due</th>
                                    <th>Grace</th>
                                    <th>Enforced</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trip->requirements as $requirement)
                                    <tr>
                                        <td style="text-transform: capitalize">{{ $requirement->item }} {{ $requirement->item_type }}</td>
                                        <td>{{ date('F d, Y', strtotime($requirement->due_at)) }}</td>
                                        <td>{{ $requirement->grace_period }} {{ $requirement->grace_period > 1 ? 'days' : 'day' }}</td>
                                        <td>{{ $requirement->enforced ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Deadlines</h5>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Due</th>
                                    <th>Grace</th>
                                    <th>Enforced</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($trip->deadlines as $deadline)
                                <tr>
                                    <td style="text-transform: capitalize">{{ $deadline->name }}</td>
                                    <td>{{ date('F d, Y', strtotime($deadline->due_at)) }}</td>
                                    <td>{{ $deadline->grace_period }} {{ $deadline->grace_period > 1 ? 'days' : 'day' }}</td>
                                    <td>{{ $deadline->enforced ? 'Yes' : 'No' }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Notes</h5>
                            </div>
                            <div class="list-group">
                                @foreach($trip->notes as $note)
                                <div class="list-group-item">
                                    <div class="list-group-item-heading"><b>{{$note->subject}}</b> by <b>{{ $note->user_id }}</b></div>
                                    <div class="list-group-item-text">{{ $note->content }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="reservations">
                        <admin-trip-reservations trip-id="{{ $trip->id }}"></admin-trip-reservations>
                    </div>
                </div>

            </div>
        </div>

        <admin-trip-duplicate trip-id="{{ $trip->id }}"></admin-trip-duplicate>
        <admin-trip-delete trip-id="{{ $trip->id }}"></admin-trip-delete>

    </div>
    <style>
        .panel dd {
            text-transform: capitalize;
        }
    </style>
@endsection