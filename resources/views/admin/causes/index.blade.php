@extends('admin.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <project-causes></project-causes>
            </div>
        </div>
    </div>

    <!-- Create Cause Modal -->
    <div class="modal fade" id="causeEditor" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
                <cause-editor></cause-editor>
        </div>
    </div>

@endsection