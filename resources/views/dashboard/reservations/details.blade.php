@extends('dashboard.reservations.show')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/css/Jcrop.min.css" type="text/css">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/js/Jcrop.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.14.2/TweenMax.min.js"></script>
@endsection

@section('tab')
    @if( $reservation->assignmentsArePublished() )
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Trip Assignments</h5>
        </div>
        <div class="panel-body">
            @if( $reservation->squadsArePublished() )
            <div class="col-md-6">
                <label>Squad(s)</label>
                <p>
                    @forelse($reservation->squads as $squad)
                        <strong>{{ ucwords($squad->team->callsign) }}</strong> &middot; {{ $squad->callsign }}
                    @empty
                        Unassigned
                    @endforelse
                </p>
                <hr class="divider">
            </div>
            @endif
            @if( $reservation->regionsArePublished() )
            <div class="col-md-6">
                <label>Region(s)</label>
                <p>
                    @forelse($reservation->squads as $squad)
                        {{
                            $squad->team && $squad->team->regions ?
                            implode(',', $squad->team->regions->pluck('name')->all()) :
                            'Unassigned'
                        }}
                    @empty
                        Unassigned
                    @endforelse
                </p>
                <hr class="divider">
            </div>
            @endif
            @if( $reservation->roomsArePublished() )
            <div class="col-md-6">
                <label>Accommodations</label>
                <p>
                    @forelse($reservation->rooms as $room)
                        <strong>{{ ucwords($room->type->name) }}</strong>
                        &middot; {{  implode(',', $room->accommodations->pluck('name')->all()) }}
                    @empty
                        Unassigned
                    @endforelse
                </p>
                <hr class="divider">
            </div>
            @endif
        </div>
    </div>
    @endif

    @if( $reservation->transportsArePublished() )
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Travel</h5>
            </div>
            @include('partials.reservations.transportation', ['reservation' => $reservation])
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Details</h5>
        </div>
        <div class="panel-body">
            <div class="col-xs-12">
                <reservation-avatar id="{{ $reservation->id }}"></reservation-avatar>
            </div>
            <div class="col-xs-12 col-lg-7">
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
                        <p>{{ ucwords($reservation->gender) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Marital Status</label>
                        <p>{{ ucwords($reservation->status) }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Shirt Size</label>
                        <p>{{ shirtSize($reservation->shirt_size) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Age</label>
                        <p>{{ $reservation->birthday->age }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Height</label>
                        <p>{{ convert_to_inches($reservation->height)['ft'] }} ft {{ convert_to_inches($reservation->height)['in'] }} in <small class="text-muted">({{ $reservation->height }} cm)</small></p>
                    </div>
                    <div class="col-md-6">
                        <label>Weight</label>
                        <p>{{ convert_to_pounds($reservation->weight) }} lbs <small class="text-muted">({{ $reservation->weight }} kg)</small></p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Birthday</label>
                        <p>{{ $reservation->birthday->format('M j, Y') }}</p>
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
                        <p class="text-capitalize">{{ $reservation->trip->type }}</p>
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
            <div class="col-xs-12 col-lg-5 panel panel-default panel-body text-center">
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
@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'rep',
                title: 'Trip Rep',
                text: 'A Mission.Me trip representative is assigned to each reservation. Find your rep\'s contact information here if you need help.',
                attachTo: {
                    element: '.tour-step-rep',
                    on: 'top'
                },
            },
            {
                id: 'navigation',
                title: 'Additional Details',
                text: 'More details about your reservation can be found here. Details are seperated by category.',
                attachTo: {
                    element: '.tour-step-rep',
                    on: 'top'
                },
            },
            {
                id: 'avatar',
                title: 'Attach a Picture',
                text: 'Put a face to a name by uploading a picture of yourself.',
                attachTo: {
                    element: '.tour-step-avatar',
                    on: 'top'
                },
            },
        ];
    </script>
@endsection