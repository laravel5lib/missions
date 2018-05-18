@extends('layouts.admin')

@section('content')
    @breadcrumbs(['links' =>[
        'admin' => 'Dashboard',
        'active' => 'Reports'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <reports-list></reports-list>
        </div>
    </div>
</div>
@endsection