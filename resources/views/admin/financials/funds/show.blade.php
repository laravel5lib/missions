@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $fund->name }}</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a href="#" class="btn btn-primary pull-right">New <i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Details</h5>
                    </div>
                    <div class="panel-body">
                        <label>Fund Name</label>
                        <p>{{ $fund->name }}</p>
                        <label>Type</label>
                        <p>{{ str_singular(ucwords($fund->fundable_type)) }}</p>
                        <label>Balance</label>
                        <h5>$ {{ $fund->balance }}</h5>
                        <label>Last Update</label>
                        <p>{{ $fund->updated_at->format('F j, Y h:i a') }}</p>
                    </div>
                    <div class="panel-footer text-right">
                        <btn class="btn btn-sm btn-default"><i class="fa fa-calculator"></i> Reconcile</btn>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Transactions</h5>
                    </div>
                    <div class="list-group">
                        <div class="list-group-item">
                            ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop