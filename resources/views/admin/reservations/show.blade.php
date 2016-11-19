@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <a href="#">
                    <h3>
                        <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        {{ $reservation->trip->campaign->name }}
                        <small>&middot; {{ country($reservation->trip->campaign->country_code) }}</small>
                    </h3>
                </a>
            </div>
            <div class="col-xs-4 text-right">
                <hr class="divider inv">
                <div class="btn-group" role="group">
                    <a href="{{ url('admin/reservations') }}" class="btn btn-primary-darker"><span class="fa fa-chevron-left icon-left"></span></a>
                    <a class="btn btn-primary" href="{{ $reservation->id }}/edit">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div style="display:inline-block;">
                        <img class="img-circle img-sm" src="{{ image($rep->avatar->source.'?w=50&h=50') }}">
                    </div>
                    <div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
                        <label style="margin-bottom:0px;font-size:10px;">Your Trip Rep</label>
                        <h5 style="margin:3px 0 6px;">{{ $rep->name }}</h5>
                        <p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $rep->phone_one }}">{{ $rep->phone_one }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $rep->email }}">{{ $rep->email }}</a></p>
                    </div>
                </div><!-- end panel-heading -->
                <!-- Nav tabs -->
                <ul class="nav nav-pills nav-stacked" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
                    <li role="presentation"><a href="#costs" aria-controls="costs" role="tab" data-toggle="tab">Costs</a></li>
                    <li role="presentation"><a href="#deadlines" aria-controls="deadlines" role="tab" data-toggle="tab">Other Deadlines</a></li>
                    <li role="presentation"><a href="#requirements" aria-controls="requirements" role="tab" data-toggle="tab">Requirements</a></li>
                    <li role="presentation"><a href="#funding" aria-controls="funding" role="tab" data-toggle="tab">Funding</a></li>
                    <li role="presentation"><a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a></li>
                    <li role="presentation"><a href="#todos" aria-controls="notes" role="tab" data-toggle="tab">Todos</a></li>
                </ul>
            </div><!-- end panel -->
        </div>
        <div class="col-xs-12 col-sm-8">
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
                            <h5>Payments Due</h5>
                        </div><!-- end panel-heading -->
                        <div class="panel-body">
                            <admin-reservation-dues id="{{ $reservation->id }}"></admin-reservation-dues>
                        </div><!-- end panel-body -->
                    </div><!-- end panel -->

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Applied Costs</h5>
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
                                <h5>Other Deadlines</h5>
                            </div>
                            <div class="panel-body">
                                <admin-reservation-deadlines id="{{ $reservation->id }}"></admin-reservation-deadlines>
                            </div>
                        </div><!-- end panel -->
                    </div><!-- end tab -->
                    <div role="tabpanel" class="tab-pane" id="requirements">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Travel Requirements</h5>
                            </div>
                            <div class="panel-body">
                                <ul class="list-group">
                                    @foreach($reservation->requirements as $requirement)

                                    <h4>
                                        {{ $requirement->name }} <small> Due: {{ carbon($requirement->due_at)->toFormattedDateString() }} <i class="fa fa-calendar"></i></small>
                                        @if($requirement->pivot->status == 'complete')
                                            <span class="badge {{ $requirement->pivot->status }} badge-success pull-right"><i class="fa fa-check"></i> Complete</span>
                                        @elseif($requirement->pivot->status == 'reviewing')
                                            <span class="badge {{ $requirement->pivot->status }} badge-info pull-right"><i class="fa fa-circle-o-notch fa-spin"></i> Reviewing</span>
                                        @elseif($requirement->pivot->status == 'incomplete')
                                            <span class="badge {{ $requirement->pivot->status }} badge-danger pull-right"><i class="fa fa-exclamation"></i> Incomplete</span>
                                        @endif
                                    </h4>

                                    @if($requirement->name === 'Medical Release')
                                        <reservations-medical-releases-manager
                                                reservation-id="{{ $reservation->id }}"
                                                medical-release-id="{{ $reservation->medical_release_id }}">
                                        </reservations-medical-releases-manager>
                                    @endif

                                    @if($requirement->name === 'Passport')
                                        <reservations-passports-manager
                                                reservation-id="{{ $reservation->id }}"
                                                passport-id="{{ $reservation->passport_id }}">
                                        </reservations-passports-manager>
                                    @endif

                                    @if($requirement->name === 'Visa')
                                        <reservations-visas-manager
                                                reservation-id="{{ $reservation->id }}"
                                                visa-id="{{ $reservation->visa_id }}">
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
                            <h5>{{ $reservation->fund->name }}</h5>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-xs-4">
                                    <label>Raised</label>
                                    <h4 class="text-success">
                                        ${{ $reservation->fund->balance }}
                                    </h4>
                                </div>
                                <div class="col-xs-8">
                                    <label>Progress</label>
                                    <h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ $reservation->getPercentRaised() }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 30%; width: {{ $reservation->getPercentRaised() }}%;">
                                            {{ $reservation->getPercentRaised() }}% of ${{ $reservation->getTotalCost() }} Raised
                                        </div>
                                    </div>
                                    </h4>
                                </div>
                            </div>

                        </div>
                    </div><!-- end panel -->

                    <hr class="divider">
                        <h4 class="text-center text-muted">Transactions</h4>
                    <hr class="divider">
                    <admin-transactions-list fund="{{ $reservation->fund->id }}"
                                             storage-name="AdminFundTransactionsConfig">
                    </admin-transactions-list>

                </div><!-- end tab -->


                <div role="tabpanel" class="tab-pane" id="notes">
                    <notes type="reservations"
                           id="{{ $reservation->id }}"
                           user_id="{{ auth()->user()->id }}"
                           :per_page="3"
                           :can-modify="{{ auth()->user()->can('modify-notes') }}">
                    </notes>
                </div>

                <div role="tabpanel" class="tab-pane" id="todos">
                    <todos type="reservations"
                           id="{{ $reservation->id }}"
                           user_id="{{ auth()->user()->id }}"
                           :can-modify="{{ auth()->user()->can('modify-todos') }}">
                    </todos>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div></div>
@endsection