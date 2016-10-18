@extends('admin.layouts.default')

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
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Reservations</h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a href="/admin/trips/create" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Reservations</h3>
            </div>
            <div class="col-sm-4 text-center">
                <a href="/admin/trips/create" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
	<div class="container">
        <div class="row">
            <div class="col-sm-12">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <li><a href="#current" role="tab" data-toggle="tab">Current</a></li>
                    <li><a href="#archived" role="tab" data-toggle="tab">Archived</a></li>
                    <li><a href="#dropped" role="tab" data-toggle="tab">Dropped</a></li>
                    <li><a href="#prospects" role="tab" data-toggle="tab">Prospects</a></li>
                </ul>
                <!-- TAB CONTENT -->
                <div class="tab-content">
                    <div class="active tab-pane fade in" id="current">
                        <admin-reservations-list type="current" storage-name="AdminReservationsCurrentStorage"></admin-reservations-list>
                    </div>
                    <div class="tab-pane fade" id="archived">
                        <admin-reservations-list type="archived" storage-name="AdminReservationsArchivedStorage"></admin-reservations-list>
                    </div>
                    <div class="tab-pane fade" id="dropped">
                        <admin-reservations-list type="dropped" storage-name="AdminReservationsDroppedStorage"></admin-reservations-list>
                    </div>
                    <div class="tab-pane fade" id="prospects">
                        <admin-interests-list></admin-interests-list>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection