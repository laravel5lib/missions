@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="hidden-xs">My Reservations</h3>
                <h3 class="text-center visible-xs">My Reservations</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container" v-tour-guide="">
        <div class="row">
            <div class="col-sm-12">
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
                        <reservations-list user-id="{{ Auth::user()->id }}" type="active"></reservations-list>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="archive">
                        <reservations-list user-id="{{ Auth::user()->id }}" type="archive"></reservations-list>
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
                id: 'find',
                title: 'Find Reservations',
                text: 'Search, filter and sort to find what you need. Export the list or change the view from list to grid.',
                attachTo: {
                    element: '.tour-step-find',
                    on: 'top'
                },
            },
            {
                id: 'list',
                title: 'At a Glance',
                text: 'This list only gives a quick overveiw of reservations. Select a reservation to see more details.',
                attachTo: {
                    element: '.tour-step-list',
                    on: 'top'
                },
            }
        ];
    </script>
@endsection