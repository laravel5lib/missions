@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li><a href="{{ url('/admin/campaigns') }}">Campaigns</a></li>
                    <li class="active">{{ $campaign->name }} - {{ country($campaign->country_code) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <ul class="nav nav-pills nav-stacked" role="tablist">

                <li role="presentation" class="{{ $tab == 'details' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/details') }}">Details</a>
                </li>

                @can('view', \App\Models\v1\Trip::class)
                <li role="presentation" class="{{ $tab == 'trips' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/trips') }}">Travel Groups</a>
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

                <!-- @can('view', \App\Models\v1\Room::class)
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
                @endcan -->

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

                <!-- @can('view', \App\CampaignTransport::class)
                <li role="presentation" class="{{ $tab == 'transports' ? 'active' : '' }} dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Transportation <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ $tab == 'transports' ? 'active' : '' }}"><a href="{{ url('admin/campaigns/'.$campaign->id.'/transports') }}">Transports</a></li>
                    </ul>
                </li>
                @endcan

                @can('view', \App\Models\v1\Promotional::class)
                <li role="presentation" class="{{ $tab == 'promotionals' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/promotionals') }}">Promos</a>
                </li>
                @endcan -->

            </ul>
        </div>

        <div class="col-sm-9">
            @yield('tab')
        </div>
    </div>
</div>
@endsection