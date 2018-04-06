@extends('site.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('site.fundraisers.nav')
            </div>
            <div class="col-md-9">

                <fundraiser-sharing :fundraiser="{{ $fundraiser }}"></fundraiser-sharing>

            </div><!-- end col -->
        </div><!-- end row -->
        <hr class="divider inv xlg">
    </div><!-- end container -->
@stop