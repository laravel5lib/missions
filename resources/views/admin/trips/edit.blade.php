@extends('admin.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <campaign-trip-form :trip="{{ $trip }}"
                                    :campaign="{{ $trip->campaign }}"
                                    :is-update="true">
                </campaign-trip-form>
            </div>
        </div>
    </div>
@endsection