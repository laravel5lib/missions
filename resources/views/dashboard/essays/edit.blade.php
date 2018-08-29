@extends('dashboard.layouts.default')

@section('content')
    @breadcrumbs(['links' => [
        'dashboard' => 'Dashboard',
        'dashboard/records/essays' => 'Travel Documents',
        'active' => 'Essays'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <essay-create-update 
                            :is-update="true" id="{{ $id }}"
                            reservation-id="{{ request()->get('reservation') }}" 
                            requirement-id="{{ request()->get('requirement') }}"
                        ></essay-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection