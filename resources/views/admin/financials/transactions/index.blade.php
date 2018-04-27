@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Transactions'
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="col-xs-12 col-md-2">
        @include('admin.partials._toolbar')
    </div>
    <div class="col-xs-12 col-md-10">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.financials.partials._tabs')
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <admin-transactions-list></admin-transactions-list>
            </div><!-- end panel-body -->
        </div><!-- end panel -->
    </div>
</div>
@stop