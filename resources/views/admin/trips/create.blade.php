@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
        	<div class="col-sm-8">
                <hr class="divider inv sm">
	            <h3 class="hidden-xs">Create A Trip</h3>
                <h3 class="visible-xs text-center">Create A Trip</h3>
	        </div>
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv">
                <a href="{{ url('admin/campaigns/' . $campaign->id) }}" class="btn btn-default"><span class="fa fa-chevron-left icon-left"></span> Back To All</a>
                <hr class="divider inv">
            </div>
            <div class="col-xs-12 text-center visible-xs">
                <hr class="divider inv sm">
                <a href="{{ url('admin/campaigns/' . $campaign->id) }}" class="btn btn-default"><span class="fa fa-chevron-left icon-left"></span> Back To All</a>
                <hr class="divider inv">
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
                        <admin-trip-create-update campaign-id="{{ $campaign->id }}" country-code="{{ $campaign->country_code }}"></admin-trip-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div><!-- end container -->
@endsection