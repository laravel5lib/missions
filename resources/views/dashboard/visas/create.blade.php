    @extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/css/Jcrop.css" type="text/css">
@endsection
@section('scripts')
    <script src="http://jcrop-cdn.tapmodo.com/v2.0.0-RC1/js/Jcrop.js"></script>
@endsection

@section('content')
<div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3 class="hidden-xs">My Visas <small>&middot; Create</small></h3>
                    <h3 class="text-center visible-xs">My Visas<br><small>Create</small></h3>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <hr class="divider inv sm">
                    <a href="/dashboard/records/visas" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back To All</a>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <a href="/dashboard/records/visas" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back To All</a>
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
                        <visa-create-update></visa-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
<hr class="divider inv xlg">
@endsection