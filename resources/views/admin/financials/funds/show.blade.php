@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $fund->name }} <small>&middot; Fund</small></h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a class="btn btn-default pull-right" href="{{ url('admin/funds') }}"><i class="fa fa-chevron-left"></i> Funds</a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-8">
                <h5>Transactions</h5>
                <admin-transactions-list fund="{{ $fund->id }}"
                                         storage-name="AdminFundTransactionsConfig">
                </admin-transactions-list>
            </div>
        </div>
    </div>
@stop