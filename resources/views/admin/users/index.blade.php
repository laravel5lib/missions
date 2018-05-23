@extends('layouts.admin')

@section('content')

@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'active' => 'Users'
]])
@endbreadcrumbs

<hr class="divider lg inv">

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('admin.partials._nav_people')
        </div>
        <div class="col-sm-10">
            @component('panel')
                @slot('body')
                    <admin-users-list></admin-users-list>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endsection