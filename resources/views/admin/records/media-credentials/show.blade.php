@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Media Credentials <small>&middot; {{ $credential->applicant_name }}</small></h3>
                    <h3 class="visible-xs text-center">User Media Credentials<br><small>{{ $credential->applicant_name }}</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        @can('update', $credential)
                        <a href="{{ url('admin/records/media-credentials/' . $credential->id . '/edit') }}" class="btn btn-primary">
                            Edit
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        @can('update', $credential)
                        <a href="{{ url('admin/records/media-credentials/' . $credential->id . '/edit') }}" class="btn btn-primary">
                            Edit
                        </a>
                        @endcan
                    </div>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
    @include('_media_credential', $credential)

    @can('view', \App\Models\v1\Note::class)
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <notes type="media_credentials" id="{{ $credential->id }}" :can-modify="1"></notes>
            </div>
        </div>
    </div>
    @endcan

@endsection