@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="hidden-xs">My Teams</h3>
                    <h3 class="text-center visible-xs">My Teams</h3>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <accordion :one-at-atime="true" type="default">
                    <panel is-open header="Tutorial Details">
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Tip 1!</strong> Don't Worry.
                        </div>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Tip 2!</strong> Be Happy.
                        </div>
                    </panel>
                </accordion>
            </div>
        </div>
        <team-manager user-id="{{ Auth::user()->id }}" group-id="{{ $groupId }}"></team-manager>
        <hr class="divider inv lg">
    </div>
@endsection

@section('tour')

@endsection