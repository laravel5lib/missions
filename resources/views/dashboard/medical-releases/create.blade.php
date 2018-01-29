@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
<hr class="divider inv lg">
<div class="container">
        <div class="row">
            <div class="col-xs-12">
                <medical-create-update></medical-create-update>
            </div>
        </div>
</div>
<hr class="divider inv xlg">
@endsection