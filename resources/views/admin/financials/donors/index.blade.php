@extends('layouts.admin')

@section('content')

@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'active' => 'Donors'
]])
@endbreadcrumbs

<hr class="divider lg inv">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            @include('admin.partials._nav_people')
        </div>
        <div class="col-xs-10">
            @component('panel')
                @slot('body')
                    <admin-donors-list></admin-donors-list>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@stop