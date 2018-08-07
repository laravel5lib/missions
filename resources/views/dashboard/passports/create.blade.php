@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs">My Passports <small>&middot; Create</small></h3>
                <h3 class="visible-xs text-center">My Passports<br><small>Create</small></h3>
            </div>
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv sm">
                <a href="/dashboard/records/passports" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
            </div>
            <div class="col-sm-4 text-center visible-xs">
                <a href="/dashboard/records/passports" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <passport-create-update 
                            reservation-id="{{ request()->get('reservation') }}" 
                            requirement-id="{{ request()->get('requirement') }}"
                        ></passport-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
</div>
<hr class="divider inv xlg">
@endsection