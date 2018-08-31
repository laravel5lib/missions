@extends('admin.records.index')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        '/admin/records/' => 'Records',
        'active' => ucwords(request()->segment(3))
    ]])
    @endbreadcrumbs
@stop

@section('tab')
    <div class="panel panel-default">
        <div class="panel-body">
            <passports-list></passports-list>
        </div>
    </div>
    <hr class="divider inv lg">
@stop