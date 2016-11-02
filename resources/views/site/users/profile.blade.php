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
        <img src="{{ image($user->banner->source) }}" alt="{{ $user->name }}">
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12">
                <div class="panel panel-default profile-pic-panel">
                    <img src="{{ image($user->avatar->source) }}" alt="{{ $user->name }}" class="img-responsive">
                    <div class="panel-body">
                        <h4>{{ $user->name }}</h4>
                        <h6 class="small">/{{ $user->url }}</h6>
                        <p>{{ $user->bio }}</p>
                        <p><i class="fa fa-map-marker"></i> {{ $user->city }}, {{ $user->state }}, {{ country($user->country_code) }}</p>
                        <ul class="list-unstyled list-inline">
                            @each('site.partials._social_link', $user->links, 'link')
                        </ul>
                    </div><!-- end panel-body -->
                </div><!-- end panel-default -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Groups Traveled With</h5>
                    </div><!-- end panel-heading -->
                    <div class="panel-body">
                        @each('site.partials._group', $user->getGroups(), 'group')
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
                @if(count($user->getCountriesVisited()))
                    <user-profile-countries id="{{ $user->id }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-countries>
                @endif
            </div><!-- end col -->
            <div class="col-md-9 col-sm-8 col-xs-12">
                <ul id="profTabs" class="nav nav-tabs" role="tablist">
                    <li data-toggle="tooltip" title="Fundraisers" role="presentation" class="active"><a href="#fundraisers" aria-controls="fundraisers" role="tab" data-toggle="tab"><i class="fa fa-dollar"></i> <span class="hidden-xs">Fundraisers</span></a></li>
                    <li data-toggle="tooltip" title="Stories" role="presentation"><a href="#stories" aria-controls="stories" role="tab" data-toggle="tab"><i class="fa fa-list-ul"></i> <span class="hidden-xs">Stories</span></a></li>
                    @can('edit', auth()->user())
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="/dashboard"><i class="fa fa-tachometer"></i></a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="row tab-pane active" id="fundraisers">
                        <user-profile-fundraisers id="{{ $user->id }}" user-url="{{ '@'.$user->url }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-fundraisers>
                    </div><!-- end row -->
                    <div role="tabpanel" class="row tab-pane" id="updates">
                        <div class="col-md-12 col-md-offset-0 col-xs-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="exampleInputPost" placeholder="Post an update in 140 characters or less">
                                <span class="input-group-btn">
                  <button class="btn btn-primary" type="button">Post</button>
                </span>
                            </div>
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object img-circle img-xs" src="images/nelson-prof-pic.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading"><a href="#">Zech Nelson</a> <small>Today at 3:34pm</small></h5>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                    <small><a href="#"><i class="fa fa-comment"></i> Comment</a></small>
                                </div><!-- end media-body -->
                            </div><!-- end media -->
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object img-circle img-xs" src="images/nelson-prof-pic.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading"><a href="#">Zech Nelson</a> <small>Today at 3:34pm</small></h5>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                    <small><a href="#"><i class="fa fa-comment"></i> Comment</a></small>
                                </div><!-- end media-body -->
                            </div><!-- end media -->
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object img-circle img-xs" src="images/nelson-prof-pic.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading"><a href="#">Zech Nelson</a> <small>Today at 3:34pm</small></h5>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                    <small><a href="#"><i class="fa fa-comment"></i> Comment</a></small>
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object img-circle img-xs" src="images/headshot-1.jpg" alt="...">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="media-heading"><a href="#">Nancy</a> <small>Today at 3:34pm</small></h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                        </div><!-- end media-body -->
                                    </div><!-- end media -->
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object img-circle img-xs" src="images/headshot-5.jpg" alt="...">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="media-heading"><a href="#">Jeremy</a> <small>Today at 3:34pm</small></h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                        </div><!-- end media-body -->
                                    </div><!-- end media -->
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object img-circle img-xs" src="images/headshot-2.jpg" alt="...">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="media-heading"><a href="#">Sarah</a> <small>Today at 3:34pm</small></h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                        </div><!-- end media-body -->
                                    </div><!-- end media -->
                                </div><!-- end media-body -->
                            </div><!-- end media -->
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object img-circle img-xs" src="images/nelson-prof-pic.jpg" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading"><a href="#">Zech Nelson</a> <small><i class="fa fa-clock"></i> Today at 3:34pm</small></h5>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias placeat tenetur quaerat, aut incidunt magni tempore culpa eos.</p>
                                    <small><a href="#"><i class="fa fa-comment"></i> Comment</a></small>
                                </div><!-- end media-body -->
                            </div><!-- end media -->
                        </div>
                    </div><!-- end row tab -->
                    <div role="tabpanel" class="row tab-pane" id="stories">
                        <div class="col-md-12 col-md-offset-0 col-xs-12">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <user-profile-stories id="{{ $user->id }}" user-url="{{ '@' . $user->url }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-stories>
                            </div>
                        </div>
                    </div><!-- end row tab -->
                </div><!-- end tab-content -->
            </div><!-- end col -->
        </div><!-- end row -->
    <hr class="divider inv xlg">
    </div><!-- end container-->

@endsection