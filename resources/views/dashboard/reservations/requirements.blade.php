@extends('dashboard.reservations.show')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('tab')

    <reservation-requirements id="{{ $reservation->id }}"></reservation-requirements>

    {{--@foreach($reservation->requirements as $requirement)--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xs-8">--}}
                            {{--<h5>{{ $requirement->requirement->name }}--}}
                                {{--@if($requirement->status == 'complete')--}}
                                    {{--<span class="label {{ $requirement->status }} label-success"><i class="fa fa-check"></i><span class="hidden-xs"> Complete</span></span>--}}
                                {{--@elseif($requirement->status == 'reviewing')--}}
                                    {{--<span class="label {{ $requirement->status }} label-info"><i class="fa fa-circle-o-notch fa-spin"></i><span class="hidden-xs"> Reviewing</span></span>--}}
                                {{--@elseif($requirement->status == 'incomplete')--}}
                                    {{--<span class="label {{ $requirement->status }} label-danger"><i class="fa fa-exclamation"></i><span class="hidden-xs"> Incomplete<span></span>--}}
                                {{--@endif--}}
                            {{--</h5>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-4 text-right">--}}
                            {{--<label style="margin:13px 0px;">Due {{ carbon($requirement->requirement->due_at)->toFormattedDateString() }}</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="panel-body">--}}
                        {{--@if($requirement->document_type === 'medical_releases')--}}
                            {{--<reservations-medical-releases-manager--}}
                                    {{--reservation-id="{{ $reservation->id }}"--}}
                                    {{--medical-release-id="{{ $reservation->medical_release_id }}">--}}
                            {{--</reservations-medical-releases-manager>--}}
                        {{--@endif--}}

                        {{--@if($requirement->document_type === 'passports')--}}
                            {{--<reservations-passports-manager--}}
                                    {{--reservation-id="{{ $reservation->id }}"--}}
                                    {{--passport-id="{{ $reservation->passport_id }}">--}}
                            {{--</reservations-passports-manager>--}}
                        {{--@endif--}}

                        {{--@if($requirement->document_type === 'visas')--}}
                            {{--<reservations-visas-manager--}}
                                    {{--reservation-id="{{ $reservation->id }}"--}}
                                    {{--visa-id="{{ $reservation->passport_id }}">--}}
                            {{--</reservations-visas-manager>--}}
                        {{--@endif--}}

                        {{--@if($requirement->document_type === 'essays')--}}
                            {{--<reservations-essays-manager--}}
                                    {{--reservation-id="{{ $reservation->id }}"--}}
                                    {{--essay-id="{{ $reservation->testimony_id }}"--}}
                                    {{--user-id="{{ $reservation->user_id }}">--}}
                            {{--</reservations-essays-manager>--}}
                        {{--@endif--}}

                        {{--@if($requirement->document_type === 'arrival_designation')--}}
                            {{--<reservations-arrival-designation--}}
                                    {{--reservation-id="{{ $reservation->id }}">--}}
                            {{--</reservations-arrival-designation>--}}
                        {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
    {{--@endforeach--}}
@endsection