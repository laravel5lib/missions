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

    <div id="parallax1" class="prof-cover-photo hidden-xs">
        <img src="{{ image($group->banner->source) }}" alt="{{ $group->name }}">
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-offset-0 col-sm-4 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default profile-pic-panel">
                    <img src="{{ image($group->avatar->source) }}" alt="{{ $group->name }}" class="img-responsive">
                    <div class="panel-body">
                        <h4>{{ $group->name }}</h4>
                        <h6>/{{ $group->url }}</h6>
                        <p>{{ $group->description }}</p>
                        <p><i class="fa fa-map-marker"></i>
                            {{ $group->city ? $group->city.', ' : null }}
                            {{ $group->state ? $group->state.', ' : null }}
                            <small>{{ country($group->country_code) }}</small></p>
                        <ul class="list-unstyled list-inline">
                            {{--@each('site.partials._social_link', $group->social, 'link')--}}
                        </ul>
                    </div><!-- end panel-body -->
                </div><!-- end panel-default -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Need Help?</h5>
                    </div><!-- end panel-heading -->
                    <div class="panel-body">
                        <a class="btn btn-default btn-block" href="#">Read FAQs</a>
                        <a class="btn btn-default btn-block" href="#">How To Register</a>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
            <div class="col-lg-9 col-sm-8 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <ul id="profTabs" class="nav nav-tabs" role="tablist">
                    <li data-toggle="tooltip" title="Current Trips" role="presentation" class="active"><a href="#current-trips" aria-controls="current-trips" role="tab" data-toggle="tab"><i class="fa fa-plane"></i></a></li>
                    <li data-toggle="tooltip" title="Fundraisers" role="presentation"><a href="#fundraisers" aria-controls="fundraisers" role="tab" data-toggle="tab"><i class="fa fa-dollar"></i></a></li>
                    <li data-toggle="tooltip" title="Stories" role="presentation"><a href="#stories" aria-controls="stories" role="tab" data-toggle="tab"><i class="fa fa-comments"></i></a></li>
                    @can('edit', $group)
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="#"><i class="fa fa-tachometer"></i></a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="row tab-pane active" id="current-trips">
                        <group-profile-trips id="{{ $group->id }}"></group-profile-trips>
                    </div><!-- end tab-pane -->
                    <div role="tabpanel" class="row tab-pane active" id="fundraisers">
                        <group-profile-fundraisers id="{{ $group->id }}" group-url="{{ $group->url }}"></group-profile-fundraisers>
                    </div><!-- end tab-pane -->
                    <div role="tabpanel" class="row tab-pane" id="stories">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <group-profile-stories id="{{ $group->id }}"></group-profile-stories>
                        </div>
                    </div><!-- end row tab -->
                </div><!-- end tab-content -->
            </div><!-- end col -->
            <div class="col-lg-3 col-md-8 col-sm-offset-0 col-sm-8 col-xs-10 col-xs-offset-1">
                <hr class="divider inv">

            </div><!-- end col -->
        </div><!-- end row -->

    </div><!-- end container-->

@endsection