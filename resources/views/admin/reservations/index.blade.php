@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Reservations <small>All</small></h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
	<div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-reservations-list></admin-reservations-list>
            </div>
        </div>
    </div>
@endsection