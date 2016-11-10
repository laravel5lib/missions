@extends('dashboard.reservations.show')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('tab')

    @foreach($reservation->requirements as $requirement)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>{{ $requirement->name }}
                        <small> Due: {{ carbon($requirement->due_at)->toFormattedDateString() }} <i class="fa fa-calendar"></i></small>
                        @if($requirement->pivot->status == 'complete')
                            <span class="badge {{ $requirement->pivot->status }} badge-success pull-right"><i class="fa fa-check"></i> Complete</span>
                        @elseif($requirement->pivot->status == 'reviewing')
                            <span class="badge {{ $requirement->pivot->status }} badge-info pull-right"><i class="fa fa-circle-o-notch fa-spin"></i> Reviewing</span>
                        @elseif($requirement->pivot->status == 'incomplete')
                            <span class="badge {{ $requirement->pivot->status }} badge-danger pull-right"><i class="fa fa-exclamation"></i> Incomplete</span>
                        @endif
                    </h5>
                </div>
                <div class="panel-body">
                        @if($requirement->document_type === 'medical_releases')
                            <reservations-medical-releases-manager
                                    reservation-id="{{ $reservation->id }}"
                                    medical-release-id="{{ $reservation->medical_release_id }}">
                            </reservations-medical-releases-manager>
                        @endif

                        @if($requirement->document_type === 'passports')
                            <reservations-passports-manager
                                    reservation-id="{{ $reservation->id }}"
                                    passport-id="{{ $reservation->passport_id }}">
                            </reservations-passports-manager>
                        @endif

                        @if($requirement->document_type === 'visas')
                            <reservations-visas-manager
                                    reservation-id="{{ $reservation->id }}"
                                    visa-id="{{ $reservation->passport_id }}">
                            </reservations-visas-manager>
                        @endif

                        @if($requirement->document_type === 'arrival_designation')
                            <reservations-arrival-designation
                                    reservation-id="{{ $reservation->id }}">
                            </reservations-arrival-designation>
                        @endif
                </div>
            </div>
    @endforeach
@endsection