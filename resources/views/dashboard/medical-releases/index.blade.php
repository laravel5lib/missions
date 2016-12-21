@extends('dashboard.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>My Medical Releases</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="medical-releases/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Medical Release</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('tab')
    <medicals-list user-id="{{ auth()->user()->id }}"></medicals-list>
    <hr class="divider inv xlg">
@stop