@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-12">
                <h3>Reports</h3>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-12 text-center">
                <h3>Reports</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <reports-list></reports-list>
            </div>
        </div>
    </div>
@endsection