@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
        	<div class="col-sm-12">
	            <h3>Groups</h3>
	        </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-groups></admin-groups>
            </div>
        </div>
    </div>
@endsection