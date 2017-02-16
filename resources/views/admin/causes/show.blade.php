@extends('admin.layouts.default')

@section('scripts')
    <script>
        // Javascript to enable link to tab
        var hash = document.location.hash;
        var tab_router = _.last(location.pathname.split('/'));
        console.log(tab_router);
        if (_.contains(['current-projects', 'archived-projects', 'current-initiatives', 'archived-initiatives'], tab_router)) {
            $('.nav-pills a[href="#'+tab_router+'"]').tab('show');
        }

        // Change hash for page-reload
        $('.nav-pills a').on('shown.bs.tab', function (e) {
            window.history.pushState({}, "Missions.me", '/admin/causes/{{ $cause->id }}/' + e.target.hash.substr(1));
        });
    </script>
@endsection

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row hidden-xs">
                <div class="col-sm-8">
                    <h3>{{ $cause->name }}</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="/admin/causes" class="btn btn-default"><i class="fa fa-chevron-left icon-left"></i> Causes</a>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-8 text-center">
                    <h3>Project Causes</h3>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="/admin/causes" class="btn btn-default"><i class="fa fa-chevron-left icon-left"></i> Causes</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
               <cause-editor id="{{ $cause->id }}" :edit="true"></cause-editor>
            </div>
            <div class="col-md-6">
                <div class="circle-tile ">
                    <div class="circle-tile-content">
                        <div class="circle-tile-description">New</div>
                        <div class="circle-tile-name">{{ $cause->name }} Projects</div>
                        <div class="circle-tile-number">{{ $cause->projects()->new()->count() }}</div>
                    </div>
                </div>
                <div class="circle-tile ">
                    <div class="circle-tile-content">
                        <div class="circle-tile-description">Funded</div>
                        <div class="circle-tile-name">{{ $cause->name }} Projects</div>
                        <div class="circle-tile-number">{{ $cause->projects()->funded()->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-pills" role="tablist">
                            <li><a href="#current-projects" role="tab" data-toggle="tab">Current Projects</a></li>
                            <li><a href="#archived-projects" role="tab" data-toggle="tab">Archived Projects</a></li>
                            <li><a href="#current-initiatives" role="tab" data-toggle="tab">Current Initiatives</a></li>
                            <li><a href="#archived-initiatives" role="tab" data-toggle="tab">Archived Initiatives</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="current-projects">
                                <projects-list cause-id="{{ $cause->id }}" list="current"></projects-list>
                            </div>
                            <div class="tab-pane fade in" id="archived-projects">
                                <projects-list cause-id="{{ $cause->id }}" list="archived"></projects-list>
                            </div>
                            <div class="tab-pane fade in" id="current-initiatives">
                                <initiatives-list cause-id="{{ $cause->id }}" list="current"></initiatives-list>
                            </div>
                            <div class="tab-pane fade in" id="archived-initiatives">
                                <initiatives-list cause-id="{{ $cause->id }}" list="archived"></initiatives-list>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection