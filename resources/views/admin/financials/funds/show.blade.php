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
                <fund-editor id="{{ $fund->id }}"></fund-editor>
            </div>
            <div class="col-sm-8">
                <notes type="funds"
                       id="{{ $fund->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3"
                       :can-modify="{{ auth()->user()->can('modify-notes') }}">
                </notes>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h5>Transactions</h5>
                <admin-transactions-list fund="{{ $fund->id }}"
                                         storage-name="AdminFundTransactionsConfig">
                </admin-transactions-list>
            </div>
        </div>
    </div>
@stop