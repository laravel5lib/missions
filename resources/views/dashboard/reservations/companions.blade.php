@extends('dashboard.reservations.show')

@section('tab')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6">
                <h5>
                    Companions {{ $reservation->companionReservations->count() }} of {{ $reservation->companion_limit }}
                </h5>
            </div>
            <div class="col-xs-6 text-right tour-step-request">
                <a class="btn btn-primary btn-xs" href="mailto:{{ $rep->email }}?subject=New Companion(s) Request&body=Please list the names, emails and your relationship to of those whom you'd like to be travel companions with. Please remember that you have a limited number of companions available. Thank you! Your trip rep will contact you soon.">
                    <span class="fa fa-group"></span> Request Companions
                </a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        @if( ! $reservation->companionReservations->count())
            <p class="text-center text-muted">No companions found.</p>
        @endif
        <div class="row">
            <div class="col-xs-12">
                @foreach($reservation->companionReservations as $companion)
                    <div class="col-xs-6 panel panel-default">
                        <h5>
                            <img src="{{ image($companion->avatar->source.'?w=50&h=50') }}" 
                                 class="img-circle av-left" 
                                 alt="{{ $companion->name }}">
                            {{ $companion->name }}
                            <small> &middot; <em>{{ ucwords($companion->pivot->relationship) }}</em></small>
                        </h5>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm text-muted">
                    <small><strong><u>DISCLAIMER:</u></strong> There is a limit to the number of travel companions allowed. While we do strive to keep you and your companions together, we <strong><u>cannot guarantee</u></strong> you will all be on the same team, room, or flight as there are many factors that can affect these decisions. Thanks for understanding.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'companions',
                title: 'Travel Companions',
                text: 'When you choose to travel with a companion, it means that you’ll be BFF’s throughout your missions experience. You’ll fly on the same planes, ride on the same bus, and be assigned to the same team. You’ll even be placed in the same hotel room (if you’re married or of the same gender).'
            },
            {
                id: 'request',
                title: 'Get Started',
                text: 'Start adding companions by sending a request to your trip rep.',
                attachTo: {
                    element: '.tour-step-request',
                    on: 'top'
                },
            }
        ];
    </script>
@endsection