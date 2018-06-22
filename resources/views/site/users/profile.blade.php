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
    @unless($user->public)
    <div class="dark-bg-primary">
        <div class="container">
            <div class="col-sm-8 text-center">
                <hr class="divider inv sm">
                {{-- <hr class="divider inv sm"> --}}
                <h5>Your account is set to private. Only you can see this page.</h5>
                <hr class="divider inv sm">
                {{-- <hr class="divider inv sm hidden-xs"> --}}
            </div>
            <div class="col-sm-4 text-center">
                <hr class="divider inv sm hidden-xs">
                <a href="{{ url('/dashboard/settings') }}" class="btn btn-sm btn-white-hollow"><i class="fa fa-cog"></i> Settings</a>
                <hr class="divider inv sm">
            </div>
        </div><!-- end container -->
    </div><!-- end dark-bg-primary -->
    @endunless
    <div id="parallax1" class="prof-cover-photo hidden-xs">
        <img src="{{ image($user->getBanner()->source) }}" alt="{{ $user->name }}">
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="panel panel-default profile-pic-panel">
                    <img src="{{ image($user->getAvatar()->source.'?w=400') }}" alt="{{ $user->name }}" class="img-responsive">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-10">
                                <h4>{{ $user->name }}</h4>
                                <h6 class="small text-muted">/{{ $user->slug->url }}</h6>
                            </div>
                            <div class="col-xs-2">
                                @if($user->id == (auth()->check() ? auth()->user()->id : null))
                                <h4>
                                    <a class="pull-right text-muted" href="{{ url('dashboard/settings') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </h4>
                                @endif
                            </div>
                        </div>
                        <p class="small">{{ $user->bio }}</p>
                        <p class="small"><i class="fa fa-map-marker text-muted" style="margin-right:3px;"></i> 
                        {{ $user->city ? $user->city.', ' : null }} {{ $user->state ? $user->state.', ' : null }} {{ country($user->country_code) }}</p>
                        <ul class="list-unstyled list-inline">
                            @each('site.partials._social_link', $user->links, 'link')
                        </ul>
                    </div><!-- end panel-body -->
                </div><!-- end panel-default -->

                @if(count($user->getGroups()))
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Groups Traveled With</h5>
                    </div><!-- end panel-heading -->
                    <div class="panel-body">
                        @each('site.partials._group', $user->getGroups(), 'group')
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
                @endif
                    
                    <user-profile-countries id="{{ $user->id }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-countries>
                    <user-profile-trip-history id="{{ $user->id }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-trip-history>

            </div><!-- end col -->
            <div class="col-md-9 col-sm-8 col-xs-12">
                <ul id="profTabs" class="nav nav-tabs" role="tablist">
                    <li data-toggle="tooltip" title="Stories" role="presentation" class="active"><a href="#stories" aria-controls="stories" role="tab" data-toggle="tab"><i class="fa fa-list-ul"></i> <span class="hidden-xs">Stories</span></a></li>
                    <li data-toggle="tooltip" title="Fundraisers" role="presentation"><a href="#fundraisers" aria-controls="fundraisers" role="tab" data-toggle="tab"><i class="fa fa-dollar"></i> <span class="hidden-xs">Fundraisers</span></a></li>
                    @if($user->id == (auth()->user() ? auth()->user()->id : null))
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="{{ url('dashboard') }}"><i class="fa fa-tachometer"></i></a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="row tab-pane active" id="stories">
                        <div class="col-md-12 col-md-offset-0 col-xs-12">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <user-profile-stories id="{{ $user->id }}" user-url="{{ '@' . $user->url }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-stories>
                            </div>
                        </div>
                    </div><!-- end row tab -->
                    <div role="tabpanel" class="row tab-pane" id="fundraisers">
                        <user-profile-fundraisers id="{{ $user->id }}"></user-profile-fundraisers>
                </div><!-- end row -->
                </div><!-- end tab-content -->
            </div><!-- end col -->
        </div><!-- end row -->
    <hr class="divider inv xlg">
    </div><!-- end container-->

@endsection