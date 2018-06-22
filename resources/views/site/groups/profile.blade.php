@extends('site.layouts.default')
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.14.2/TweenMax.min.js"></script>
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
    @unless($group->public)
    <div class="dark-bg-primary">
        <div class="container">
            <div class="col-sm-8 text-center">
                <hr class="divider inv sm">
                <h5>Your account is set to private. Only you can see this page.</h5>
                <hr class="divider inv sm">
            </div>
            <div class="col-sm-4 text-center">
                <hr class="divider inv sm hidden-xs">
                <a href="{{ url('/dashboard/groups/' . $group->id) }}" class="btn btn-sm btn-white-hollow"><i class="fa fa-cog"></i> Settings</a>
                <hr class="divider inv sm">
            </div>
        </div><!-- end container -->
    </div><!-- end dark-bg-primary -->
    @endunless
    <div id="parallax1" class="prof-cover-photo hidden-xs">
        <img src="{{ image($group->getBanner()->source) }}" alt="{{ $group->name }}">
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="panel panel-default profile-pic-panel">
                    <img src="{{ image($group->getAvatar()->source.'?w=400') }}" alt="{{ $group->name }}" class="img-responsive">
                    <div class="panel-body">
                        <h4>{{ $group->name }}</h4>
                        <h6 class="small text-muted">/{{ $group->slug->url }}</h6>
                        <p class="small">{{ $group->description }}</p>
                        <p class="small"><i class="fa fa-map-marker text-muted" style="margin-right:3px;"></i>
                            {{ $group->city ? $group->city.', ' : null }}
                            {{ $group->state ? $group->state.', ' : null }}
                            {{ country($group->country_code) }}</p>
                        <ul class="list-unstyled list-inline text-muted">
                            @each('site.partials._social_link', $group->social, 'link')
                        </ul>
                    </div><!-- end panel-body -->
                </div><!-- end panel-default -->
            </div><!-- end col -->
            <div class="col-md-9 col-sm-8 col-xs-12">
                <ul id="profTabs" class="nav nav-tabs" role="tablist">
                    <li data-toggle="tooltip" title="Stories" role="presentation"><a href="#stories" aria-controls="stories" role="tab" data-toggle="tab"><i class="fa fa-comments"></i> <span class="hidden-xs">Stories</span></a></li>
                    <li data-toggle="tooltip" title="Current Trips" role="presentation" class="active"><a href="#current-trips" aria-controls="current-trips" role="tab" data-toggle="tab"><i class="fa fa-plane"></i> <span class="hidden-xs">Trips</span></a></li>
                    @can('edit', $group)
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="/dashboard/groups/{{ $group->id }}"><i class="fa fa-tachometer"></i></a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="stories">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <group-profile-stories id="{{ $group->id }}" :manager-ids="{{ $group->managers->pluck('id') }}" auth-id="{{ auth()->check() ? auth()->user()->id : '' }}"></group-profile-stories>
                        </div>
                    </div><!-- end row tab -->
                    <div role="tabpanel" class="tab-pane active" id="current-trips">
                            @foreach($campaigns as $campaign)

                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="{{ url($campaign->slug->url) }}">
                                                    <img class="img-responsive" 
                                                         src="{{ image($campaign->getAvatar()->source).'?w=500' }}">
                                                </a>
                                                <hr class="divider inv visible-sm visible-xs">
                                            </div>
                                            <div class="col-md-8">
                                                <h4 class="media-heading">{{ $campaign->name }}</h4>
                                                <p><strong class="text-primary">
                                                    {{ $campaign->started_at->format('F d') }} - 
                                                    {{ $campaign->ended_at->format('F d, Y') }}
                                                </strong></p>
                                                <p><i class="fa fa-map-marker"></i> {{ country($campaign->country_code) }}</p>
                                                <hr class="divider">
                                                <p>{{ $campaign->short_desc }}</p>
                                                <a href="{{ url($campaign->slug->url) }}" class="btn btn-link">Learn More</a>
                                                <a href="{{ url($campaign->slug->url.'/teams/'.$group->id.'/trips') }}" class="btn btn-primary">Register</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                    </div><!-- end tab-pane -->
                </div><!-- end tab-content -->
            </div><!-- end col -->
            <div class="col-lg-3 col-md-8 col-sm-offset-0 col-sm-8 col-xs-10 col-xs-offset-1">
                <hr class="divider inv">

            </div><!-- end col -->
        </div><!-- end row -->

    </div><!-- end container-->

@endsection