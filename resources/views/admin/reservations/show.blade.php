@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <a href="#">
                    <h3>
                        <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        {{ $reservation->trip->campaign->name }}
                        <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                    </h3>
                </a>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv">
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
                    <li role="presentation"><a href="#dues" aria-controls="dues" role="tab" data-toggle="tab">Dues</a></li>
                    {{--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>--}}
                </ul>
            </div><!-- end panel -->
        </div>
        <div class="col-sm-8">
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="details">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Details</h5>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-7">
                                <label>Reservation ID</label>
                                <p>{{ $reservation->id }}</p>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Surname</label>
                                        <p>{{ $reservation->surname }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Given Names</label>
                                        <p>{{ $reservation->given_names }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Gender</label>
                                        <p>{{ $reservation->gender }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Marital Status</label>
                                        <p>{{ $reservation->status }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Shirt Size</label>
                                        <p>{{ $reservation->shirt_size }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Age</label>
                                        <p>{{ $reservation->birthday->age }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Birthday</label>
                                        <p>{{ $reservation->birthday->format('F j, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Group</label>
                                        <p>{{ $reservation->trip->group->name }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Trip Type</label>
                                        <p>{{ $reservation->trip->type }} Missionary</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Start Date</label>
                                        <p>{{ $reservation->trip->started_at->toFormattedDateString() }}</p>
                                    </div>
                                </div>
                                <hr class="divider">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>End Date</label>
                                        <p>{{ $reservation->trip->ended_at->toFormattedDateString() }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Trip Starts In</label>
                                        <p>{{ $reservation->trip->started_at->diffInDays() }} days</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 panel panel-default panel-body text-center">
                                <label>Email</label>
                                <p>{{ $reservation->email }}</p>
                                <label>Home Phone</label>
                                <p>{{ $reservation->phone_one }}</p>
                                <label>Mobile Phone</label>
                                <p>{{ $reservation->phone_two }}</p>
                                <label>Address</label>
                                <p>{{ $reservation->address }}</p>
                                <label>City</label>
                                <p>{{ $reservation->city }}</p>
                                <label>State/Providence</label>
                                <p>{{ $reservation->state }}</p>
                                <label>Zip/Postal Code</label>
                                <p>{{ $reservation->zip }}</p>
                                <label>Country</label>
                                <p>{{ country($reservation->country_code) }}</p>
                            </div>
                        </div>
                    </div><!-- end panel -->
                </div><!-- end tab -->
                <div role="tabpanel" class="tab-pane" id="costs">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Costs Breakdown</h5>
                        </div>
                        <div class="panel-body">
                            <admin-reservation-costs id="{{ $reservation->id }}"></admin-reservation-costs>
                        </div><!-- end panel-body -->
                        {{-- {{ $reservation->costs }} --}}
                    </div><!-- end panel -->
                </div><!-- end tab -->
                    <div role="tabpanel" class="tab-pane" id="deadlines">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Deadlines</h5>
                            </div>
                            <div class="panel-body">
                                <admin-reservation-deadlines id="{{ $reservation->id }}"></admin-reservation-deadlines>
                            </div>
                        </div><!-- end panel -->
                    </div><!-- end tab -->
                    <div role="tabpanel" class="tab-pane" id="requirements">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Requirements</h5>
                            </div>
                            <div class="panel-body">
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
                    </div><!-- end panel -->
                </div>
                <div role="tabpanel" class="tab-pane" id="funding">
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
                </div><!-- end tab -->
                <div role="tabpanel" class="tab-pane" id="dues">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Dues</h5>
                        </div><!-- end panel-heading -->
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Outstanding Balance</th>
                                    <th>Grace Period</th>
                                    <th>Due</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($reservation->dues as $due)
                                    <tr>
                                        <td>
                                            @if ($due->getStatus() == 'late')
                                                <span class="badge badge-danger">Past Due</span>
                                            @elseif($due->getStatus() == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @else
                                                <span class="badge badge-info">Due in {{ $due->due_at->diffForHumans(null, true) }}</span>
                                            @endif
                                        </td>
                                        <td>${{ $due->outstanding_balance }}</td>
                                        <td>{{ $due->grace_period }}</td>
                                        <td>{{ $due->due_at->toFormattedDateString() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div><!-- end panel-body -->
                    </div><!-- end panel -->
                </div><!-- end tab -->

                </div>
            </div>
        </div>
    </div>
</div>
</div></div>
@endsection