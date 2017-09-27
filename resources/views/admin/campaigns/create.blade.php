@extends('admin.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection

@section('content')
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <campaign-create></campaign-create>
            </div>
        </div>
    </div>
@endsection