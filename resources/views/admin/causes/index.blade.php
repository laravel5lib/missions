@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="active">Causes</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="col-xs-12 col-md-2">
        @include('admin.partials._toolbar')
    </div>
    <div class="col-xs-12 col-md-10">
        <project-causes></project-causes>
    </div>
</div>

<!-- Create Cause Modal -->
<div class="modal fade" id="causeEditor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
            <cause-editor></cause-editor>
    </div>
</div>
@endsection