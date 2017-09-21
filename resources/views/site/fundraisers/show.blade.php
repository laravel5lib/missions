@extends('site.layouts.default')
@section('styles')
    @if( $fundraiser->sponsor_id === (auth()->check() ? auth()->id() : '') )
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/css/Jcrop.min.css" type="text/css">
    @endif
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58ba1e02535b950011d40583&product=inline-share-buttons"></script>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    @if( $fundraiser->sponsor_id === (auth()->check() ? auth()->id() : '') )
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/js/Jcrop.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.14.2/TweenMax.min.js"></script>
        <script>
            // init controller
            var controller = new ScrollMagic.Controller({globalSceneOptions: {triggerHook: "onEnter", duration: "200%"}});
            // build scenes
            new ScrollMagic.Scene({triggerElement: "#parallax1"})
                .setTween("#parallax1 > img", {y: "80%", ease: Linear.easeNone})
                .addTo(controller);
        </script>
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <hr class="divider inv xlg">
            <h3 class="text-center text-primary">{{ $fundraiser->name }}</h3>
            <h5 class="text-center text-capitalize">organized by <a href="{{ url($fundraiser->sponsor->slug->url) }}">{{ $fundraiser->sponsor->name }}</a></h5>
            <hr class="divider inv lg">
            <div class="col-xs-12 col-sm-6 col-sm-push-6 col-md-4 col-md-push-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1 class="text-center text-success"><listen-text text="${{ $fundraiser->raisedAsDollars() }}" event="Fundraiser::raised"></listen-text> <span style="font-size: 18px;">Raised</span></h1>
                        @unless($fundraiser->isClosed())
                        <h4 class="text-center">${{ $fundraiser->goalAmountAsDollars() }} <span style="font-size: 14px;">Goal</span></h4>
                        <div class="col-xs-12 clearfix">
                            <div class="row" style="background: #f5f5f5">
                                <user-profile-fundraisers-progress :now="{{ $fundraiser->getPercentRaised() }}"></user-profile-fundraisers-progress>
                            </div>
                            <hr class="divider sm inv">
                        </div>
                        <h6 class="text-center small text-muted">Ends {{ $fundraiser->ended_at->diffForHumans() }}</h6>
                        <div>
                            <div class="text-center">
                            <a class="btn btn-success" data-toggle="collapse" href="#collapseDonate" aria-expanded="false" aria-controls="collapseDonate">Donate Now</a>
                            </div>
                            <hr class="divider inv sm">
                        </div>
                        @endunless

                        @unless($fundraiser->isOpen())
                            <h6 class="text-center small text-muted">This fundraiser closed on {{ $fundraiser->ended_at->format('F j, Y') }}.</h6>
                            <p class="text-center small text-muted">You can still make a general donation to Missions.Me by clicking the button below.</p>
                            <div class="text-center">
                                <a class="btn btn-success" href="{{ url('donate') }}">Donate<span class="hidden-sm"> to Missions.Me</span></a>
                            </div>
                        @endunless
                    </div>
                </div>

                <modal-donate title="{{ $fundraiser->fund->name }}" stripe-key="{{ env('STRIPE_KEY') }}" auth="{{ auth()->check() ? 1 : 0 }}"
                              type="{{ $type or '' }}" type-id="{{ $slug or '' }}" fund-id="{{ $fundraiser->fund->id }}" recipient="{{ $fundraiser->fund->name }}"></modal-donate>

                <div class="sharethis-inline-share-buttons"></div>
                <hr class="divider lg">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <user-profile-fundraisers-donors id="{{ $fundraiser->id }}"></user-profile-fundraisers-donors>
                </div><!-- end panel-group -->
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-pull-6 col-md-8 col-md-pull-4">
                <fundraisers-uploads id="{{ $fundraiser->id }}" sponsor-id="{{ $fundraiser->sponsor_id }}" auth-id="{{ (auth()->check() ? auth()->id() : '') }}"></fundraisers-uploads>
                <hr class="divider inv">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#desc" data-toggle="tab">The Story</a></li>
                    <li role="presentation"><a href="#stories" data-toggle="tab">Fundraiser Updates</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="desc">
                        <fundraisers-manager id="{{ $fundraiser->id }}" sponsor-id="{{ $fundraiser->sponsor_id }}" auth-id="{{ (auth()->check() ? auth()->id() : '') }}"></fundraisers-manager>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="stories">
                        <fundraisers-stories id="{{ $fundraiser->id }}" sponsor-id="{{ $fundraiser->sponsor_id }}" auth-id="{{ (auth()->check() ? auth()->id() : '') }}"></fundraisers-stories>
                    </div>
                </div> <!-- end tab-content -->
            </div><!-- end col -->
        </div><!-- end row -->
        <hr class="divider inv xlg">
    </div><!-- end container -->
    <markdown-example-modal></markdown-example-modal>
@stop