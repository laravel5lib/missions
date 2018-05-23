@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
@breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/organizations' => 'Organizations',
        'active' => 'New'
    ]])
    @endbreadcrumbs
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            	<div class="panel panel-default">
            		<div class="panel-body">
                		<admin-group-create></admin-group-create>
                	</div>
                </div>
            </div>
        </div>
    </div>
@endsection