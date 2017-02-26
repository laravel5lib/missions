@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="hidden-xs">My Projects</h3>
                    <h3 class="visible-xs text-center">My Projects</h3>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container" v-tour-guide="">
        <div class="row">
            <div class="col-sm-12 tour-step-manage">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#active" data-toggle="pill"><i class="fa fa-bolt"></i> Active</a>
                    </li>
                    <li role="presentation">
                        <a href="#archive" data-toggle="pill"><i class="fa fa-archive"></i> Archived</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="active">
                        <user-projects-list user-id="{{ Auth::user()->id }}" type="active"></user-projects-list>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="archive">
                        <user-projects-list user-id="{{ Auth::user()->id }}" type="archive"></user-projects-list>
                    </div>

                </div>
            </div>
        </div>
        <hr class="divider inv lg">
    </div>

@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'manage',
                title: 'Manage Projects',
                text: 'Find current and past projects you are managing right here. Click on a project card to see more details about the project.',
                attachTo: {
                    element: '.tour-step-manage',
                    on: 'top'
                },
            },
            {
                id: 'find',
                title: 'Find Projects',
                text: 'Search, filter and sort to find the project you are looking for.',
                attachTo: {
                    element: '.tour-step-find',
                    on: 'top'
                },
            },
        ];
    </script>
@endsection