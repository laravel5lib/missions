@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                @include('dashboard.reservations.layouts.menu')
            </div>
            <div class="col-sm-8">
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" style="width:100px; height:100px" src="{{ $reservation->trip->campaign->thumb_src }}" alt="{{ $reservation->trip->campaign->name }}">
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
                	<li class="list-group-item">
                        {{ $requirement->item }} {{ $requirement->item_type }} | Due: {{ carbon($requirement->due_at)->toFormattedDateString() }}
                        @if($requirement->pivot->status == 'complete')
                        <span class="badge {{ $requirement->pivot->status }} badge-success"><i class="fa fa-check"></i> Complete</span>
                        @elseif($requirement->pivot->status == 'reviewing')
                        <span class="badge {{ $requirement->pivot->status }} badge-info"><i class="fa fa-circle-o-notch fa-spin"></i> Reviewing</span>
                        @elseif($requirement->pivot->status == 'incomplete')
                        <span class="badge {{ $requirement->pivot->status }} badge-warning"><i class="fa fa-exclamation"></i> Incomplete</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection