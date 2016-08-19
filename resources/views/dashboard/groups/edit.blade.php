@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Edit Your Group</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <groups-list user-id="{{ auth()->check() ? auth()->id() : null }}" :select-ui="true" current-group="{{ $id }}"></groups-list>
            </div>
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            	<div class="panel panel-default">
            		<div class="panel-body">
		               <group-edit group-id="{{ $id }}"></group-edit>
		            </div>
                </div>
            </div>
        </div>
    </div>
@endsection