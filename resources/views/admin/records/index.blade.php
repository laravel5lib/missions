@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => ucwords($tab)
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">

    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                @include('admin.records.layouts.menu', [
                'links' => config('navigation.admin.records')
                ])
            </div>
            <div class="col-sm-9">
                <travel-documents-list 
                    url="{{ $tab }}"
                    type="{{ $tab }}"
                ></travel-documents-list>
                <hr class="divider inv lg">
            </div>
        </div>
    </div>

@endsection