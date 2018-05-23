@extends('layouts.admin')

@section('content')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Funds'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('admin.partials._nav_donations')
        </div>
        <div class="col-sm-10">
            @component('panel')
                @slot('title')
                    <h5>All Funds</h5>
                @endslot
                @slot('body')
                    <admin-funds-list></admin-funds-list>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@stop