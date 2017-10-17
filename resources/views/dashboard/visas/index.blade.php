@extends('dashboard.records.index')

@section('header')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <h3 class="hidden-xs">My Visas</h3>
                    <h3 class="text-center visible-xs">My Visas</h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="visas/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Visa</a>
                </div>
                <div class="col-sm-12 text-center visible-xs">
                    <a href="visas/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> Add Visa</a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('tab')

<div class="panel panel-default">
    <div class="panel-body">
        <visas-list user-id="{{ auth()->user()->id }}"></visas-list>
    </div>
</div>

@endsection