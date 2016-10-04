@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>
                    <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->avatar->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">{{ $reservation->trip->campaign->name }}
                    <small>&middot; {{ country($reservation->trip->campaign->country_code) }}</small>
                </h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu')
            </div>
            <div class="col-sm-8">
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