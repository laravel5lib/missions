@extends('site.layouts.default')
@section('styles')
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/css/Jcrop.min.css" type="text/css">--}}
    <link rel="stylesheet" href="{{ url('/vendor/redactor/redactor.min.css') }}" />
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58ba1e02535b950011d40583&product=inline-share-buttons"></script>
@endsection
@section('scripts')
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/2.0.0/js/Jcrop.min.js"></script>--}}
@endsection

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('site.fundraisers.nav')
            </div>
            <div class="col-md-9">

                <fundraisers-description :fundraiser="{{ $fundraiser }}"></fundraisers-description>

            </div><!-- end col -->
        </div><!-- end row -->
        <hr class="divider inv xlg">
    </div><!-- end container -->
    <markdown-example-modal></markdown-example-modal>
@stop