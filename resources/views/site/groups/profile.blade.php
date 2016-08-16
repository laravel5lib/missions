@extends('site.layouts.default')

@section('content')

    <div id="parallax1" class="prof-cover-photo">
        <img src="{{ $group->banner }}" alt="{{ $group->name }}">
    </div><!-- end page-header-outer -->
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-offset-0 col-sm-4 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default profile-pic-panel">
                    <img src="{{ $group->avatar }}" alt="{{ $group->name }}" class="img-responsive">
                    <div class="panel-body">
                        <h4>{{ $group->name }}</h4>
                        <h6>/{{ $group->url }}</h6>
                        <p>{{ $group->description }}</p>
                        <p><i class="fa fa-map-marker"></i>
                            {{ $group->city ? $group->city.', ' : null }}
                            {{ $group->state ? $group->state.', ' : null }}
                            <small>{{ $group->country_name }}</small></p>
                        <ul class="list-unstyled list-inline">
                            @each('site.partials._social_link', $group->social, 'link')
                        </ul>
                    </div><!-- end panel-body -->
                </div><!-- end panel-default -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>Countries Visited</h4>
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-rounded img-xs" src="images/v-prof-pic.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading">Colombia</h5>
                            </div><!-- end media-body -->
                        </div><!-- end media -->
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-rounded img-xs" src="images/v-prof-pic.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading">Guatemala</h5>
                            </div><!-- end media-body -->
                        </div><!-- end media -->
                        <div class="media">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object img-rounded img-xs" src="images/v-prof-pic.jpg" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <h5 class="media-heading">Dominican Republic</h5>
                            </div><!-- end media-body -->
                        </div><!-- end media -->
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
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
                    <li data-toggle="tooltip" title="Current Trips" role="presentation" class="active"><a href="#current-trips" aria-controls="fundraisers" role="tab" data-toggle="tab"><i class="fa fa-plane"></i></a></li>
                    <li data-toggle="tooltip" title="Updates" role="presentation"><a href="#updates" aria-controls="updates" role="tab" data-toggle="tab"><i class="fa fa-pencil"></i></a></li>
                    <li data-toggle="tooltip" title="Dashboard" class="pull-right"><a href="#"><i class="fa fa-tachometer"></i></a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="row tab-pane active" id="current-trips">
                        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12">
                            <div class="panel panel-default">
                                <img src="images/india-prof-pic.jpg" alt="India" class="img-responsive">
                                <div class="panel-body">
                                    <h6><span class="label label-default">Write The Future</span></h6>
                                    <h4>Peru 2016</h4>
                                    <h6>Full Week Missionary</h6>
                                    <h6>July 20 - July 27, 2015</h6>
                                    <ul class="list-inline">
                                        <li data-toggle="tooltip" title="Reservations"><i class="fa fa-user"></i> 30</li>
                                        <li data-toggle="tooltip" title="Registration Open" class="pull-right"><i class="fa fa-sign-in"></i></li>
                                    </ul>
                                    <a class="btn btn-default btn-link btn-sm btn-block" href="#">Learn More</a>
                                    <p><a class="btn btn-primary btn-lg btn-block" href="#">Register</a></p>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div><!-- end col -->
                        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12">
                            <div class="panel panel-default">
                                <img src="images/japan-prof-pic.jpg" alt="Japan" class="img-responsive">
                                <div class="panel-body">
                                    <h6><span class="label label-default">Write The Future</span></h6>
                                    <h4>Peru 2016</h4>
                                    <h6>Full Week Missionary</h6>
                                    <h6>July 20 - July 27, 2015</h6>
                                    <ul class="list-inline">
                                        <li data-toggle="tooltip" title="Reservations"><i class="fa fa-user"></i> 30</li>
                                        <li data-toggle="tooltip" title="Registration Open" class="pull-right"><i class="fa fa-sign-in"></i></li>
                                    </ul>
                                    <a class="btn btn-default btn-link btn-sm btn-block" href="#">Learn More</a>
                                    <p><a class="btn btn-primary btn-lg btn-block" href="#">Register</a></p>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div><!-- end col -->
                        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12">
                            <div class="panel panel-default">
                                <img src="images/japan-prof-pic.jpg" alt="Japan" class="img-responsive">
                                <div class="panel-body">
                                    <h6><span class="label label-default">Write The Future</span></h6>
                                    <h4>Peru 2016</h4>
                                    <h6>Full Week Missionary</h6>
                                    <h6>July 20 - July 27, 2015</h6>
                                    <ul class="list-inline">
                                        <li data-toggle="tooltip" title="Reservations"><i class="fa fa-user"></i> 30</li>
                                        <li data-toggle="tooltip" title="Registration Open" class="pull-right"><i class="fa fa-sign-in"></i></li>
                                    </ul>
                                    <a class="btn btn-default btn-link btn-sm btn-block" href="#">Learn More</a>
                                    <p><a class="btn btn-primary btn-lg btn-block" href="#">Register</a></p>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div><!-- end col -->
                        <div class="col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-0 col-xs-12">
                            <div class="panel panel-default">
                                <img src="images/japan-prof-pic.jpg" alt="Japan" class="img-responsive">
                                <div class="panel-body">
                                    <h6><span class="label label-default">Write The Future</span></h6>
                                    <h4>Peru 2016</h4>
                                    <h6>Full Week Missionary</h6>
                                    <h6>July 20 - July 27, 2015</h6>
                                    <ul class="list-inline">
                                        <li data-toggle="tooltip" title="Reservations"><i class="fa fa-user"></i> 30</li>
                                        <li data-toggle="tooltip" title="Registration Open" class="pull-right"><i class="fa fa-sign-in"></i></li>
                                    </ul>
                                    <a class="btn btn-default btn-link btn-sm btn-block" href="#">Learn More</a>
                                    <p><a class="btn btn-primary btn-lg btn-block" href="#">Register</a></p>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div><!-- end col -->
                    </div><!-- end tab-pane -->
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
                </div><!-- end tab-content -->
            </div><!-- end col -->
            <div class="col-lg-3 col-md-8 col-sm-offset-0 col-sm-8 col-xs-10 col-xs-offset-1">
                <hr class="divider inv">

            </div><!-- end col -->
        </div><!-- end row -->

    </div><!-- end container-->

@endsection