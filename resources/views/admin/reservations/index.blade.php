@extends('layouts.admin')

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
                @sidenav(['links' => [
                    "admin/campaigns/{$campaign->id}/reservations" => 'Missionaries',
                    "admin/campaigns/{$campaign->id}/flights" => 'Flights'
                ]])
                @endsidenav
            </div>
            <div class="col-sm-10">
                <!-- TAB NAVIGATION -->
                @component('panel')
                    @slot('body')
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="active"><a href="#current" role="tab" data-toggle="tab"><i class="fa fa-fire"></i> Active</a></li>
                            <li><a href="#dropped" role="tab" data-toggle="tab"><i class="fa fa-trash-o"></i> Dropped</a></li>
                            @can('view', \App\Models\v1\TripInterest::class)
                                <li><a href="#prospects" role="tab" data-toggle="tab"><i class="fa fa-edit"></i> Interests</a></li>
                            @endcan
                        </ul>
                        <hr class="divider">
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="current">
                                <admin-reservations-list type="current"
                                                        campaign-id="{{ $campaign->id }}"
                                                        storage-name="AdminReservationsCurrentStorage">
                                </admin-reservations-list>
                            </div>
                            <div class="tab-pane fade" id="dropped">
                                <admin-reservations-list type="dropped"
                                                        campaign-id="{{ $campaign->id }}"
                                                        storage-name="AdminReservationsDroppedStorage">
                                </admin-reservations-list>
                            </div>
                            <div class="tab-pane fade" id="prospects">
                                <admin-interests-list campaign-id="{{ $campaign->id }}"></admin-interests-list>
                            </div>
                        </div>
                    @endslot
                @endcomponent

            </div>
        </div>
    </div>
</div>
@endsection