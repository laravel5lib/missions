@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu')
            </div>
            <div class="col-sm-8">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source) }}" alt="{{ $reservation->trip->campaign->name }}">
                    </a>
                    <div class="media-body">
                        <h3 class="media-heading">
                            {{ $reservation->trip->campaign->name }}
                            <small>{{ country($reservation->trip->campaign->country_code) }}</small>
                        </h3>
                        <h4>Travel Documents</h4>

                    </div>
                </div>
                <br>
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
        </div>
    </div>
@endsection