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
                    <div class="panel-heading">
                        <h5>Essay</h5>
                    </div>
                    <div class="panel-body">
                        <essay-create-update
                            reservation-id="{{ request()->get('reservation') }}" 
                            requirement-id="{{ request()->get('requirement') }}"
                        ></essay-create-update>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection