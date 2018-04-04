@extends('site.layouts.default')
@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('site.fundraisers.nav')
            </div>
            <div class="col-md-9">

                <fundraisers-uploads id="{{ $fundraiser->uuid }}" sponsor-id="{{ auth()->user()->id }}"></fundraisers-uploads>

            </div><!-- end col -->
        </div><!-- end row -->
        <hr class="divider inv xlg">
    </div><!-- end container -->
@stop