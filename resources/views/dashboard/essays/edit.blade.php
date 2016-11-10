@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3>My Essays <small>&middot; Edit</small></h3>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <essay-create-update user-id="{{ auth()->id() }}" :is-update="true" id="{{ $id }}"></essay-create-update>
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection