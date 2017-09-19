@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Visas <small>&middot; {{ $visa->given_names. ' '. $visa->surname }}</small></h3>
                    <h3 class="visible-xs text-center">User Visas<br><small>{{ $visa->given_names. ' '. $visa->surname }}</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a onclick="window.history.back()" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        @can('update', $visa)
                        <a href="{{ url('admin/records/visas/' . $visa->id . '/edit') }}" class="btn btn-primary">
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
                        @can('update', $visa)
                        <a href="{{ url('admin/records/visas/' . $visa->id . '/edit') }}" class="btn btn-primary">
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
    @include('_visa', $visa)

    @can('view', \App\Models\v1\Note::class)
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <notes type="visas" id="{{ $visa->id }}" :can-modify="1"></notes>
            </div>
        </div>
    </div>
    @endcan

@endsection