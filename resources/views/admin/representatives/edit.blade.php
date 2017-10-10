@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row hidden-xs">
                <div class="col-sm-8">
                    <h3>Edit Trip Rep</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="/admin/representatives" class="btn btn-link">
                        <i class="fa fa-list icon-left"></i> All Trip Reps
                    </a>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-8 text-center">
                    <h3>Edit Trip Rep</h3>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="/admin/representatives" class="btn btn-link">
                        <i class="fa fa-list icon-left"></i> All Trip Reps
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <representative-edit-form :representative="{{ $representative }}"></representative-edit-form>
            </div>
        </div>
    </div>
@endsection