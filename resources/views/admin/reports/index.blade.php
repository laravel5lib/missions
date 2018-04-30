@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' =>[
        'admin' => 'Dashboard',
        'active' => 'Reports'
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
        <reports-list></reports-list>
    </div>
</div>
@endsection