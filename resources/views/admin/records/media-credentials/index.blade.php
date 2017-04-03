@extends('admin.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">User Media Credentials</h3>
                    <h3 class="visible-xs text-center">User Media Credentials</h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="/admin/records/media-credentials/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> User Media Credential</a>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <a href="/admin/records/media-credentials/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Media Credential</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
@stop

@section('tab')
    <media-credentials-list user-id="{{ auth()->user()->id }}"></media-credentials-list>
    <hr class="divider inv xlg">
@stop