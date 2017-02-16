@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>Edit Your Group</h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
                <a href="/dashboard/groups/{{ $group->id }}" class="btn btn-primary pull-right">
                    Group Details
                </a>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            	<div class="panel panel-default">
            		<div class="panel-body">
		               <admin-group-edit group-id="{{ $id }}"></admin-group-edit>
		            </div>
                </div>
            </div>
        </div>
    </div>
@endsection