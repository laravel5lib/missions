@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Create New Transaction</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a href="{{ url('admin/transactions') }}" class="btn btn-default pull-right"><i class="fa fa-chevron-left"></i> Transactions </a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                <transaction-form></transaction-form>
            </div>
        </div>
    </div>
@stop