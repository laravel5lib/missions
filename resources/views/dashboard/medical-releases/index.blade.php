@extends('dashboard.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">My Medical Releases</h3>
                    <h3 class="visible-xs text-center">My Medical Releases</h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="medical-releases/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Medical Release</a>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <a href="medical-releases/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Medical Release</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
@stop

@section('tab')
    <medicals-list user-id="{{ auth()->user()->id }}" managing="{{ (auth()->user()->managing()->count()) or 0 }}"></medicals-list>
    <hr class="divider inv xlg">
@stop