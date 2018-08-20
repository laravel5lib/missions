@extends('site.layouts.default')
@inject('markdown', 'League\CommonMark\Converter')

@push('scripts')
<script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endpush

@section('content')
<div class="dark-bg-primary">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <hr class="divider xlg inv">
                <h1 style="margin-bottom: 0;">{{ ucwords($trip->type) }} Trip</h1>
                <h4 style="line-height: 2em;">
                    @foreach($trip->tags as $tag)
                    <span class="label" style="background-color: white; color: #f52330;">{{ ucwords($tag->name) }}</span>
                    @endforeach
                </h4>
                <hr class="divider xlg inv">
            </div><!-- end col -->
            @if($trip->campaign->slug)
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv">
                <hr class="divider inv sm">
                <a href="{{ url($trip->campaign->slug->url.'/teams/'.$trip->group_id.'/trips') }}" class="btn btn-default-hollow" style="color: white; border-color: white;">
                    <i class="fa fa-chevron-left icon-left"></i> More Options
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="darker-bg-primary" style="color: white;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <label style="color: rgb(246, 103, 112); margin-top: 2em;">Dates</label>
                <h3 style="margin-top: 0;">{{ $trip->started_at->format('F j') }} - {{ $trip->ended_at->format('F j, Y') }}</h3>
            </div>
            <div class="col-md-4">
                <label style="color: rgb(246, 103, 112); margin-top: 2em;">Starting Cost</label>
                <h3 style="margin-top: 0;">${{  number_format($trip->starting_cost, 2, '.', ',') }}</h3>
            </div>
            <div class="col-md-4">
                @if($trip->status <> 'active')
                    <button class="btn btn-primary btn-lg btn-block" disabled style="background-color: white; color: #f52330 !important; border-color: white; margin-top: 1em; margin-bottom: 1em;">Registration Closed</button>
                @elseIf(!$trip->public)
                    <button class="btn btn-primary btn-lg btn-block" style="background-color: white; color: #f52330 !important; border-color: white; margin-top: 1em; margin-bottom: 1em;" data-toggle="tooltip" data-placement="top" title="Please contact your team coordinator to learn how to register for this trip.">Private Registration</button>
                    <h5 class="text-center">
                    </h5>
                @else
                    <a href="/trips/{{ $trip->id }}/register" class="btn btn-primary btn-lg btn-block" style="background-color: white; color: #f52330 !important; border-color: white; margin-top: 1em; margin-bottom: 1em;" data-toggle="tooltip" data-placement="top" title="There are {{ $trip->spots }} spots left!">
                        @if(auth()->check())
                            Register Now
                        @else
                            Sign in and Register
                        @endif
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="container">
    <hr class="divider inv lg">
    <div class="row">
        <div class="visible-xs">
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-center">
                <a class="lead text-primary" role="button" data-toggle="collapse" href="#collapseDetails" aria-expanded="false" aria-controls="collapseDetails">Read Details...</a>
                <hr class="divider inv lg">
            </div>
            <div id="collapseDetails" class="collapse">
                <div class="col-xs-12">
                    {!! $markdown->convertToHtml($trip->description) !!}
                    <hr class="divider inv" />
                </div><!-- end col -->
            </div><!-- end collapse -->
        </div><!-- end visible-xs -->
        <div class="col-sm-8 col-md-8 col-lg-8 hidden-xs">

            {!! $markdown->convertToHtml($trip->description) !!}

            <hr class="divider inv" />

        </div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="panel panel-default">
            	<div class="panel-body">
                    <img src="{{ image($trip->campaign->getAvatar()->source) }}" class="img-responsive">
                    <p class="text-muted text-center"><em>{{ $trip->campaign->name }}</em></p>
                    <hr class="divider lg">
                    <h6 class="text-uppercase text-center">
                        <i class="fa fa-map-marker"></i> {{ country($trip->country_code) }}
                    </h6>
                    <hr class="divider lg">
                    <div class="text-center">
                        <img src="{{ image($trip->group->getAvatar()->source)}}" class="img-circle img-xs">
                        <h5>{{ $trip->group->name }}</h5>
                    </div>
                    <hr class="divider lg">
                    @unless($trip->reservations->count() < 20 || !$trip->public)
                        <hr class="divider lg">
                        <h5 class="text-center">
                            <small>{{ $trip->reservations->count() }} people have registered for this trip.</small>
                        </h5>
                    @endunless
                    <h6 class="text-uppercase text-center">
                        <img class="img-xs img-circle av-left"
                             src="../images/why-mm/{{ strtolower(str_replace('_', '', $trip->difficulty)) }}.png"
                             alt="{{ $trip->difficulty }}"
                        >
                        Difficulty
                    </h6>
                    <hr class="divider lg">
                    <h6 class="text-uppercase text-center">
                        This trip is ideal for:
                    </h6>
                    <p>
                    @foreach($trip->prospects as $prospect)
                        <span class="label label-filter">{{ $prospect }}</span>
                    @endforeach
                    </p>
            	</div>
            </div>
            @if($trip->requirements->count())
                @include('site.trips.partials._requirements', ['trip' => $trip])
            @endif

            @if($trip->campaign->slug)
                <div class="col-xs-12 text-center visible-xs">
                    <a href="{{ url($trip->campaign->slug->url.'/teams/'.$trip->group_id.'/trips') }}" class="btn btn-default-hollow">
                        <i class="fa fa-chevron-left icon-left"></i> More Options
                    </a>
                    <hr class="divider inv">
                </div>
            @endif
        </div>
    </div>
    <hr class="divider inv lg">
</div>
@endsection