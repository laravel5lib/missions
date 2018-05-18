@extends('layouts.admin')

@section('content')
    @include('admin.partials._nav_donations')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Transactions'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            @component('panel')
                @slot('title')
                    <h5>All Transactions</h5>
                @endslot
                @slot('body')
                    <admin-transactions-list></admin-transactions-list>
               @endslot
            @endcomponent
        </div>
    </div>
</div>
@stop