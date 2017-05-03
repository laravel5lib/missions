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
                    <a href="{{ url('admin/campaigns') }}" class="btn btn-primary-darker"><span class="fa fa-chevron-left icon-left"></span></a>
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/edit') }}" class="btn btn-primary">Edit</a>
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
                <li role="presentation" class="{{ $tab == 'trips' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/trips') }}">Trips</a>
                </li>
                <li role="presentation" class="{{ $tab == 'details' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/details') }}">Details</a>
                </li>
                <li role="presentation" class="{{ $tab == 'promotionals' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/promotionals') }}">Promotionals</a>
                </li>
                <li role="presentation" class="{{ $tab == 'transports' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/transports') }}">Transportation</a>
                </li>
                <li role="presentation" class="{{ $tab == 'teams' ? 'active' : '' }}">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/teams') }}">Teams</a>
                </li>
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