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
            <h3 class="text-center text-primary">{{ $fundraiser->name }}</h3>
            <h5 class="text-center">organized by {{ $fundraiser->sponsor->name }}</h5>
            <hr class="divider inv lg">
            <div class="col-sm-8">
                <fundraisers-banner id="{{ $fundraiser->id }}" banner="{{ $fundraiser->banner ? image($fundraiser->banner->source) : '' }}"></fundraisers-banner>
                {{--@if($fundraiser->banner)
                    <img src="{{ image($fundraiser->banner->source) }}" class="img-responsive">
                @endif--}}
                <hr class="divider inv">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#desc" data-toggle="tab">Description</a></li>
                    <li role="presentation"><a href="#stories" data-toggle="tab">Stories</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="desc">
                    {% $fundraiser->description %}
                    </div>
                    <div role="tabpanel" class="tab-pane" id="stories">
                        <fundraisers-stories id="{{ $fundraiser->id }}" sponsor-id="{{ $fundraiser->sponsor_id }}" auth-id="{{ auth()->check() ? auth()->id() : '' }}"></fundraisers-stories>
                    </div>
                </div> <!-- end tab-content -->
            </div><!-- end col -->
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="text-center text-success">${{ $fundraiser->raised() }} <span style="font-size: 18px;">Raised</span></h1>
                        <h4 class="text-center">${{ $fundraiser->goal_amount }} <span style="font-size: 14px;">Goal</span></h4>
                        <h6 class="text-center text-uppercase small text-muted"></h6>
                        {{--<h2 class="text-center">{{ $fundraiser->getPercentRaised() }}% <span style="font-size: 12px;">Percent Raised</span></h2>--}}
                        <div class="panel panel-default" style="background-color: #f7f7f7">
                            <div class="panel-body">
                                <user-profile-fundraisers-progress :now="{{ $fundraiser->getPercentRaised() }}"></user-profile-fundraisers-progress>
                            </div>
                        </div>
                        <h6 class="text-center small text-muted"><i class="fa fa-calendar"></i> Deadline is {{ $fundraiser->ended_at->format('F j, Y h:i a') }}</h6>
                        <h6 class="text-center small text-muted">Ends {{ $fundraiser->ended_at->diffForHumans() }}</h6>
                        <div>
                            <modal-donate title="{{ $fundraiser->fund->name }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}" auth="{{ auth()->check() ? 1 : 0 }}"
                              type="{{ $type or '' }}" type-id="{{ $slug or '' }}" fund-id="{{ $fundraiser->fund->id }}" recipient="{{ $fundraiser->sponsor->name }}"></modal-donate>
                        </div>
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