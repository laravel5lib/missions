@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Medical Releases <small>&middot; {{ $release->name }}</small></h3>
                    <h3 class="visible-xs text-center">User Medical Releases<br><small>{{ $release->name }}</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a href="{{ url('admin/records/medical-releases') }}" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span> Back
                        </a>
                        {{-- <a href="{{ url('admin/records/medical-releases/' . $release->id . '/edit') }}" class="btn btn-primary">
                            <i class="fa fa-edit icon-left"></i> Edit
                        </a> --}}
                    </div>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <div class="btn-group">
                        <a href="{{ url('admin/records/medical-releases') }}" class="btn btn-primary-darker">
                            <span class="fa fa-chevron-left icon-left"></span>
                        </a>
                        <a href="{{ url('admin/records/medical-releases/' . $release->id . '/edit') }}" class="btn btn-primary">
                            <i class="fa fa-edit icon-left"></i> Edit
                        </a>
                    </div>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv">
    @include('_medical_release', $release)

    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <notes type="medical_releases" id="{{ $release->id }}" :can-modify="1"></notes>
            </div>
        </div>
    </div>
@endsection