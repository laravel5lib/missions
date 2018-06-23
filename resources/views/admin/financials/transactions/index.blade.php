@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Transactions'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('admin.partials._nav_donations')
        </div>
        <div class="col-sm-10">
            <admin-transactions-list></admin-transactions-list>
        </div>
    </div>
</div>
@stop