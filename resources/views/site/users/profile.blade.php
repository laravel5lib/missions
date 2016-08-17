@extends('site.layouts.default')

@section('content')


    <div id="parallax1" class="prof-cover-photo">
        <img src="{{ image($user->banner->source) }}" alt="{{ $user->name }}">
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-offset-0 col-sm-4 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default profile-pic-panel">
                    <img src="{{ image($user->avatar->source) }}" alt="{{ $user->name }}" class="img-responsive">
                    <div class="panel-body">
                        <h4>{{ $user->name }}</h4>
                        <h6>/{{ $user->url }}</h6>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Countries Visited</h5>
                    </div><!-- end panel-heading -->
                    <div class="panel-body">
                        <h4>
                            @foreach($user->getCountriesVisited() as $country)
                                <p><span class="label label-primary">
                                    <i class="fa fa-map-marker"></i> {{ country($country) }}
                                </span></p>
                            @endforeach
                        </h4>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
                @endif
            </div><!-- end col -->
            <div class="col-lg-6 col-sm-8 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <ul id="profTabs" class="nav nav-tabs" role="tablist">
                    <li data-toggle="tooltip" title="Fundraisers" role="presentation" class="active"><a href="#fundraisers" aria-controls="fundraisers" role="tab" data-toggle="tab"><i class="fa fa-dollar"></i></a></li>
                    <li data-toggle="tooltip" title="Updates" role="presentation"><a href="#updates" aria-controls="updates" role="tab" data-toggle="tab"><i class="fa fa-pencil"></i></a></li>
                    <li data-toggle="tooltip" title="Stats" role="presentation"><a href="#stats" aria-controls="stats" role="tab" data-toggle="tab"><i class="fa fa-line-chart"></i></a></li>
                    <li data-toggle="tooltip" title="Stories" role="presentation"><a href="#stories" aria-controls="stories" role="tab" data-toggle="tab"><i class="fa fa-list-ul"></i></a></li>
                    @can('update', auth()->user())
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="settings.html"><i class="fa fa-tachometer"></i></a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="row tab-pane active" id="fundraisers">
                        <div class="col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12">
                            <div class="panel panel-default">
                                <img src="images/india-prof-pic.jpg" alt="India" class="img-responsive">
                                <div class="panel-body">
                                    <h4>Christmas In India</h4>
                                    <h6>Tagline</h6>
                                    <h3><span class="text-success">$2000</span> <small>Raised</small></h3>
                                    <p><span>50</span>% <small>Funded</small> / <span>2</span> <small>Donors</small></p>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                    <p><a class="btn btn-primary btn-block" href="#">Details</a></p>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div><!-- end col -->
                        <div class="col-md-6 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12">
                            <div class="panel panel-default">
                                <img src="images/japan-prof-pic.jpg" alt="Japan" class="img-responsive">
                                <div class="panel-body">
                                    <h4>Christmas In Japan</h4>
                                    <h6>Tagline</h6>
                                    <h3><span class="text-success">$2000</span> <small>Raised</small></h3>
                                    <p><span>50</span>% <small>Funded</small> / <span>2</span> <small>Donors</small></p>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                    <p><a class="btn btn-primary btn-block" href="#">Details</a></p>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div><!-- end col -->
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
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <user-profile-stories id="{{ $user->id }}" auth-id="{{ auth()->check() ? auth()->user()->id : null }}"></user-profile-stories>
                        </div>
                    </div><!-- end row tab -->
                </div><!-- end tab-content -->
            </div><!-- end col -->
            <div class="col-lg-3 col-md-8 col-sm-offset-0 col-sm-8 col-xs-10 col-xs-offset-1">
                <hr class="divider inv">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Activity</h5>
                    </div><!-- end panel-heading -->
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-circle img-xs" src="images/headshot-1.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading"><a href="#">Jonny B</a> <small>added you to the <a href="#">Missions.Me</a> group</small></h5>
                                <small>1 hour ago</small>
                            </div><!-- end media-body -->
                        </div><!-- end media -->
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-circle img-xs" src="images/headshot-2.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading"><a href="#">Jonny B</a> <small>added you to the <a href="#">Missions.Me</a> group</small></h5>
                                <small>1 hour ago</small>
                            </div><!-- end media-body -->
                        </div><!-- end media -->
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-circle img-xs" src="images/headshot-3.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading"><a href="#">Jonny B</a> <small>added you to the <a href="#">Missions.Me</a> group</small></h5>
                                <small>1 hour ago</small>
                            </div><!-- end media-body -->
                        </div><!-- end media -->
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-circle img-xs" src="images/headshot-4.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading"><a href="#">Jonny B</a> <small>added you to the <a href="#">Missions.Me</a> group</small></h5>
                                <small>1 hour ago</small>
                            </div><!-- end media-body -->
                        </div><!-- end media -->

                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
        </div><!-- end row -->

    </div><!-- end container-->


@endsection