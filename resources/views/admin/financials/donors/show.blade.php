@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $donor->name }}</h3>
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
                        <label>Donor Name</label>
                        <p>{{ $donor->name }}</p>
                        <label>Type</label>
                        <p>{{ str_singular(ucwords($donor->account_type)) }}</p>
                        <label>Last Update</label>
                        <p>{{ $donor->updated_at->format('F j, Y h:i a') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-header">Transactions</h5>
                    </div>
                    <admin-transactions-list donor="{{ $donor->id }}" storage-name="AdminDonorTransactionsConfig"></admin-transactions-list>

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