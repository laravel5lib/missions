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
    <div id="parallax1" class="prof-cover-photo">
        @if($fundraiser->banner)
            <img src="{{ image($fundraiser->banner->source) }}" class="img-responsive">
        @endif
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>{{ $fundraiser->name }}</h3>
                <div>
                    <ul>
                        <li>Goal Amount: ${{ $fundraiser->goal_amount }}</li>
                        <li>Raised Amount: ${{ $fundraiser->raised() }}</li>
                        <li>Percent Raised: {{ $fundraiser->getPercentRaised() }}%</li>
                        <li>Deadline: {{ $fundraiser->ended_at->format('F j, Y h:i a') }}</li>
                        <li>Time Left: {{ $fundraiser->ended_at->diffForHumans() }}</li>
                    </ul>
                </div>
                <div>
                    {% $fundraiser->description %}
                </div>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <user-profile-fundraisers-donors id="{{ $fundraiser->id }}"></user-profile-fundraisers-donors>
                </div><!-- end panel-group -->
            </div>
        </div>
    </div><!-- end container -->
@stop