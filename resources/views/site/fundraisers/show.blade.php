@extends('site.layouts.default')
@section('scripts')
<script src="http://cdnjs.cloudflare.com/ajax/libs/gsap/1.14.2/TweenMax.min.js"></script>
<script>
    // init controller
    var controller = new ScrollMagic.Controller({globalSceneOptions: {triggerHook: "onEnter", duration: "200%"}});
    // build scenes
    new ScrollMagic.Scene({triggerElement: "#parallax1"})
        .setTween("#parallax1 > img", {y: "80%", ease: Linear.easeNone})
        .addTo(controller);
</script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <hr class="divider inv xlg">
            <h3 class="text-center">{{ $fundraiser->name }}</h3>
            <h5 class="text-center">by {{ $fundraiser->sponsor->name }}</h5>
            <hr class="divider inv lg">
            <div class="col-sm-8">
                @if($fundraiser->banner)
                    <img src="{{ image($fundraiser->banner->source) }}" class="img-responsive">
                @endif
                <div>
                    {% $fundraiser->description %}
                </div>
                <div>
                    <fundraisers-stories id="{{ $fundraiser->id }}" sponsor-id="{{ $fundraiser->sponsor_id }}" auth-id="{{ auth()->check() ? auth()->id() : '' }}"></fundraisers-stories>
                </div>
            </div><!-- end col -->
            <div class="col-sm-4">
                <modal-donate title="{{ $fundraiser->name }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}" auth="{{ auth()->check() ? 1 : 0 }}"
                              type="{{ $type or '' }}" type-id="{{ $slug or '' }}" recipient="{{ $fundraiser->sponsor->name }}"></modal-donate>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="text-center text-success">${{ $fundraiser->raised() }} <span style="font-size: 12px;">Raised</span></h1>
                        <h5 class="text-center"><small class="text-uppercase">Goal Amount</small> ${{ $fundraiser->goal_amount }}</h5>
                        <h6 class="text-center text-uppercase small text-muted"></h6>
                        {{--<h2 class="text-center">{{ $fundraiser->getPercentRaised() }}% <span style="font-size: 12px;">Percent Raised</span></h2>--}}
                        <user-profile-fundraisers-progress :now="{{ $fundraiser->getPercentRaised() }}"></user-profile-fundraisers-progress>
                        <hr class="divider lg">
                        <h6 class="text-center small text-muted"><i class="fa fa-calendar"></i> Deadline is {{ $fundraiser->ended_at->format('F j, Y h:i a') }}</h6>
                        <h6 class="text-center small text-muted">Ends {{ $fundraiser->ended_at->diffForHumans() }}</h6>
                    </div>
                </div>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <user-profile-fundraisers-donors id="{{ $fundraiser->id }}"></user-profile-fundraisers-donors>
                </div><!-- end panel-group -->
            </div>
        </div>
        <hr class="divider inv xlg">
    </div><!-- end container -->
@stop