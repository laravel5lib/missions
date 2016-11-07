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
                    <div class="btn-group pull-right">
                        <a href="#" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#">Edit</a></li>
                            <li><a href="#">Transfer Funds</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Delete</a></li>
                        </ul>
                    </div>
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
                        <reconcile-fund id="{{ $fund->id }}"></reconcile-fund>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Transactions</h5>
                    </div>
                    <admin-transactions-list fund="{{ $fund->id }}" storage-name="AdminFundTransactionsConfig"></admin-transactions-list>

                    {{--<div class="list-group">
                        <div class="list-group-item">
                            ...
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@stop