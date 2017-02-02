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
    @unless($group->public)
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
                <div class="panel panel-default profile-pic-panel" data-aos="fade-up">
                    <img src="{{ image($group->getAvatar()->source) }}" alt="{{ $group->name }}" class="img-responsive">
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
                    <li data-toggle="tooltip" title="Stories" role="presentation" class="active"><a href="#stories" aria-controls="stories" role="tab" data-toggle="tab"><i class="fa fa-comments"></i> <span class="hidden-xs">Stories</span></a></li>
                    <li data-toggle="tooltip" title="Current Trips" role="presentation"><a href="#current-trips" aria-controls="current-trips" role="tab" data-toggle="tab"><i class="fa fa-plane"></i> <span class="hidden-xs">Trips</span></a></li>
                    {{-- <li data-toggle="tooltip" title="Fundraisers" role="presentation"><a href="#fundraisers" aria-controls="fundraisers" role="tab" data-toggle="tab"><i class="fa fa-dollar"></i> <span class="hidden-xs">Fundraisers</span></a></li> --}}
                    @can('edit', $group)
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="/dashboard/groups/{{ $group->id }}"><i class="fa fa-tachometer"></i></a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="stories">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <group-profile-stories id="{{ $group->id }}" :manager-ids="{{ $group->managers->pluck('id') }}" auth-id="{{ auth()->check() ? auth()->user()->id : '' }}"></group-profile-stories>
                        </div>
                    </div><!-- end row tab -->
                    <div role="tabpanel" class="tab-pane" id="current-trips">
                        <group-profile-trips id="{{ $group->id }}"></group-profile-trips>
                    </div><!-- end tab-pane -->
                    {{-- <div role="tabpanel" class="tab-pane" id="fundraisers">
                        <group-profile-fundraisers id="{{ $group->id }}" group-url="{{ $group->url }}"></group-profile-fundraisers>
                    </div><!-- end tab-pane --> --}}
                </div><!-- end tab-content -->
            </div><!-- end col -->
            <div class="col-lg-3 col-md-8 col-sm-offset-0 col-sm-8 col-xs-10 col-xs-offset-1">
                <hr class="divider inv">

            </div><!-- end col -->
        </div><!-- end row -->

    </div><!-- end container-->

@endsection