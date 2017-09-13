@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <a href="#">
                    <h3>
                        <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($campaign->getAvatar()->source . "?w=200") }}" alt="{{ $campaign->name }}">
                        {{ $campaign->name }}
                        <small>&middot; {{ country($campaign->country_code) }}</small>
                    </h3>
                </a>
            </div>
            <div class="col-xs-4 text-right">
                <hr class="divider inv xs">
                <hr class="divider inv sm">
                <div class="btn-group" role="group">
                    <a href="{{ url('admin/campaigns') }}" class="btn btn-primary-darker">
                        <span class="fa fa-chevron-left icon-left"></span>
                        @cannot('update', \App\Models\v1\Campaign::class)
                            Back
                        @endcannot
                    </a>
                    @can('update', \App\Models\v1\Campaign::class)
                        <a href="{{ url('admin/campaigns/'.$campaign->id.'/edit') }}"
                           class="btn btn-primary">
                            Edit
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs" role="tablist">

                @can('view', \App\Models\v1\Trip::class)
                <li role="presentation" class="{{ $tab == 'trips' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/trips') }}">Trips</a>
                </li>
                @endcan

                @can('view', \App\Models\v1\Team::class)
                <li role="presentation" class="{{ ($tab == 'squads' || $tab == 'squad-types') ? 'active' : '' }} dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Squads <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ $tab == 'squads' ? 'active' : '' }}"><a href="{{ url('admin/campaigns/'.$campaign->id.'/squads') }}">Rosters</a></li>

                        @can('view', \App\Models\v1\TeamType::class)
                        <li class="{{ $tab == 'squad-types' ? 'active' : '' }}">
                            <a href="{{ url('admin/campaigns/'.$campaign->id.'/squad-types') }}">Squad Types</a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan

                @can('view', \App\Models\v1\Room::class)
                 <li role="presentation" class="{{ $tab == 'accommodations' || $tab == 'rooming-manager' || $tab == 'room-types' ? 'active' : '' }} dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Rooming <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ $tab == 'rooming-manager' ? 'active' : '' }}">
                            <a href="{{ url('admin/campaigns/'.$campaign->id.'/rooming-manager') }}">Room Assignments</a>
                        </li>

                        @can('view', \App\Models\v1\RoomType::class)
                            <li class="{{ $tab == 'room-types' ? 'active' : '' }}">
                                <a href="{{ url('admin/campaigns/'.$campaign->id.'/room-types') }}">Room Types</a>
                            </li>
                        @endcan

                        @can('view', \App\Models\v1\Accommodation::class)
                            <li class="{{ $tab == 'accommodations' ? 'active' : '' }}">
                                <a href="{{ url('admin/campaigns/'.$campaign->id.'/accommodations') }}">Accommodations</a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('view', \App\Models\v1\Region::class)
                <li role="presentation" class="{{ $tab == 'regions' || $tab == 'region-accommodations' ? 'active' : '' }} dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                      Regions <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">

                        @can('view', \App\Models\v1\Accommodations::class)
                        <li>
                            <a href="{{ url('admin/campaigns/'.$campaign->id.'/region-accommodations') }}">
                                Accommodations
                            </a>
                        </li>
                        @endcan

                        <li class="{{ $tab == 'regions' ? 'active' : '' }}">
                            <a href="{{ url('admin/campaigns/'.$campaign->id.'/regions') }}">Squad Assignments</a>
                        </li>
                    </ul>
                </li>
                @endcan

                @can('view', \App\CampaignTransport::class)
                <li role="presentation" class="{{ $tab == 'transports' ? 'active' : '' }} dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Transportation <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ $tab == 'transports' ? 'active' : '' }}"><a href="{{ url('admin/campaigns/'.$campaign->id.'/transports') }}">Transports</a></li>
                    </ul>
                </li>
                @endcan

                <li role="presentation" class="{{ $tab == 'details' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/details') }}">Details</a>
                </li>

                @can('view', \App\Models\v1\Promotional::class)
                <li role="presentation" class="{{ $tab == 'promotionals' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/promotionals') }}">Promos</a>
                </li>
                @endcan

            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @yield('tab')
        </div>
    </div>
</div>
@endsection