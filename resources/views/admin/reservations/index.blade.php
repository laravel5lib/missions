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
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'active' => 'Reservations'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="col-xs-12">

        <div class="row">
            <div class="col-sm-2">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-pills nav-stacked" role="tablist">
                    <li><a href="#current" role="tab" data-toggle="tab">Current</a></li>
                    <li><a href="#archived" role="tab" data-toggle="tab">Archived</a></li>
                    <li><a href="#dropped" role="tab" data-toggle="tab">Dropped</a></li>
                    @can('view', \App\Models\v1\TripInterest::class)
                        <li><a href="#prospects" role="tab" data-toggle="tab">Interests</a></li>
                    @endcan
                </ul>
            </div>
            <div class="col-sm-10">
                <!-- TAB CONTENT -->
                <div class="tab-content">
                    <div class="active tab-pane fade in" id="current">
                        @component('panel')
                            @slot('body')
                                <admin-reservations-list type="current"
                                                        campaign-id="{{ $campaign->id }}"
                                                        storage-name="AdminReservationsCurrentStorage">
                                </admin-reservations-list>
                            @endslot
                        @endcomponent
                    </div>
                    <div class="tab-pane fade" id="archived">
                        @component('panel')
                            @slot('body')
                                <admin-reservations-list type="archived"
                                                        campaign-id="{{ $campaign->id }}"
                                                        storage-name="AdminReservationsArchivedStorage">
                                </admin-reservations-list>
                            @endslot
                        @endcomponent
                    </div>
                    <div class="tab-pane fade" id="dropped">
                        @component('panel')
                            @slot('body')
                                <admin-reservations-list type="dropped"
                                                        campaign-id="{{ $campaign->id }}"
                                                        storage-name="AdminReservationsDroppedStorage">
                                </admin-reservations-list>
                            @endslot
                        @endcomponent
                    </div>
                    <div class="tab-pane fade" id="prospects">
                        @component('panel')
                            @slot('body')
                                <admin-interests-list></admin-interests-list>
                            @endslot
                        @endcomponent
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection