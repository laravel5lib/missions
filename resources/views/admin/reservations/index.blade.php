@extends('layouts.admin')

@section('scripts')
    <script>
        // Javascript to enable link to tab
        var hash = document.location.hash;
        var tab_router = _.last(location.pathname.split('/'));
        if (_.contains(['current', 'archived', 'dropped', 'prospects'], tab_router)) {
            $('.nav-tabs a[href="#'+tab_router+'"]').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.history.pushState({}, "Missions.me", '/admin/reservations/' + e.target.hash.substr(1));
        });
    </script>
@endsection

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Reservations'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="col-xs-12">

        <div class="row">
            <div class="col-sm-12">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <li><a href="#current" role="tab" data-toggle="tab">Current</a></li>
                    <li><a href="#archived" role="tab" data-toggle="tab">Archived</a></li>
                    <li><a href="#dropped" role="tab" data-toggle="tab">Dropped</a></li>
                    @can('view', \App\Models\v1\TripInterest::class)
                        <li><a href="#prospects" role="tab" data-toggle="tab">Interests</a></li>
                    @endcan
                </ul>
                <!-- TAB CONTENT -->
                <div class="tab-content">
                    <div class="active tab-pane fade in" id="current">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <admin-reservations-list type="current"
                                                        storage-name="AdminReservationsCurrentStorage">
                                </admin-reservations-list>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="archived">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <admin-reservations-list type="archived"
                                                        storage-name="AdminReservationsArchivedStorage">
                                </admin-reservations-list>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="dropped">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <admin-reservations-list type="dropped"
                                                        storage-name="AdminReservationsDroppedStorage">
                                </admin-reservations-list>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="prospects">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <admin-interests-list></admin-interests-list>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection